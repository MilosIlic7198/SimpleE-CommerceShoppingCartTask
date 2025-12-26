The following products are low on stock:

@foreach($products as $product)
{{ $product->name }} — {{ $product->stock_quantity }} left
@endforeach

