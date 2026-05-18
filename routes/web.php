<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\VendorMenuController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerController;

Auth::routes();

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/otp', function () {
    return view('auth.otp');
});
Route::post('/otp', [App\Http\Controllers\Auth\LoginController::class, 'verifyOtp']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');
    // Week 8: harus SEBELUM resource agar tidak tertimpa /barang/{id}
    Route::get('/barang/scanner', [BarangController::class, 'scanner'])->name('barang.scanner');
    Route::get('/barang/find/{id}', [BarangController::class, 'findById'])->name('barang.find');
    Route::resource('barang', BarangController::class);

    Route::get('/pdf-sertifikat', [PdfController::class, 'sertifikat']);
    Route::get('/pdf-undangan', [PdfController::class, 'undangan']);

    // Week 4 - JavaScript & jQuery
    Route::get('/table-biasa', function () {
        return view('tugas2.table-biasa');
    })->name('table-biasa');

    Route::get('/datatables', function () {
        return view('tugas2.datatables');
    })->name('datatables');

    // Week 4 - Select & Select2
    Route::get('/select', function () {
        return view('tugas4.select');
    })->name('select');

    // Week 5 - AJAX
    Route::get('/wilayah-jquery', function () {
        return view('tugas-ajax.wilayah-jquery');
    })->name('wilayah-jquery');

    Route::get('/wilayah-axios', function () {
        return view('tugas-ajax.wilayah-axios');
    })->name('wilayah-axios');

    // Week 7 - Barcode, QR Code & Kamera
    Route::prefix('customer-manage')->name('customer-manage.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create1', [CustomerController::class, 'create1'])->name('create1');
        Route::post('/store1', [CustomerController::class, 'store1'])->name('store1');
        Route::get('/create2', [CustomerController::class, 'create2'])->name('create2');
        Route::post('/store2', [CustomerController::class, 'store2'])->name('store2');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    });
});

// Week 6 - Payment Gateway

// Midtrans Webhook (no auth, no CSRF)
Route::post('/midtrans/notification', [CustomerOrderController::class, 'notification'])->name('midtrans.notification');

// Customer (no auth)
Route::prefix('order')->group(function () {
    Route::get('/', [CustomerOrderController::class, 'index'])->name('customer.order');
    Route::get('/menus/{vendorId}', [CustomerOrderController::class, 'getMenusByVendor'])->name('customer.menus');
    Route::get('/menu-detail/{menuId}', [CustomerOrderController::class, 'getMenuDetail'])->name('customer.menu.detail');
    Route::post('/checkout', [CustomerOrderController::class, 'checkout'])->name('customer.checkout');
    Route::get('/payment-success/{kodePesanan}', [CustomerOrderController::class, 'paymentSuccess'])->name('customer.payment.success');
    Route::get('/check-status/{kodePesanan}', [CustomerOrderController::class, 'checkStatus'])->name('customer.check.status');
    Route::post('/simulate-pay/{kodePesanan}', [CustomerOrderController::class, 'simulatePay'])->name('customer.simulate.pay');
});

// Vendor
Route::prefix('vendor')->group(function () {
    Route::get('/login', [VendorAuthController::class, 'showLogin'])->name('vendor.login');
    Route::post('/login', [VendorAuthController::class, 'login'])->name('vendor.login.post');
    Route::post('/logout', [VendorAuthController::class, 'logout'])->name('vendor.logout');

    Route::middleware('auth:vendor')->group(function () {
        Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
        Route::get('/pesanan-lunas', [VendorDashboardController::class, 'pesananLunas'])->name('vendor.pesanan.lunas');
        Route::resource('/menu', VendorMenuController::class)->names('vendor.menu');

        // Week 8 - QR Code Scanner
        Route::get('/scan-qr', [VendorDashboardController::class, 'scanQr'])->name('vendor.scan.qr');
        Route::post('/scan-qr/find', [VendorDashboardController::class, 'findByQr'])->name('vendor.scan.qr.find');
    });
});
