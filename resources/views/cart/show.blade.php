{{-- resources/views/cart/show.blade.php --}}

@extends('master')

@section('content')
<div class="container">
    <h1>Carrinho de Compras</h1>

    <ul>
        @forelse ($cartItems as $id => $item)
            <li>
                {{ $item['name'] }}
                <input type="text" value="{{ $item['quantity'] }}">
                Price: R$ {{ number_format($item['price'], 2, ',', '.') }}
                Subtotal: R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                <a href="{{ route('remove.from.cart', $id) }}">remove</a>
            </li>
        @empty
            <li>Nenhum produto no carrinho</li>
        @endforelse
    </ul>

    <div>Total: R$ {{ number_format($total, 2, ',', '.') }}</div>

    <hr>

    <a href="{{ route('checkout') }}">Checkout</a>
</div>
@endsection
