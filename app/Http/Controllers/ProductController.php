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

    $items = [
        'mode' => 'payment',
        'success_url' => 'http://seu-site.com/success',
        'cancel_url' => 'http://seu-site.com/cancel',
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => ['name' => $product->name],
                'unit_amount' => $price,
            ],
            'quantity' => 1,
        ]],
    ];

    $checkout_session = $stripe->checkout->sessions->create($items);

    return redirect($checkout_session->url);
}



    public function checkout(Request $request)
{
    $cartItems = session()->get('cart', []);
    $items = [
        'mode' => 'payment',
        'success_url' => 'http://seu-site.com/success', // Altere para a URL de sucesso
        'cancel_url' => 'http://seu-site.com/cancel',  // Altere para a URL de cancelamento
    ];

    foreach ($cartItems as $item) {
        $items['line_items'][] = [
            'price_data' => [
                'currency' => 'brl', // Substitua pela sua moeda se necessário
                'product_data' => [
                    'name' => $item['name']
                ],
                'unit_amount' => $item['price'] * 100
            ],
            'quantity' => $item['quantity']
        ];
    }

    $stripe = new StripeClient(env('STRIPE_SECRET'));
    $checkout_session = $stripe->checkout->sessions->create($items);

    return redirect($checkout_session->url);
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
