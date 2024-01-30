<h1>Produtos Unitários</h1>
<ul>
    @foreach ($products as $item)
        <li>
            <a href="{{ route('products.show', ['productId' => $item['product']->id]) }}">
                {{ $item['product']->name }} - ${{ number_format($item['price']->unit_amount / 100, 2) }}
            </a>
        </li>
    @endforeach
</ul>
