<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (!isset($cart['products'])) {
            $cart['products'] = [];
            $cart['total'] = 0;
        }

        $inCart = false;
        foreach ($cart['products'] as $index => $productInCart) {
            if ($productInCart['id'] === $product->id) {
                $cart['products'][$index]['quantity']++;
                $cart['total'] += $product->price;
                $inCart = true;
                break;
            }
        }

        if (!$inCart) {
            $cart['products'][] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1
            ];
            $cart['total'] += $product->price;
        }

        session()->put('cart', $cart);
        return redirect()->route('products.index');
    }

    public function remove(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart['products'])) {
            foreach ($cart['products'] as $index => $productInCart) {
                if ($productInCart['id'] === $productId) {
                    $cart['total'] -= $productInCart['price'] * $productInCart['quantity'];
                    unset($cart['products'][$index]);
                    break;
                }
            }
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.show');
    }

    public function show(Request $request)
    {
        $cartItems = session()->get('cart.products', []);
        $total = session()->get('cart.total', 0);

        return view('cart.show', compact('cartItems', 'total'));
    }
}
