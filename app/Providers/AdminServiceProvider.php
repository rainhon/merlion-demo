<?php
namespace App\Providers;

use Merlion\AdminProvider;
use Illuminate\Support\Facades\Route;
use Merlion\Components\Layouts\Admin;

class AdminServiceProvider extends AdminProvider
{

    public function admin(Admin $admin): Admin
    {
        return $admin
            ->id('admin')
            ->default()
            ->authenticatedRoutes(function () {
                Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
                Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
                Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
            })
            ->path('admin');
    }
}