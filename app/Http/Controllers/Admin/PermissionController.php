<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Merlion\Http\Controllers\CrudController;

class PermissionController extends CrudController
{
    protected string $model = Permission::class;

    protected function schemas(): array
    {
        return [
            'id',
            [
                'name' => 'name',
                'type' => 'text',
                'label' => '权限名称',
                'rules' => 'required|string|max:255',
                'show_index' => true,
                'show_create' => true,
                'show_edit' => true,
                'show_detail' => true,
                'filterable' => true,
                'sortable' => true,
            ],
            [
                'name' => 'slug',
                'type' => 'text',
                'label' => '标识符',
                'rules' => 'required|string|max:255',
                'show_index' => true,
                'show_create' => true,
                'show_edit' => true,
                'show_detail' => true,
            ],
            [
                'name' => 'description',
                'type' => 'textarea',
                'label' => '描述',
                'show_create' => true,
                'show_edit' => true,
                'show_detail' => true,
            ],
        ];
    }

    protected function searches(): array
    {
        return ['name', 'slug'];
    }
}
