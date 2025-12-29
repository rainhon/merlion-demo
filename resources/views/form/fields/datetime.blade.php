@php
    $name = $self->getName();
    $id = $self->getId();
    $label = $self->getLabel();
    $value = $self->getValue();
    $format = $self->format ?? 'Y-m-d H:i:s';
    $withTime = $self->withTime ?? true;
    $minDate = $self->minDate ?? null;
    $maxDate = $self->maxDate ?? null;
    $disabled = $self->disabled ?? false;
    $readonly = $self->readonly ?? false;
    $placeholder = $self->placeholder ?? '选择日期时间';
    $useCurrentTime = $self->useCurrentTime ?? true;
    $label_position = $self->getContext('label_position') ?? null;

    // 格式化显示值
    $displayValue = $value;
    if (!empty($value)) {
        try {
            if (is_numeric($value)) {
                $displayValue = date($format, (int)$value);
            } elseif (is_string($value)) {
                $dateTime = new \DateTime($value);
                $displayValue = $dateTime->format($format);
            }
        } catch (\Exception $e) {
            $displayValue = $value;
        }
    } elseif ($useCurrentTime) {
        // 如果值为空且启用当前时间，显示当前时间
        $displayValue = date($format);
    } else {
        $displayValue = '';
    }

    // 确定 datetimepicker 的格式
    $pickerFormat = $withTime ? 'Y-m-d H:i:S' : 'Y-m-d';
@endphp

<x-merlion::form.field :$label :$id :$full :$label_position>
    <div class="datetime-wrapper">
        <input {{$attributes->merge(['class' => 'form-control datetime-input'])}}
               type="text"
               name="{{$name}}"
               id="{{$id}}"
               placeholder="{{$placeholder}}"
               value="{{old($name, $displayValue)}}"
               {{ $disabled ? 'disabled' : '' }}
               {{ $readonly ? 'readonly' : '' }}
               data-format="{{$pickerFormat}}"
               data-min-date="{{$minDate}}"
               data-max-date="{{$maxDate}}"
               data-with-time="{{ $withTime ? 'true' : 'false' }}"
        >
        @if(!$disabled && !$readonly)
            <button type="button" class="btn btn-outline-secondary datetime-toggle" tabindex="-1">
                <i class="ti ti-calendar"></i>
            </button>
        @endif
    </div>
</x-merlion::form.field>

<style>
    .datetime-wrapper {
        display: flex;
        gap: 0.5rem;
    }

    .datetime-wrapper input {
        flex: 1;
    }

    .datetime-wrapper .datetime-toggle {
        border-left: none;
    }

    .datetime-wrapper .datetime-toggle:first-child input,
    .datetime-wrapper input:first-child {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .datetime-wrapper .datetime-toggle:last-child {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

@pushonce('scripts')
    <!-- Flatpickr 日期时间选择器 -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/zh.js"></script>
@endpushonce

@pushonce('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
@endpushonce

@push('scripts')
    <script nonce="{{csp_nonce()}}">
        (function() {
            const input = document.getElementById('{{$id}}');
            if (!input) return;

            const format = input.dataset.format || 'Y-m-d H:i:S';
            const withTime = input.dataset.withTime === 'true';
            const minDate = input.dataset.minDate || null;
            const maxDate = input.dataset.maxDate || null;

            // Flatpickr 配置
            const config = {
                locale: 'zh',
                dateFormat: format.replace(':S', ''), // 移除秒数的格式标记
                enableTime: withTime,
                time_24hr: true,
                allowInput: true,
                minDate: minDate,
                maxDate: maxDate,
                onOpen: function(selectedDates, dateStr, instance) {
                    instance.calendarContainer.classList.add('flatpickr-custom');
                }
            };

            // 初始化 Flatpickr
            flatpickr(input, config);

            // 日历按钮点击事件
            const toggleBtn = input.parentElement.querySelector('.datetime-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    input._flatpickr.open();
                });
            }

            // 触发表单变化事件
            input.addEventListener('change', function(e) {
                const form = input.closest('form');
                if (form) {
                    form.dispatchEvent(new CustomEvent('field_value_changed', {
                        detail: {
                            name: '{{$name}}',
                            value: e.target.value
                        },
                        bubbles: true
                    }));
                }
            });
        })();
    </script>
@endpush
