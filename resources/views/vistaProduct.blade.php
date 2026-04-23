<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .wrapper {
            max-width: 1150px;
            margin: 18px auto;
            padding: 0 10px;
        }

        .content {
            padding: 0;
        }

        .box {
            margin-bottom: 10px;
        }

        .box-header {
            padding: 10px 12px;
        }

        .box-body {
            padding: 12px;
        }

        .table th,
        .table td {
            padding: .45rem;
            font-size: 13px;
            vertical-align: middle;
        }

        .btn-xs {
            margin-bottom: 3px;
        }
    </style>
</head>
<body class="hold-transition skin-blue">
<div class="wrapper">
<section class="content">
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Listado de productos</h3>
        <div class="box-tools">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCrearProducto">
                <i class="fa fa-plus"></i> Nuevo producto
            </button>
        </div>
    </div>
    <div class="box-body">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Filtros --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Nombre</label>
                <select id="filtroNombreProd" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach(collect($productos)->pluck('name')->unique()->sort()->values() as $nombreProducto)
                        <option value="{{ strtolower($nombreProducto) }}">{{ $nombreProducto }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Descripción</label>
                <input type="text" id="filtroDescProd" class="form-control form-control-sm" placeholder="Filtrar por descripción...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-secondary btn-sm" onclick="limpiarFiltrosProd()">Limpiar</button>
            </div>
        </div>

        {{-- Tabla --}}
        <table class="table table-bordered table-hover" id="tablaProductos">
            <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="width:90px">Precio</th>
                    <th style="width:120px">Categoría</th>
                    <th style="width:140px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $prod)
                <tr
                    data-nombre="{{ strtolower($prod->name) }}"
                    data-desc="{{ strtolower($prod->description) }}"
                >
                    <td>{{ $prod->name }}</td>
                    <td>{{ $prod->description }}</td>
                    <td>${{ number_format($prod->price, 2) }}</td>
                    <td>{{ $prod->category->name ?? '—' }}</td>
                    <td>
                        <button class="btn btn-warning btn-xs"
                            data-toggle="modal" data-target="#modalEditarProducto"
                            onclick="cargarEditarProd(
                                {{ $prod->id }},
                                '{{ addslashes($prod->name) }}',
                                '{{ addslashes($prod->description) }}',
                                '{{ addslashes($prod->descriptionLong) }}',
                                {{ $prod->price }},
                                {{ $prod->idcategory }}
                            )">
                            <i class="fa fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-xs"
                            data-toggle="modal" data-target="#modalEliminarProducto"
                            onclick="cargarEliminarProd({{ $prod->id }}, '{{ addslashes($prod->name) }}')">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
</section>


{{-- ===================== MODAL CREAR ===================== --}}
<div class="modal fade" id="modalCrearProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo producto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ url('/productos') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="name" class="form-control solo-texto" required placeholder="Nombre del producto" pattern="[^0-9]*" title="No se permiten numeros">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Precio</label>
                                <input type="number" name="price" class="form-control" required step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select name="idcategory" class="form-control" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción corta</label>
                        <input type="text" name="description" class="form-control solo-texto" placeholder="Descripción breve" pattern="[^0-9]*" title="No se permiten numeros">
                    </div>
                    <div class="form-group">
                        <label>Descripción larga</label>
                        <textarea name="descriptionLong" class="form-control solo-texto" rows="3" placeholder="Descripción detallada"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ===================== MODAL EDITAR ===================== --}}
<div class="modal fade" id="modalEditarProducto" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar producto</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditarProd" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="name" id="epNombre" class="form-control solo-texto" required pattern="[^0-9]*" title="No se permiten numeros">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Precio</label>
                                <input type="number" name="price" id="epPrecio" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoría</label>
                                <select name="idcategory" id="epCategoria" class="form-control" required>
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción corta</label>
                        <input type="text" name="description" id="epDesc" class="form-control solo-texto" pattern="[^0-9]*" title="No se permiten numeros">
                    </div>
                    <div class="form-group">
                        <label>Descripción larga</label>
                        <textarea name="descriptionLong" id="epDescLong" class="form-control solo-texto" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ===================== MODAL ELIMINAR ===================== --}}
<div class="modal fade" id="modalEliminarProducto" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-white">Confirmar eliminación</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEliminarProd" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <p>¿Eliminar el producto <strong id="eliminarNombreProd"></strong>?</p>
                    <p class="text-muted" style="font-size:12px">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
function cargarEditarProd(id, nombre, desc, descLong, precio, idcat) {
    document.getElementById('epNombre').value   = nombre;
    document.getElementById('epDesc').value     = desc;
    document.getElementById('epDescLong').value = descLong;
    document.getElementById('epPrecio').value   = precio;
    document.getElementById('epCategoria').value = idcat;
    document.getElementById('formEditarProd').action = '/productos/' + id;
}

function cargarEliminarProd(id, nombre) {
    document.getElementById('eliminarNombreProd').textContent = nombre;
    document.getElementById('formEliminarProd').action = '/productos/' + id;
}

function limpiarFiltrosProd() {
    document.getElementById('filtroNombreProd').value = '';
    document.getElementById('filtroDescProd').value = '';
    filtrarProd();
}

document.getElementById('filtroDescProd').addEventListener('input', filtrarProd);
document.getElementById('filtroNombreProd').addEventListener('change', filtrarProd);

function filtrarProd() {
    const nombre = document.getElementById('filtroNombreProd').value.toLowerCase();
    const desc   = document.getElementById('filtroDescProd').value.toLowerCase();
    document.querySelectorAll('#tablaProductos tbody tr').forEach(tr => {
        const matchNombre = !nombre || tr.dataset.nombre === nombre;
        const matchDesc   = tr.dataset.desc.includes(desc);
        tr.style.display  = (matchNombre && matchDesc) ? '' : 'none';
    });
}

document.querySelectorAll('.solo-texto').forEach(function (campo) {
    campo.addEventListener('input', function () {
        this.value = this.value.replace(/\d+/g, '');
    });
});
</script>
</body>
</html>