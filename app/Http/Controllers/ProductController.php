<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct() {
    $this->middleware('auth');
    }   

    public function index() {
    $stripe = new StripeClient(env('STRIPE_SECRET'));

    // Listar todos os preços
    $prices = $stripe->prices->all(['limit' => 10]);

    // Filtrar preços unitários
    $unitPrices = array_filter($prices->data, function($price) {
        return !isset($price->recurring) || $price->recurring === null;
    });

    // Recuperar detalhes dos produtos associados a preços unitários
    $products = [];
    foreach ($unitPrices as $price) {
        $product = $stripe->products->retrieve($price->product);
        $products[] = [
            'product' => $product,
            'price' => $price
        ];
    }

    return view('products.index', ['products' => $products]);
}


   public function show($productId) {
    $stripe = new StripeClient(env('STRIPE_SECRET'));
    $product = $stripe->products->retrieve($productId);

    // Recuperando os preços para o produto
    $prices = $stripe->prices->all(['product' => $productId]);

    // Suponha que usamos o primeiro preço associado ao produto para simplificar
    $price = $prices->data[0]->unit_amount / 100; // Convertendo de centavos para dólares

    return view('products.show', [
        'product' => $product,
        'price' => $price
    ]);
}

   public function purchase(Request $request, $productId) {
    $stripe = new StripeClient(env('STRIPE_SECRET'));

    // Recuperando o produto e os preços do Stripe
    $product = $stripe->products->retrieve($productId);
    $prices = $stripe->prices->all(['product' => $productId]);
    $price = $prices->data[0]->unit_amount;

    // Obter o usuário autenticado e criar ou recuperar o cliente Stripe
    $user = Auth::user();
    $stripeCustomer = $user->createOrGetStripeCustomer();

    // Criar um PaymentIntent para o produto
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $price,
        'currency' => 'usd', // ou a moeda que você usa
        'customer' => $stripeCustomer->id, // Associar o PaymentIntent ao cliente Stripe
    ]);

    return view('checkout', [
        'clientSecret' => $paymentIntent->client_secret,
        'product' => $product,
        'price' => $price / 100
    ]);
}



    public function checkout(Request $request)
{
    $cartItems = session()->get('cart', []);
    $total = array_reduce($cartItems, function ($carry, $item) {
        return $carry + $item['price'] * $item['quantity'];
    }, 0);

    $stripe = new StripeClient(env('STRIPE_SECRET'));

    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $total * 100, // Stripe espera o valor em centavos
        'currency' => 'usd', // Substitua pela sua moeda se necessário
        // Você pode adicionar mais configurações conforme necessário
    ]);

    return view('checkout', [
        'clientSecret' => $paymentIntent->client_secret,
        'cartItems' => $cartItems,
        'total' => $total
    ]);
}

public function processPayment(Request $request)
{
    $stripe = new StripeClient(env('STRIPE_SECRET'));

    try {
        $stripe->paymentIntents->confirm(
            $request->input('paymentIntentId'),
            ['payment_method' => $request->input('paymentMethodId')]
        );

        // Aqui você pode limpar o carrinho, salvar o pedido no banco de dados, etc.
        session()->forget('cart');

        return redirect()->route('success');
    } catch (\Exception $e) {
        return back()->withErrors(['message' => 'Error processing payment. ' . $e->getMessage()]);
    }
}

}
