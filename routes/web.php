<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAppointmentController;
use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Online Booking, Secure Public Reports & Auth)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicAppointmentController::class, 'create'])->name('home');

// Online Appointment Booking (Guest - No Login Required)
Route::get('/booking', [PublicAppointmentController::class, 'create'])->name('booking.create');
Route::post('/booking', [PublicAppointmentController::class, 'store'])->name('booking.store');

// Secure Public Medical Report Access (Token-based - No Login Required)
Route::get('/report/{token}', [PublicReportController::class, 'show'])->name('public.reports.show');
Route::get('/report/{token}/download', [PublicReportController::class, 'download'])->name('public.reports.download');
Route::get('/report/{token}/pdf', [PublicReportController::class, 'stream'])->name('public.reports.stream');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Requires Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile & Password Settings (All Authenticated Users)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // PDF Reports & WhatsApp Sharing (accessible to all authenticated staff)
    Route::get('/reports/{order}/pdf', [ReportController::class, 'generatePdf'])->name('reports.pdf');
    Route::get('/reports/{order}/download', [ReportController::class, 'download'])->name('reports.download');
    Route::get('/reports/{order}/whatsapp', [ReportController::class, 'whatsappLink'])->name('reports.whatsapp');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Full Access)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->as('admin.')->group(function () {
        // User & Staff Management (CRUD + status toggle)
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('users', UserController::class);

        // Lab Test Catalog Management (CRUD)
        Route::resource('tests', TestController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Receptionist & Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:receptionist,admin')->prefix('reception')->as('reception.')->group(function () {

        // Patient Management (CRUD & Search)
        Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
        Route::resource('patients', PatientController::class);

        // Appointments Management (Confirm / Edit / Cancel)
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');

        // Orders Management
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('/orders/{order}/sample-status', [OrderController::class, 'updateSampleStatus'])->name('orders.update-sample-status');
        Route::patch('/orders/{order}/patient-phone', [OrderController::class, 'updatePatientPhone'])->name('orders.update-patient-phone');

        // Invoices & Payments Management
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::patch('/invoices/{invoice}/pay', [InvoiceController::class, 'markAsPaid'])->name('invoices.pay');
        Route::patch('/invoices/{invoice}/recalculate', [InvoiceController::class, 'recalculate'])->name('invoices.recalculate');
    });

    /*
    |--------------------------------------------------------------------------
    | Technician & Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:technician,admin')->prefix('lab')->as('lab.')->group(function () {
        // Lab Work Queue (Pending Orders & Entering Results)
        Route::get('/orders', [ResultController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}/results', [ResultController::class, 'editOrderResults'])->name('orders.results.edit');
        Route::put('/orders/{order}/results', [ResultController::class, 'updateOrderResults'])->name('orders.results.update');

        Route::get('/reports', fn () => 'Technician/Admin: Generate & View PDF Reports')->name('reports.index');
    });

});
