<?php
declare(strict_types=1);

namespace App\Components\Form\Fields;

use Merlion\Components\Form\Fields\Field;

class DateTime extends Field
{
    /**
     * 指定视图路径
     */
    protected mixed $view = 'form.fields.datetime';

    /**
     * 日期格式
     */
    public mixed $format = 'Y-m-d H:i:s';

    /**
     * 是否显示时间
     */
    public mixed $withTime = true;

    /**
     * 最小日期
     */
    public mixed $minDate = null;

    /**
     * 最大日期
     */
    public mixed $maxDate = null;

    /**
     * 是否禁用
     */
    public mixed $disabled = false;

    /**
     * 是否只读
     */
    public mixed $readonly = false;

    /**
     * 占位符文本
     */
    public mixed $placeholder = '选择日期时间';

    /**
     * 是否使用当前时间作为默认值
     */
    public mixed $useCurrentTime = true;

    /**
     * 从请求中获取数据
     */
    public function getDataFromRequest($request = null)
    {
        $result = parent::getDataFromRequest($request);

        // 如果为空且启用当前时间，使用当前时间
        if (empty($result) && $this->useCurrentTime) {
            return date($this->format);
        }

        return $result;
    }

    /**
     * 保存数据到模型
     */
    public function save($model)
    {
        $data = $this->getDataFromRequest();

        if (empty($data)) {
            $model->{$this->getName()} = null;
        } else {
            $model->{$this->getName()} = $data;
        }

        return $model;
    }

    /**
     * 格式化显示值（用于列表和详情页）
     */
    public function diaplayValue()
    {
        $value = $this->getValue();

        if (empty($value)) {
            return '';
        }

        // 如果是时间戳，转换为日期时间
        if (is_numeric($value)) {
            try {
                $value = date($this->format, (int)$value);
            } catch (\Exception $e) {
                return $value;
            }
        }

        // 尝试格式化已有日期
        if (is_string($value)) {
            try {
                $dateTime = new \DateTime($value);
                return $dateTime->format($this->format);
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }

    /**
     * 设置日期格式
     */
    public function format(string $format): static
    {
        $this->format = $format;
        return $this;
    }

    /**
     * 设置是否显示时间
     */
    public function withTime(bool $withTime = true): static
    {
        $this->withTime = $withTime;
        return $this;
    }

    /**
     * 设置最小日期
     */
    public function minDate($date): static
    {
        $this->minDate = $date;
        return $this;
    }

    /**
     * 设置最大日期
     */
    public function maxDate($date): static
    {
        $this->maxDate = $date;
        return $this;
    }

    /**
     * 设置是否禁用
     */
    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * 设置是否只读
     */
    public function readonly(bool $readonly = true): static
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * 设置占位符
     */
    public function placeholder(string $placeholder): static
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 设置是否使用当前时间
     */
    public function useCurrentTime(bool $use = true): static
    {
        $this->useCurrentTime = $use;
        return $this;
    }
}
