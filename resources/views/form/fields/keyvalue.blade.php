@php
    $name = $self->getName();
    $id = $self->getId();
    $label = $self->getLabel();

    // 获取当前值
    $value = $self->getValue();
    if (is_string($value)) {
        $value = json_decode($value, true) ?: [];
    }
    if (!is_array($value)) {
        $value = [];
    }

    // 过滤空值并重新索引
    $value = array_values(array_filter($value, function($item) {
        return isset($item['key']) && $item['key'] !== '';
    }));

    // 如果没有值，创建一个空的键值对
    if (empty($value)) {
        $value = [['key' => '', 'value' => '']];
    }

    $allowAddRemove = $self->allowAddRemove ?? true;
    $keyPlaceholder = $self->keyPlaceholder ?? 'Key';
    $valuePlaceholder = $self->valuePlaceholder ?? 'Value';
    $addButtonLabel = $self->addButtonLabel ?? '添加';
    $removeButtonLabel = $self->removeButtonLabel ?? '删除';
    $label_position = $self->getContext('label_position') ?? null;
@endphp

<x-merlion::form.field :$label :$id :$full :$label_position>
    <div id="kv-container-{{$id}}" class="key-value-wrapper" data-field-name="{{$name}}">
        @foreach($value as $index => $pair)
            <div class="key-value-row mb-2" data-index="{{$index}}">
                <div class="input-group">
                    <input type="text"
                           class="form-control key-input"
                           placeholder="{{$keyPlaceholder}}"
                           name="{{$name}}[{{$index}}][key]"
                           value="{{isset($pair['key']) ? $pair['key'] : ''}}"
                    >
                    <input type="text"
                           class="form-control value-input"
                           placeholder="{{$valuePlaceholder}}"
                           name="{{$name}}[{{$index}}][value]"
                           value="{{isset($pair['value']) ? $pair['value'] : ''}}"
                    >
                    @if($allowAddRemove)
                        <button type="button" class="btn btn-outline-danger remove-pair" tabindex="-1">
                            <i class="ti ti-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach

        @if($allowAddRemove)
            <button type="button" class="btn btn-outline-primary add-pair mt-2">
                <i class="ti ti-plus"></i> {{$addButtonLabel}}
            </button>
        @endif
    </div>

    <input type="hidden" name="{{$name}}" id="kv-data-{{$id}}" value='{{json_encode($value)}}'>
</x-merlion::form.field>

<style>
    .key-value-wrapper {
        --kv-border-color: #dbdbe0;
    }

    .key-value-row {
        animation: slideIn 0.2s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .key-value-row .input-group {
        flex-wrap: nowrap;
    }

    .key-value-row .key-input {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        max-width: 200px;
        min-width: 150px;
    }

    .key-value-row .value-input {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        flex: 1;
    }

    .key-value-row .remove-pair {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .key-value-wrapper .add-pair {
        width: 100%;
    }
</style>

@push('scripts')
    <script nonce="{{csp_nonce()}}">
        (function() {
            const container = document.getElementById('kv-container-{{$id}}');
            const dataInput = document.getElementById('kv-data-{{$id}}');
            const fieldName = container.dataset.fieldName;

            // 更新隐藏字段数据
            function updateData() {
                const rows = container.querySelectorAll('.key-value-row');
                const data = Array.from(rows).map((row, index) => {
                    const keyInput = row.querySelector('.key-input');
                    const valueInput = row.querySelector('.value-input');

                    // 更新 name 属性的索引
                    keyInput.name = `${fieldName}[${index}][key]`;
                    valueInput.name = `${fieldName}[${index}][value]`;

                    return {
                        key: keyInput.value,
                        value: valueInput.value
                    };
                }).filter(item => item.key.trim() !== '' || item.value.trim() !== '');

                dataInput.value = JSON.stringify(data);

                // 触发表单变化事件
                const form = container.closest('form');
                if (form) {
                    form.dispatchEvent(new CustomEvent('field_value_changed', {
                        detail: {
                            name: '{{$name}}',
                            value: data
                        },
                        bubbles: true
                    }));
                }
            }

            // 添加新键值对
            container.querySelector('.add-pair')?.addEventListener('click', function() {
                const rows = container.querySelectorAll('.key-value-row');
                const newIndex = rows.length;

                const newRow = document.createElement('div');
                newRow.className = 'key-value-row mb-2';
                newRow.setAttribute('data-index', newIndex);
                newRow.innerHTML = `
                    <div class="input-group">
                        <input type="text"
                               class="form-control key-input"
                               placeholder="{{$keyPlaceholder}}"
                               name="${fieldName}[${newIndex}][key]"
                               value=""
                        >
                        <input type="text"
                               class="form-control value-input"
                               placeholder="{{$valuePlaceholder}}"
                               name="${fieldName}[${newIndex}][value]"
                               value=""
                        >
                        <button type="button" class="btn btn-outline-danger remove-pair" tabindex="-1">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                `;

                const addButton = this;
                container.insertBefore(newRow, addButton);

                // 聚焦到新行的 key 输入框
                newRow.querySelector('.key-input').focus();

                updateData();
            });

            // 删除键值对
            container.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-pair');
                if (removeBtn) {
                    const row = removeBtn.closest('.key-value-row');
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(-10px)';

                    setTimeout(() => {
                        row.remove();
                        updateData();
                    }, 200);
                }
            });

            // 监听输入变化
            container.addEventListener('input', function(e) {
                if (e.target.classList.contains('key-input') || e.target.classList.contains('value-input')) {
                    updateData();
                }
            });

            // 监听键盘事件（Enter 键添加新行，Escape 键删除）
            container.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && (e.target.classList.contains('key-input') || e.target.classList.contains('value-input'))) {
                    e.preventDefault();
                    container.querySelector('.add-pair')?.click();
                }
            });

            // 初始化
            updateData();
        })();
    </script>
@endpush
