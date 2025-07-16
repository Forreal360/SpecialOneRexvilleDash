<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\V1\Auth\LoginComponent;
use App\Livewire\V1\Panel\Home\HomeComponent;
use App\Http\Controllers\AuthController;
use App\Livewire\V1\Panel\Admin\{GetAdminsComponent, CreateAdminComponent, UpdateAdminComponent};
use App\Livewire\V1\Auth\ForgotPasswordComponent;
use App\Livewire\V1\Panel\Promotion\GetPromotionsComponent;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', LoginComponent::class)->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', ForgotPasswordComponent::class)->name('password.request');

Route::group(["prefix" => "panel", "middleware" => "auth", "as" => "v1.panel."], function () {

    Route::get('/home', HomeComponent::class)->name("home");

    /* ----------------- Admins ----------------- */
    Route::get('/admins', GetAdminsComponent::class)->name('admins.index');
    Route::get('/admins/create', CreateAdminComponent::class)->name('admins.create');
    Route::get('/admins/{id}/edit', UpdateAdminComponent::class)->name('admins.edit');

    /* ----------------- Promotions ----------------- */
    Route::get('/promotions', GetPromotionsComponent::class)->name('promotions.index');
    Route::get('/promotions/create', \App\Livewire\V1\Panel\Promotion\CreatePromotionComponent::class)->name('promotions.create');
});

