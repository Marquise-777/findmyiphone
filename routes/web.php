<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/orders/{order}/print', function (Order $order) {
    return view('invoices.print', compact('order'));
})->name('orders.print');

