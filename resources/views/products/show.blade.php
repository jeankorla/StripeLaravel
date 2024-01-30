<div>
    <h2>{{ $product->name }}</h2>
    <p>Preço: R${{ number_format($price, 2) }}</p>
    <p>Descrição: {{ $product->description }}</p>

    <form action="{{ route('products.purchase', ['productId' => $product->id]) }}" method="post">
        @csrf
        <button type="submit">Comprar</button>
    </form>
</div>
