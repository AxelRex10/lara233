<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
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
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Listado de categorías</h3>
        <div class="box-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCrearCategoria">
                <i class="fa fa-plus"></i> Nueva categoría
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
                <select id="filtroNombre" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach(collect($categorias)->pluck('name')->unique()->sort()->values() as $nombreCategoria)
                        <option value="{{ strtolower($nombreCategoria) }}">{{ $nombreCategoria }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Descripción</label>
                <input type="text" id="filtroDesc" class="form-control form-control-sm" placeholder="Filtrar por descripción...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-secondary btn-sm" onclick="limpiarFiltros()">Limpiar</button>
            </div>
        </div>

        {{-- Tabla --}}
        <table class="table table-bordered table-hover" id="tablaCategorias">
            <thead class="thead-light">
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="width:140px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $cat)
                <tr
                    data-nombre="{{ strtolower($cat->name) }}"
                    data-desc="{{ strtolower($cat->description) }}"
                >
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->description }}</td>
                    <td>
                        <button class="btn btn-warning btn-xs"
                            data-toggle="modal" data-target="#modalEditarCategoria"
                            onclick="cargarEditar({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description) }}')">
                            <i class="fa fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-xs"
                            data-toggle="modal" data-target="#modalEliminarCategoria"
                            onclick="cargarEliminar({{ $cat->id }}, '{{ addslashes($cat->name) }}')">
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
<div class="modal fade" id="modalCrearCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva categoría</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ url('/categorias') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" class="form-control solo-texto" required placeholder="Nombre de la categoría" pattern="[^0-9]*" title="No se permiten numeros">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control solo-texto" rows="3" placeholder="Descripción de la categoría"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ===================== MODAL EDITAR ===================== --}}
<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar categoría</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditar" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="name" id="editNombre" class="form-control solo-texto" required pattern="[^0-9]*" title="No se permiten numeros">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="description" id="editDesc" class="form-control solo-texto" rows="3"></textarea>
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
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-white">Confirmar eliminación</h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEliminar" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <p>¿Eliminar la categoría <strong id="eliminarNombre"></strong>?</p>
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
function cargarEditar(id, nombre, desc) {
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editDesc').value = desc;
    document.getElementById('formEditar').action = '/categorias/' + id;
}

function cargarEliminar(id, nombre) {
    document.getElementById('eliminarNombre').textContent = nombre;
    document.getElementById('formEliminar').action = '/categorias/' + id;
}

function limpiarFiltros() {
    document.getElementById('filtroNombre').value = '';
    document.getElementById('filtroDesc').value = '';
    filtrar();
}

document.getElementById('filtroDesc').addEventListener('input', filtrar);
document.getElementById('filtroNombre').addEventListener('change', filtrar);

function filtrar() {
    const nombre = document.getElementById('filtroNombre').value.toLowerCase();
    const desc   = document.getElementById('filtroDesc').value.toLowerCase();
    document.querySelectorAll('#tablaCategorias tbody tr').forEach(tr => {
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