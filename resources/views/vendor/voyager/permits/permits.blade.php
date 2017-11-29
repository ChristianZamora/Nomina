
<style>
.container {
  position: relative;
  width: 50%;
}

.image {
  display: block;
  width: 100%;
  height: auto;
  position:relative;
}

.overlay {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  transition: .5s ease;
  background-color: #008CBA;
}

.container:hover .overlay {
  opacity: 1;
}

.text {
  color: white;
  font-size: 20px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
}

a.text:hover {
    color:#ffcc00;
    font-size:250%;
} 

</style>

@extends('voyager::master')
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
<!-- Bootstrap CSS -->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
<link rel="stylesheet"href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="fa fa-clock-o"></i> PERMISOS DE {{ $nombreUsuarioMayuscula, $descripcionAdmin }}
        </h1>
            <!--
            <a href="" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>Nuevo</span>
            </a>
            -->
    </div>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
<div class="container">
<button type="button" class="btn btn-info" data-toggle="modal" href="#permisoNuevo">Permiso Nuevo</button>
</div> <br>
<div class="container">
    <div class="row">
    <div id="horariosGenerales" class="col-lg-12">
            <div class="container">
                <img src="{{ voyager_asset('images/widget-backgrounds/verPermisos.jpg') }}" class="image" alt="Responsive image">
                <div class="overlay">
                    <a class="text" data-toggle="modal" href="#verPermisos">Ver Permisos</a>
                </div>
            </div>
        </div>
    </div>
    {{ $descripcionAdmin }}
</div>

<!-- MODAL -->
<div class="modal fade" id="verPermisos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Permisos de Empleados</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div align="center">
            <table id="id_table" class="table table-hover table-bordered table-striped datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nombre <i class="fa fa-user-o"></i></th> 
                            <th>Permiso <i class="fa fa-file-o"></i></th>
                            <th>Día <i class="fa fa-calendar"></i></th> 
                          
                        </tr>
                    </thead>
            </table>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js"></script>
<link rel="stylesheet"href="//cdn.datatables.net/plug-ins/1.10.16/api/fnReloadAjax.js">
@include ('sweet::alert')

<!-- Modal -->
<div class="modal fade" id="permisoNuevo" tabindex="-1" role="dialog" 
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    Generar un nuevo permiso
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form action="{{ route('createPermits') }}" class="form-horizontal" role="form" method="post">
                {!! csrf_field() !!}
                  <div class="form-group">
                    <label class="col-sm-2 control-label"
                              for="inputEmail3">Motivo</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" 
                         name="nombre" placeholder="Escribe el motivo de tu permiso"/>
                         <input type="hidden" class="form-control" 
                         name="inputIdUsuario" value="{{ $idUsuario }}" />
                    </div>
                  </div>
                

            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancelar
                </button>
                
                <button type="submit" class="btn btn-primary">
                    Guardar Permiso
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function() {

     $('#id_table').dataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('permits') }}',
        columns: [
            {data: 'name', name: 'name'},
            {data: 'nombre', name: 'nombre'},
            {data: 'created_at', name: 'created_at'},
        ]
    });

    $('#id_table').dataTable().fnDestroy();

    });

</script>

@endsection




