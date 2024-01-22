<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function checkout()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $cart = Session::get('cart', []);

        $lineItems = [];
        foreach ($cart['products'] ?? [] as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // O preço deve estar em centavos
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $checkoutSession = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('checkout.success'), // URL de sucesso
            'cancel_url' => route('checkout.cancel'), // URL de cancelamento
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
    {
         return view('checkout.success'); // Retorne uma view de sucesso.
    }
    public function cancel(Request $request)
    {
            return view('checkout.cancel'); // Retorne uma view de cancelamento.
    }
}
