<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\AllergeenController;
use App\Http\Controllers\DeliveryOverviewController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/products', [ProductController::class, 'index'])->name('product.index');

Route::get('/product/{id}/allergenenInfo', [ProductController::class, 'allergenenInfo'])->name('product.allergenenInfo');

Route::get('/product/{id}/leverantieInfo', [ProductController::class, 'leverantieInfo'])->name('product.leverantieInfo');

// Suppliers
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/{id}/products', [SupplierController::class, 'products'])->name('suppliers.products');

// Delivery create/store
Route::get('/suppliers/{supplierId}/product/{productId}/delivery', [SupplierController::class, 'createDelivery'])->name('suppliers.delivery');
Route::post('/suppliers/{supplierId}/product/{productId}/delivery', [SupplierController::class, 'storeDelivery'])->name('suppliers.delivery.store');

// Wijzigen Leveranciers
Route::get('/leveranciers', [LeverancierController::class, 'index'])->name('leveranciers.index');
Route::get('/leveranciers/{id}/details', [LeverancierController::class, 'details'])->name('leveranciers.details');
Route::get('/leveranciers/{id}/edit', [LeverancierController::class, 'edit'])->name('leveranciers.edit');
Route::put('/leveranciers/{id}', [LeverancierController::class, 'update'])->name('leveranciers.update');

// Overzicht Allergenen
Route::get('/allergenen', [AllergeenController::class, 'index'])->name('allergeen.index');
Route::get('/allergenen/product/{id}/leverancier', [AllergeenController::class, 'leverancierInfo'])->name('allergeen.leverancier');

// Overzicht producten uit het assortiment (User Story 1)
Route::get('/overzicht-producten-uit-het-assortiment', [DeliveryOverviewController::class, 'index'])->name('delivery.overview');
Route::get('/overzicht-producten-uit-het-assortiment/product/{productId}', [DeliveryOverviewController::class, 'productDetails'])->name('delivery.product-details');
Route::post('/overzicht-producten-uit-het-assortiment/product/{productId}/verwijder', [DeliveryOverviewController::class, 'deleteProduct'])->name('delivery.product-delete');




Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
