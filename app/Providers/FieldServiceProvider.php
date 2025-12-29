<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Components\Form\Fields\KeyValue;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * 注册任何应用服务
     */
    public function register(): void
    {
        //
    }

    /**
     * 启动任何应用服务
     */
    public function boot(): void
    {
        // 在框架启动后注册自定义字段
        $this->app->booted(function () {
            $this->registerCustomFields();
        });
    }

    /**
     * 注册自定义字段到 Merlion
     */
    protected function registerCustomFields(): void
    {
        $customFields = [
            'keyvalue' => KeyValue::class,
        ];

        \Merlion\Components\Form\Fields\Field::$fieldsMap = array_merge(
            \Merlion\Components\Form\Fields\Field::$fieldsMap,
            $customFields
        );
    }
}
