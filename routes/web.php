<?php

// Facades
use App\Livewire\EventList;
use App\Livewire\CreateOrder;

// Livewire Components
use App\Livewire\PaymentPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

// Midtrans
// Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');

// Halaman utama (daftar event) yang bisa diakses publik.
Route::get('/', EventList::class)->name('events.index');

// Grup rute yang hanya bisa diakses oleh pengguna yang sudah login.
Route::middleware('auth')->group(function () {
    // Halaman form untuk memesan tiket event.
    Route::get('/events/{event}/order', CreateOrder::class)->name('orders.create');

    // Halaman untuk proses pembayaran tiket.
    Route::get('/orders/{order}/payment', PaymentPage::class)->name('orders.payment');

    // Halaman profil pengguna.
    Route::view('profile', 'profile')->name('profile');

    // Proses untuk logout pengguna.
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

// Memuat rute-rute autentikasi bawaan (login, register, dll.).
require __DIR__ . '/auth.php';
