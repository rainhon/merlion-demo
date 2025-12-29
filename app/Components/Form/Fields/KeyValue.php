<?php
declare(strict_types=1);

namespace App\Components\Form\Fields;

use Merlion\Components\Form\Fields\Field;

class KeyValue extends Field
{
    protected mixed $view = 'form.fields.keyvalue';
    /**
     * 是否允许添加/删除键值对
     */
    public mixed $allowAddRemove = true;

    /**
     * 是否允许重复的键
     */
    public mixed $allowDuplicateKeys = false;

    /**
     * 键输入框占位符
     */
    public mixed $keyPlaceholder = 'Key';

    /**
     * 值输入框占位符
     */
    public mixed $valuePlaceholder = 'Value';

    /**
     * 添加按钮文本
     */
    public mixed $addButtonLabel = '添加';

    /**
     * 删除按钮文本
     */
    public mixed $removeButtonLabel = '删除';

    /**
     * 从请求中获取数据
     */
    public function getDataFromRequest($request = null)
    {
        $result = parent::getDataFromRequest($request);

        if (is_string($result)) {
            return json_decode($result, true) ?: [];
        }

        return is_array($result) ? $result : [];
    }

    /**
     * 保存数据到模型
     */
    public function save($model)
    {
        $data = $this->getDataFromRequest();

        // 过滤空键
        $data = array_filter($data, function($item) {
            return isset($item['key']) && $item['key'] !== '';
        });

        // 重新索引数组
        $data = array_values($data);

        $model->{$this->getName()} = json_encode($data);
        return $model;
    }

    /**
     * 设置是否允许添加/删除
     */
    public function allowAddRemove(bool $allow = true): static
    {
        $this->allowAddRemove = $allow;
        return $this;
    }

    /**
     * 设置是否允许重复的键
     */
    public function allowDuplicateKeys(bool $allow = true): static
    {
        $this->allowDuplicateKeys = $allow;
        return $this;
    }

    /**
     * 设置键输入框占位符
     */
    public function keyPlaceholder(string $placeholder): static
    {
        $this->keyPlaceholder = $placeholder;
        return $this;
    }

    /**
     * 设置值输入框占位符
     */
    public function valuePlaceholder(string $placeholder): static
    {
        $this->valuePlaceholder = $placeholder;
        return $this;
    }

    /**
     * 设置添加按钮文本
     */
    public function addButtonLabel(string $label): static
    {
        $this->addButtonLabel = $label;
        return $this;
    }

    /**
     * 设置删除按钮文本
     */
    public function removeButtonLabel(string $label): static
    {
        $this->removeButtonLabel = $label;
        return $this;
    }
}
