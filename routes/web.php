<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\V1\Auth\LoginComponent;
use App\Livewire\V1\Panel\Home\HomeComponent;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\V1\Dev\TestController;
use App\Livewire\V1\Panel\Admin\{GetAdminsComponent, CreateAdminComponent, UpdateAdminComponent};
use App\Livewire\V1\Auth\{ForgotPasswordComponent, ResetPasswordComponent};
use App\Livewire\V1\Panel\Promotion\{GetPromotionsComponent, CreatePromotionComponent, UpdatePromotionComponent};
use App\Livewire\V1\Panel\Role\{GetRolesComponent, CreateRoleComponent, EditRoleComponent};
use App\Livewire\V1\Panel\Client\{GetClientsComponent, CreateClientComponent, UpdateClientComponent};
use App\Livewire\V1\Panel\Client\Vehicle\{GetVehiclesComponent, CreateVehicleComponent, UpdateVehicleComponent};
use App\Livewire\V1\Panel\Client\Service\{GetClientServicesComponent, CreateClientServiceComponent, UpdateClientServiceComponent};
use App\Livewire\V1\Panel\VehicleService\{GetVehicleServicesComponent, CreateVehicleServiceComponent, UpdateVehicleServiceComponent};
use App\Livewire\V1\Panel\Appointment\{GetAppointmentsComponent, UpdateAppointmentComponent};
use App\Livewire\V1\Panel\Ticket\{GetTicketsComponent, ViewTicketComponent};

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
    Route::get('/admins', GetAdminsComponent::class)->name('admins.index')->middleware('can:administrators.get');
    Route::get('/admins/create', CreateAdminComponent::class)->name('admins.create')->middleware('can:administrators.create');
    Route::get('/admins/{id}/edit', UpdateAdminComponent::class)->name('admins.edit')->middleware('can:administrators.update');

    /* ----------------- Promotions ----------------- */
    Route::get('/promotions', GetPromotionsComponent::class)->name('promotions.index')->middleware('can:promotions.get');
    Route::get('/promotions/create', CreatePromotionComponent::class)->name('promotions.create')->middleware('can:promotions.create');
    Route::get('/promotions/{id}/edit', UpdatePromotionComponent::class)->name('promotions.edit')->middleware('can:promotions.update');

    /* ----------------- Roles ----------------- */
    Route::get('/roles', GetRolesComponent::class)->name('roles.index')->middleware('can:roles.get');
    Route::get('/roles/create', CreateRoleComponent::class)->name('roles.create')->middleware('can:roles.create');
    Route::get('/roles/{id}/edit', EditRoleComponent::class)->name('roles.edit')->middleware('can:roles.update');

    /* ----------------- Clients ----------------- */
    Route::get('/clients', GetClientsComponent::class)->name('clients.index')->middleware('can:clients.get');
    Route::get('/clients/create', CreateClientComponent::class)->name('clients.create')->middleware('can:clients.create');
    Route::get('/clients/{id}/edit', UpdateClientComponent::class)->name('clients.edit')->middleware('can:clients.update');

    /* ----------------- Vehicles ----------------- */
    Route::get('/clients/{clientId}/vehicles', GetVehiclesComponent::class)->name('vehicles.index')->middleware('can:clients-vehicles.get');
    Route::get('/clients/{clientId}/vehicles/create', CreateVehicleComponent::class)->name('vehicles.create')->middleware('can:clients-vehicles.create');
    Route::get('/clients/{clientId}/vehicles/{id}/edit', UpdateVehicleComponent::class)->name('vehicles.edit')->middleware('can:clients-vehicles.update');

    /* ----------------- Client Services ----------------- */
    Route::get('/clients/{clientId}/services', GetClientServicesComponent::class)->name('client-services.index')->middleware('can:clients-vehicles-services.get');
    Route::get('/clients/{clientId}/services/create', CreateClientServiceComponent::class)->name('client-services.create')->middleware('can:clients-vehicles-services.create');
    Route::get('/clients/{clientId}/services/{id}/edit', UpdateClientServiceComponent::class)->name('client-services.edit')->middleware('can:clients-vehicles-services.update');

    /* ----------------- Vehicle Services ----------------- */
    Route::get('/vehicle-services', GetVehicleServicesComponent::class)->name('vehicle-services.index')->middleware('can:vehicle-services.get');
    Route::get('/vehicle-services/create', CreateVehicleServiceComponent::class)->name('vehicle-services.create')->middleware('can:vehicle-services.create');
    Route::get('/vehicle-services/{id}/edit', UpdateVehicleServiceComponent::class)->name('vehicle-services.edit')->middleware('can:vehicle-services.update');

    /* ----------------- Appointments ----------------- */
    Route::get('/appointments', GetAppointmentsComponent::class)->name('appointments.index')->middleware('can:appointments.get');
    Route::get('/appointments/{id}/edit', UpdateAppointmentComponent::class)->name('appointments.edit')->middleware('can:appointments.update');
    Route::get('/appointments/{id}/confirm', \App\Livewire\V1\Panel\Appointment\ConfirmAppointmentComponent::class)->name('appointments.confirm')->middleware('can:appointments.update-status');
    Route::get('/appointments/{id}/complete', \App\Livewire\V1\Panel\Appointment\CompleteAppointmentComponent::class)->name('appointments.complete')->middleware('can:appointments.update-status');

    /* ----------------- Tickets ----------------- */
    Route::get('/tickets', GetTicketsComponent::class)->name('tickets.index')->middleware('can:tickets.get');
    Route::get('/tickets/{ticketId}/view', ViewTicketComponent::class)->name('tickets.view')->middleware('can:tickets.get');
});
