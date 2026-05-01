<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Filament\Pages\Invoice;

Route::get('/admin/invoice/{orderId}', Invoice::class);

Route::get('/', function () {
    return view('welcome');
});



Route::get('/orders/{order}/print', function (Order $order) {
    return view('invoices.print', compact('order'));
})->name('orders.print');

Route::get('/orders/{order}/invoice', function (Order $order) {
    return view('filament.pages.invoice', ['order' => $order]);
})->name('orders.invoice');
