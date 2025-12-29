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
            'name',
            'email',
        ];
    }

    protected function searches(): array
    {
        return ['name', 'email'];
    }
}