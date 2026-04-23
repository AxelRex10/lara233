<h1>Categorias y productos</h1>

@foreach ($categorias as $categoria)
    <h2>{{ $categoria->name }} ({{ $categoria->products->count() }} productos)</h2>

    <ul>
        @foreach ($categoria->products as $producto)
            <li>{{ $producto->name }} - ${{ number_format($producto->price, 2) }}</li>
        @endforeach
    </ul>
@endforeach