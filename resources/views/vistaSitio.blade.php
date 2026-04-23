<h1> Sitio json </h1>

@foreach($traductorsitio as $trad)
<div style="border:3px solid; padding:12px; margin-bottom:12px;">
	<h3>{{ $trad['nombre'] }}</h3>

	<a href="{{ route('sitio.detalle', $trad['id']) }}">
		<button>
			Ver detalles
		</button>
	</a>
</div>
@endforeach