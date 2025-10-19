<?php

// Facades
use App\Livewire\EventList;
use App\Livewire\MyTickets;

// Livewire Components
use App\Livewire\CreateOrder;
use App\Livewire\EventDetail;
use App\Livewire\PaymentPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MidtransController;

// Midtrans
// Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler'])->name('midtrans.notification');

// Halaman utama (daftar event) yang bisa diakses publik.
Route::get('/', EventList::class)->name('events.index');



// Grup rute yang hanya bisa diakses oleh pengguna yang sudah login.
Route::middleware('auth')->group(function () {
    // Halaman form untuk memesan tiket event.
    Route::get('/events/{event}/order', CreateOrder::class)->name('orders.create');

    // Add this new route for the detail page
    Route::get('/events/{event:slug}', EventDetail::class)->name('events.show');

    // Halaman untuk proses pembayaran tiket.
    Route::get('/orders/{order}/payment', PaymentPage::class)->name('orders.payment');

    // Tambahkan rute ini untuk menampilkan e-ticket
    Route::get('/tickets/{order}/view', [TicketController::class, 'show'])->name('tickets.show');

    // Di dalam grup middleware('auth')
    Route::get('/my-tickets', MyTickets::class)->name('my-tickets.index');

    // Halaman profil pengguna.
    Route::view('profile', 'profile')->name('profile')->middleware('auth');

    // Proses untuk logout pengguna.
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

// Memuat rute-rute autentikasi bawaan (login, register, dll.).
require __DIR__ . '/auth.php';
