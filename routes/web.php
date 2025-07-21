<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\V1\Auth\LoginComponent;
use App\Livewire\V1\Panel\Home\HomeComponent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\V1\Dev\TestController;
use App\Livewire\V1\Panel\Admin\{GetAdminsComponent, CreateAdminComponent, UpdateAdminComponent};
use App\Livewire\V1\Auth\{ForgotPasswordComponent, ResetPasswordComponent};
use App\Livewire\V1\Panel\Promotion\{GetPromotionsComponent, CreatePromotionComponent, UpdatePromotionComponent};
use App\Livewire\V1\Panel\Client\{GetClientsComponent, CreateClientComponent, UpdateClientComponent};
use App\Livewire\V1\Panel\Client\Vehicle\{GetVehiclesComponent, CreateVehicleComponent, UpdateVehicleComponent};

Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', LoginComponent::class)->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', ForgotPasswordComponent::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPasswordComponent::class)->name('password.reset');


Route::group(["prefix" => "v1/panel", "middleware" => "auth:admin", "as" => "v1.panel."], function () {

    Route::get('/home', HomeComponent::class)->name("home");

    /* ----------------- Admins ----------------- */
    Route::get('/admins', GetAdminsComponent::class)->name('admins.index');
    Route::get('/admins/create', CreateAdminComponent::class)->name('admins.create');
    Route::get('/admins/{id}/edit', UpdateAdminComponent::class)->name('admins.edit');

    /* ----------------- Promotions ----------------- */
    Route::get('/promotions', GetPromotionsComponent::class)->name('promotions.index');
    Route::get('/promotions/create', CreatePromotionComponent::class)->name('promotions.create');
    Route::get('/promotions/{id}/edit', UpdatePromotionComponent::class)->name('promotions.edit');

    /* ----------------- Clients ----------------- */
    Route::get('/clients', GetClientsComponent::class)->name('clients.index');
    Route::get('/clients/create', CreateClientComponent::class)->name('clients.create');
    Route::get('/clients/{id}/edit', UpdateClientComponent::class)->name('clients.edit');

    /* ----------------- Vehicles ----------------- */
    Route::get('/clients/{clientId}/vehicles', GetVehiclesComponent::class)->name('vehicles.index');
    Route::get('/clients/{clientId}/vehicles/create', CreateVehicleComponent::class)->name('vehicles.create');
    Route::get('/clients/{clientId}/vehicles/{id}/edit', UpdateVehicleComponent::class)->name('vehicles.edit');
});
