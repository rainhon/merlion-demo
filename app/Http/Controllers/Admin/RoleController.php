<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Merlion\Http\Controllers\CrudController;

class RoleController extends CrudController
{
    protected string $model = Role::class;

    protected function schemas(): array
    {
        return [
            'id',
            [
                'name' => 'name',
                'type' => 'text',
                'label' => '角色名称',
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
            [
                'name' => 'permissions',
                'type' => 'select',
                'multiple' => true,
                'relationship' => 'permissions',
                'relationshipTitleAttribute' => 'name',
                'label' => '权限',
                'show_create' => true,
                'show_edit' => true,
            ],
        ];
    }

    protected function searches(): array
    {
        return ['name', 'slug'];
    }
}
