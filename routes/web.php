<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\StripeController;

// Rota para listar os produtos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Rota para adicionar um produto ao carrinho
Route::get('/add-to-cart/{product}', [CartController::class, 'add'])->name('add.to.cart');

// Rota para remover um produto do carrinho
Route::get('/remove-from-cart/{product}', [CartController::class, 'remove'])->name('remove.from.cart');

// Rota para mostrar o carrinho
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');

Route::get('/checkout', [StripeController::class, 'checkout'])->name('checkout');

Route::get('/checkout/success', [StripeController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'cancel'])->name('checkout.cancel');
