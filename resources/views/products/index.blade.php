<!-- resources/views/products/index.blade.php -->

@extends('master')

@section('content')
<div class="container">
    <h1>Produtos</h1>
    <ul>
        @foreach ($products as $product)
        <li>
            {{ $product->name }} - R$ {{ $product->price }}
            <a href="{{ route('add.to.cart', $product->id) }}">Adicionar ao Carrinho</a>
        </li>
        @endforeach
    </ul>
</div>
@endsection
