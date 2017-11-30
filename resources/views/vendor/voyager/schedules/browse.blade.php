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

<!-- Bootstrap CSS -->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet"href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="fa fa-clock-o"></i> HORARIOS DE {{ $nombreUsuarioMayuscula, $descripcionAdmin }}
        </h1>
    </div>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
<div class="container">
    <div class="row">

    <div id="horariosGenerales" class="col-lg-12">
            <div class="container">
                <img src="{{ voyager_asset('images/widget-backgrounds/reloj.jpg') }}" class="image" alt="Responsive image">
                <div class="overlay">
                    <a class="text" data-toggle="modal" href="#verHorarios">Ver Horarios</a>
                </div>
            </div>
        </div>

    </div>
    {{ $descripcionAdmin }}

</div>

<!-- MODAL -->
<div class="modal fade" id="verHorarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Checadas de Empleados</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div align="center">
            <table id="table" class="display datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nombre <br><i class="fa fa-user-o"></i></th> 
                                <th>Entrada <br><i class="fa fa-share"></i></th> 
                                <th>Comida <br><i class="fa fa-spoon"></i></th>
                                <th>Fin Comida <br><i class="fa fa-reply"></i> <i class="fa fa-spoon"></i></th>
                                <th>Salida <br><i class="fa fa-reply"></i></th>
                                <th>Extra <br><i class="fa fa-plus"></i></th>
                                <th>Trabajado <br><i class="fa fa-check"></i></th>
                                <th>Receso <br><i class="fa fa-spinner"></i></th>
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

<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


<!-- JAVASCRIPT -->
<script type="text/javascript">

$(document).ready(function() {

     $('#table').dataTable({
        processing: true,
        serverSide: true,
        paging: false,
        stateSave: true,
        destroy: true,
        ajax: '{{ route('datatable/getdata') }}',
        columns: [
            {data: 'name'},
            {data: 'entry'},
            {data: 'break'},
            {data: 'end_break'},
            {data: 'exit'},
            {data: 'extra_time'},
            {data: 'time_worked'},
            {data: 'time_break'}
            
        ]
    });

    $('.datatable').dataTable().fnDestroy();

    });

</script>


@endsection





