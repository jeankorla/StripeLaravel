<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);


Route::get('/subscribe', [SubscriptionController::class, 'showSubscription']);
Route::post('/subscribe', [SubscriptionController::class, 'processSubscription']);
Route::get('/welcome', [SubscriptionController::class, 'showWelcome'])->middleware('subscribed');

Route::post('/seller/subscribe', [SubscriptionController::class, 'processSubscription']);


Route::get('/funcionou', [SubscriptionController::class, 'showWelcome'])->middleware('subscribed');



// Rota para listar todos os produtos
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Rota para mostrar um produto específico
Route::get('/products/{productId}', [ProductController::class, 'show'])->name('products.show');

// Rota para iniciar o processo de compra de um produto unitário
Route::post('/products/{productId}/purchase', [ProductController::class, 'purchase'])->name('products.purchase');

// Rota para exibir a página de checkout
Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');

// Rota para processar o pagamento
Route::post('/process-payment', [ProductController::class, 'processPayment'])->name('payment.process');
