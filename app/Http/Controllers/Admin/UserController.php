<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Merlion\Http\Controllers\CrudController;

class UserController extends CrudController
{
    protected string $model = User::class;

    protected function schemas(): array
    {
        return [
            'id',
            [
                'name' => 'name',
                'type' => 'text',
                'label' => '用户名',
                'rules' => 'required|string|max:255',
                'show_index' => true,
                'show_create' => true,
                'show_edit' => true,
                'show_detail' => true,
                'filterable' => true,
                'sortable' => true,
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => '邮箱',
                'rules' => 'required|email',
                'show_index' => true,
                'show_create' => true,
                'show_edit' => true,
                'show_detail' => true,
                'filterable' => true,
                'sortable' => true,
            ],
            [
                'name' => 'roles',
                'type' => 'select',
                'multiple' => true,
                'relationship' => 'roles',
                'relationshipTitleAttribute' => 'name',
                'label' => '角色',
                'show_create' => true,
                'show_edit' => true,
                'show_detail' => true,
            ],
        ];
    }

    protected function searches(): array
    {
        return ['name', 'email'];
    }
}