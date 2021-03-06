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
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet"href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}" />
@include ('sweet::alert')
{{ URL::asset('css/stylesweetAlert.css') }}
@section('content')

    <div class="col-lg-6" id="inicioReceso">
        <div class="container">
            <img src="{{ voyager_asset('images/widget-backgrounds/checarComida.jpg') }}" class="image" alt="Responsive image">
            <div class="overlay">
                <a class="text" data-toggle="modal" href="#checarComida">Iniciar Receso</a>
            </div>
        </div>
    </div>

    <div class="col-lg-6" id="finReceso" style="display: none;">
        <div class="container">
            <img src="{{ voyager_asset('images/widget-backgrounds/checarRegresoComida.jpg') }}" class="image" alt="Responsive image">
            <div class="overlay">
                <a class="text" data-toggle="modal" href="#checarRegresoComida">Finalizar Receso</a>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="container">
            <img src="{{ voyager_asset('images/widget-backgrounds/checarSalida.jpg') }}" class="image" alt="Responsive image">
            <div class="overlay">
                <a class="text" data-toggle="modal" href="#checarSalida">Checar Salida</a>
            </div>
        </div>
    </div>
   
    <div class="col-lg-12">
    
        <div class="container">
             <br><br>
            <h1>Tómalo en Cuenta</h1>
            <br>
            <b>
                <p>
                    <li>Por llegar temprano todos los días de la catorcena, entre 8:46 y las 09:00, obtendrás el bono de puntualidad por el 10% de tu salario base.</li><br>
                    <li>Por llegar temprano todos los días de la catorcena 8:45 o antes, obtendrás el bono de extrapuntualidad por el 10% de tu salario base.</li>
                    <br>
                    <li>En caso de inasistencia el día sábado también se descontará como día completo.</li>
                    <br>
                    <li>El día sábado, solo se tiene tolerancia de llegar como máximo 30 min después de 8:45.</li>
                    <br>
                    <li>La hora extra se paga después del minuto 50 de la hora adicional trabajada.</li>
                    <br>
                    <li>Con un permiso autorizado para faltar a la catorcena, perderás el derecho de obtener tu bono de extrapuntualidad.</li>
                </p>
            </b>
        </div>
    </div>

<div class="modal fade" id="checarRegresoComida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Estás a punto de registrar tu final de receso</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div align="center">
            <h4 class="modal-title" id="exampleModalLabel">¿Estás seguro?</h4></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" onclick="guardarRegresoComida()" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="checarComida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Estás a punto de checar tu hora de comida</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div align="center">
            <h4 class="modal-title" id="exampleModalLabel">¿Estás seguro?</h4></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" onclick="guardarComida()" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="checarSalida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Estás a punto de checar tu hora de salida</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <div align="center">
            <h4 class="modal-title" id="exampleModalLabel">¿Estás seguro?</h4></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" onclick="guardarSalida()" class="btn btn-primary">Si</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

$(document).ready(function() {

  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
               type:  'post',
               url:   '{{ route('status_comida') }}',
               success:  function (response) {

                 if(response == 1){

                  $('#finReceso').show();
                  $('#inicioReceso').hide();

                 }

               }
       });
});


function guardarComida()
{


   $.ajax({
               type:  'post',
               url:   '{{ route('checar_comida') }}',
               success:  function (response) {

                 //alert(response);

                if(response == 1){
                  $('#checarComida').modal('hide');
                  
                  swal({
                     title: "Buen provecho",
                     text: "No olvides registrar tu regreso.",
                     type: "success",
                     timer: 2000
                     })

                  $('#inicioReceso').hide();
                  $('#finReceso').show();

                } if(response == 0){ 
                  $('#checarComida').modal('hide');
                  swal({
                    title: "Ya has registrado tu receso anteriormente",
                    timer: 2000
                    })
  
                }
               }
       });
}

function guardarRegresoComida()
{


   $.ajax({
               type:  'post',
               url:   '{{ route('checar_regresoComida') }}',
               success:  function (response) {

                 //alert(response);

                if(response == 1){
                  $('#checarRegresoComida').modal('hide');
                  
                  swal({
                     title: "Que hayas disfrutado de tus alimentos",
                     type: "success",
                     timer: 2000
                     })
                  $('#finReceso').hide();
                  $('#inicioReceso').show();
                  

                } if(response == 0){ 
                  $('#checarRegresoComida').modal('hide');
                  swal({
                    title: "Ya has registrado tu receso anteriormente",
                    timer: 2000
                    })
  
                }
               }
       });
}

function guardarSalida()
{

var d = new Date();
var año = d.getFullYear();
var mes = d.getMonth()+1;
var dia = d.getDate().toString();
var hora = d.getHours();
var minuto = d.getMinutes();
var segundo = d.getSeconds();

var fecha_actual= año + "-" + mes + "-" + dia;
var hora_actual= hora + ":" + minuto + ":" + segundo;

   $.ajax({
               type:  'post',
               url:   '{{ route('checar_salida') }}',
               data:  {fecha_actual:fecha_actual , hora_actual:hora_actual},
               success:  function (response) {

                 //alert(response);

              if(response == 'guardado'){
                $('#checarSalida').modal('hide');
                
                swal({
                   title: "Que tengas un excelente día.",
                   text: "No olvides cerrar tu sesión",
                   type: "success",
                   timer: 2000
                   })
              } if(response == false){ 
                $('#checarSalida').modal('hide');
                swal({
                  title: "Ya has registrado tu salida anteriormente",
                  timer: 2000
                  })
  
              }
               }
       });
}

</script>

@endsection