<h1>Detalle de hamburguesa</h1>

<div style="border:3px solid; padding:12px; max-width:700px;">
    <p><strong>ID:</strong> {{ $detalle['id'] }}</p>
    <p><strong>Nombre:</strong> {{ $detalle['nombre'] }}</p>
    <p><strong>Descripcion:</strong> {{ $detalle['descripcion'] }}</p>
    <p><strong>Precio:</strong> ${{ number_format($detalle['precio'], 2) }}</p>

    <p><strong>Ingredientes:</strong></p>
    <ul>
        @foreach($detalle['ingredientes'] as $ingrediente)
            <li>{{ $ingrediente }}</li>
        @endforeach
    </ul>

    <p><strong>Categoria:</strong> {{ $detalle['categoria'] }}</p>
    <p><strong>Disponible:</strong> {{ $detalle['disponible'] ? 'Si' : 'No' }}</p>
</div>

<p>
    <a href="{{ url('/sitio') }}">Volver al listado</a>
</p>
