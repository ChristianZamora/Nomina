<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ScheduleUser;
use App\Schedule;
use App\Usuario;
use Carbon\Carbon;

class TimeClock extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('vendor.voyager.timeclock.timeclock');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function comprobarComida()
    {

        $ultimoRegistro = $this->ultimoRegistroSchedule();

        $comida = $ultimoRegistro['break'];
        $finComida = $ultimoRegistro['end_break'];

        if($comida != NULL && $finComida === NULL){ //Cuando tiene registro de comida pero no de fin comida

            return 1;

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function guardarComida(Request $request)
    {
        $checarInicioComida = Carbon::now('America/Mexico_City');

        $idUltimoRegistro = $this->ultimoRegistroScheduleUser();

        $checadasUsuario = $this->ultimoRegistroSchedule();

        if ($checadasUsuario['break'] != null) {

            return 0;
        }

        $insertarComida = Schedule::where("id", '=', $idUltimoRegistro)->update([
            'break' => $checarInicioComida,
        ]);

        return 1;
        //
    }

    public function guardarRegresoComida(Request $request)
    {
        $checarRegresoComida = Carbon::now('America/Mexico_City');

        $idUltimoRegistro = $this->ultimoRegistroScheduleUser();

        $checadasUsuario = $this->ultimoRegistroSchedule();

        if($checadasUsuario['end_break'] != NULL) {

            return 0;

        } 

        $insertarRegresoComida = Schedule::where("id", '=', $idUltimoRegistro)
        ->update([
                'end_break' => $checarRegresoComida
            ]);
       
       
        return 1;
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $checadasUsuario = $this->ultimoRegistroSchedule(); //Obtener último registro de la tabla Schedule

        $fecha_actual = $request->input("fecha_actual");
        $hora_actual = $request->input("hora_actual");
        $fechaActualClear = $this->convertirFechasEnArray($fecha_actual);
        $horaActualClear = $this->convertirHorasEnArray($hora_actual);

        $SalidaUsuario = $this->crearFechaCarbon($fechaActualClear[0], $fechaActualClear[1], $fechaActualClear[2], $horaActualClear[0], $horaActualClear[1], $horaActualClear[2]); // Checar salida con fecha y hora actual

        $idUltimoRegistro = $this->ultimoRegistroScheduleUser();

        $horaDeComidaUsuarioClear = $this->horaDeComidaUsuario(); // Obtener hora de comida del usuario

        $horaDeSalidaUsuarioClear = $this->horaDeSalidaUsuario(); // Obtener hora de salida del usuario

        $horaChecadaEntradaUsuario = $this->checadaEntradaUsuario();

        $horaChecadaComidaUsuario = $this->checadaComidaUsuario();

        $horaChecadaRegresoComidaUsuario = $this->checadaRegresoComidaUsuario();

        $recesoMinutos =$horaChecadaComidaUsuario->diffInMinutes($horaChecadaRegresoComidaUsuario);// Tiempo de receso del empleado 

        if ($checadasUsuario['exit'] == null) {//// //Si la última checada de la salida está vacia se podrá guardar la checada

        $tiempoExtra =$SalidaUsuario->diffInMinutes($horaDeSalidaUsuarioClear); //Diferencia en minutos entrela hora de salida establecida para el usuario y su hora de checada en salida 
        
        $difEntradaComida =$horaChecadaComidaUsuario->diffInMinutes($horaChecadaEntradaUsuario); //Diferenciaen minutos entre la hora de entrada  y su hora de checada en comida
         
        $difRegresoComidaSalida =$horaChecadaRegresoComidaUsuario->diffInMinutes($SalidaUsuario); //Diferenciaen  minutos entre la hora de regreso de comida  y su hora de checada en salida
        $tiempo = $difEntradaComida + $difRegresoComidaSalida; //tiempo total laborado durante el día

        $tiempoLaborado = $this->toHours($tiempo);
        $horasExtras = $this->toHours($tiempoExtra);
        $receso = $this->toHours($recesoMinutos);

        $diff =$SalidaUsuario->diffForHumans($horaDeSalidaUsuarioClear); 
        $horas = explode(' ' , $diff);

        $insertarSalida = Schedule::where("id", '=', $idUltimoRegistro)
        ->update([
           'exit' => Carbon::now('America/Mexico_City')
           ]);

           if($horas[2] =! 'before') {    
                 $insertarSalida = Schedule::where("id", '=', $idUltimoRegistro)
                 ->update([
                     'extra_time' => $horasExtras
                     ]);
         }
         
        $insertarTiempoLaborado = Schedule::where("id", '=', $idUltimoRegistro)
        ->update([
                 'time_worked' => $tiempoLaborado,
                 'time_break' => $receso
             ]);
             
 
//**********************************SI EL REGISTRO DE COMIDA SE ENCUENTRA VACIO*********************************          
         if($checadasUsuario['break'] == NULL) {

            $checarHoraComida = $this->crearFechaCarbon($fechaActualClear[0],$fechaActualClear[1],$fechaActualClear   [2], $horaDeComidaUsuarioClear[0],$horaDeComidaUsuarioClear[1],$horaDeComidaUsuarioClear[2]);
            
            $checarHoraFinComida = $this->crearFechaCarbon($fechaActualClear[0],$fechaActualClear[1], $fechaActualClear[2], $horaDeComidaUsuarioClear[0],$horaDeComidaUsuarioClear[1], $horaDeComidaUsuarioClear[2])->addHours(1);
            
            $difEntradaComida =$horaChecadaComidaUsuario->diffInMinutes($horaChecadaEntradaUsuario); //Diferencia     en minutos entre la hora de entrada  y su hora de checada en comida
            
            $difRegresoComidaSalida =$horaChecadaRegresoComidaUsuario->diffInMinutes($SalidaUsuario); //Diferencia    en  minutos entre la hora de regreso de comida  y su hora de checada en salida
 
              $insertarComidas = Schedule::where("id", '=', $idUltimoRegistro)
               ->update([
                       'break' => $checarHoraComida,
                       'end_break' => $checarHoraFinComida
                   ]);

            $tiempoExtra =$SalidaUsuario->diffInMinutes($horaDeSalidaUsuarioClear); //Diferencia eminutos entre la hora de salida establecida para el usuario y su hora de checada en salida  
            $recesoMinutos =$horaChecadaComidaUsuario->diffInMinutes($horaChecadaRegresoComidaUsuario); //Diferencia en  minutos de la comida   
            $tiempo = $difEntradaComida + $difRegresoComidaSalida; //tiempo total laborado durante el día

            $tiempoLaborado = $this->toHours($tiempo);
            $horasExtras = $this->toHours($tiempoExtra);   
            $receso = $this->toHours($recesoMinutos); //Minutos de receso convertidos a horas    

              $insertarDatosExtras = Schedule::where("id", '=', $idUltimoRegistro)
                ->update([
                        'time_worked' => $tiempoLaborado,
                        'extra_time' => $horasExtras,
                        'time_break' => $receso,
                    ]);     

                   return 'guardado';
         }


//*********************************SI EL REGISTRO DE finCOMIDA SE ENCUENTRA VACIO*******************************          
         if($checadasUsuario['break'] != NULL && $checadasUsuario['end_break'] == NULL) {

            $checarHoraComidaMasUno = $this->checadaComidaUsuario()->addHours(1);

            $insertarComida = Schedule::where("id", '=', $idUltimoRegistro)
            ->update([
                    'end_break' => $checarHoraComidaMasUno
                ]);
            
            $difEntradaComida =$horaChecadaComidaUsuario->diffInMinutes($horaChecadaEntradaUsuario); //Diferencia en minutos entre la hora de entrada  y su hora de checada en comida
            
            $difRegresoComidaSalida =$horaChecadaRegresoComidaUsuario->diffInMinutes($SalidaUsuario); //Diferencia en  minutos entre la hora de regreso de comida  y su hora de checada en salida

            $recesoMinutos =$horaChecadaComidaUsuario->diffInMinutes($horaChecadaRegresoComidaUsuario); //Diferencia en  minutos de la comida

            $tiempoExtra =$SalidaUsuario->diffInMinutes($horaDeSalidaUsuarioClear); //Diferencia en minutos entre la hora de salida establecida para el usuario y su hora de checada en salida

            $tiempo = $difEntradaComida + $difRegresoComidaSalida; //tiempo total laborado durante el día

            $receso = $this->toHours($recesoMinutos); //Minutos de receso convertidos a horas
            $tiempoLaborado = $this->toHours($tiempo);
            $horasExtras = $this->toHours($tiempoExtra);
            
              $insertarComida = Schedule::where("id", '=', $idUltimoRegistro)
               ->update([
                       'time_worked' => $tiempoLaborado,
                       'time_break' => $receso,
                       'extra_time' => $horasExtras

                   ]);
            
                   return 'guardado';
         }


         return 'guardado'; 
  } 

       return 0;
}

    public function toHours($min)
        { 
            
            //obtener segundos
            $sec = $min * 60;
            //dias es la division de n segs entre 86400 segundos que representa un dia
            $dias=floor($sec/86400);
            //mod_hora es el sobrante, en horas, de la division de días; 
            $mod_hora=$sec%86400;
            //hora es la division entre el sobrante de horas y 3600 segundos que representa una hora;
            $horas=floor($mod_hora/3600); 
            //mod_minuto es el sobrante, en minutos, de la division de horas; 
            $mod_minuto=$mod_hora%3600;
            //minuto es la division entre el sobrante y 60 segundos que representa un minuto;
            $minutos=floor($mod_minuto/60);

            $text = $horas.' hrs'.' '.$minutos.' min';
          
            return $text; 

    }


    public function convertirHorasEnArray($hora)
    {
        $horas = explode(':', $hora);

        return $horas;
        //
    }

    public function convertirFechasEnArray($fecha)
    {
        $fechas = explode('-', $fecha);

        return $fechas;
        //
    }

    public function usuarioLogeado()
    {
        $idUsuarioLogeado = Auth::user()->id;

        $ultimoRegistroScheUser = ScheduleUser::where("user_id", '=', $idUsuarioLogeado)->get();

        return $idUsuarioLogeado;
        //
    }

    public function ultimoRegistroScheduleUser()
    {

        $idUsuarioLogeado = $this->usuarioLogeado();

        $ultimoRegistroScheUser = ScheduleUser::where("user_id", '=', $idUsuarioLogeado)->get(); //sobtienen el último registro con el id del usuario de la tabla shcedule_users
        $ultimo_registro = $ultimoRegistroScheUser->last();
        $ultimoRegistroArray = $ultimo_registro->toArray();
        $idUltimoRegistro = $ultimoRegistroArray['schedule_id']; //se obtiene el id del último registro

        return $idUltimoRegistro;
    }

    public function ultimoRegistroSchedule()
    {

        $idUltimoRegistro = $this->ultimoRegistroScheduleUser();

        $checadasUsuario = Schedule::where("id", '=', $idUltimoRegistro)->get(); //se obtiene las checadas del usuario
        $ultimoRegistroChecadas = $checadasUsuario->last();
        $ultimoRegistroArrayChecadas = $ultimoRegistroChecadas->toArray();

        return $ultimoRegistroArrayChecadas;
    }

    public function horaDeComidaUsuario()
    {

        $idUsuarioLogeado = $this->usuarioLogeado();

        $horasEstablecidasDeUsuario = Usuario::where("id", '=', $idUsuarioLogeado)->get();
        $horasEstablecidasDeUsuarioArray = $horasEstablecidasDeUsuario->toArray();
        $horaDeComidaUsuario = $horasEstablecidasDeUsuario[0]['hora_receso'];

        $arrEat = explode(' ', $horaDeComidaUsuario);
        $arrEatOne = explode('-', $arrEat[0]);
        $arrEatTwo = explode(':', $arrEat[1]);

        return $arrEatTwo;
    }

    public function horaDeSalidaUsuario()
    {
        $idUsuarioLogeado = $this->usuarioLogeado();
        
        $horasEstablecidasDeUsuario= Usuario::where("id", '=', $idUsuarioLogeado)->get();
        $horaDeSalidaUsuario = $horasEstablecidasDeUsuario[0]['hora_salida'];
        
        $fechaActual = date("Y-m-d");
        $fechaActualAno = explode('-' , $fechaActual);
     
        $array1 = explode(' ' , $horaDeSalidaUsuario);
        $array2 = explode('-' , $array1[0]);
        $array3 = explode(':' , $array1[1]);

        $checarHoraSalida = $this->crearFechaCarbon($fechaActualAno[0], $fechaActualAno[1], $fechaActualAno[2], $array3[0], $array3[1], $array3[2]);

        return $checarHoraSalida;
        //
    }

    public function checadaEntradaUsuario()
    {

        $idUltimoRegistro = $this->ultimoRegistroSchedule();
        $horaEntrada = $idUltimoRegistro['entry'];
        $horaEntradaArray = explode(' ', $horaEntrada);
        $fechaArray = explode('-' , $horaEntradaArray[0]);
        $horaArray = explode(':' , $horaEntradaArray[1]);

        $checarHoraEntrada = $this->crearFechaCarbon($fechaArray[0], $fechaArray[1], $fechaArray[2], $horaArray[0], $horaArray[1], $horaArray[2]);

        return $checarHoraEntrada;
        //
    }

    public function checadaComidaUsuario()
    {

        $idUltimoRegistro = $this->ultimoRegistroSchedule();
        $horaComida = $idUltimoRegistro['break'];
        $horaComidaArray = explode(' ', $horaComida);
        $fechaArray = explode('-' , $horaComidaArray[0]);
        $horaArray = explode(':' , $horaComidaArray[1]);

        $checarComidaEntrada = $this->crearFechaCarbon($fechaArray[0], $fechaArray[1], $fechaArray[2], $horaArray[0], $horaArray[1], $horaArray[2]);

        return $checarComidaEntrada;
        //
    } 

    public function checadaRegresoComidaUsuario()
    {

        $idUltimoRegistro = $this->ultimoRegistroSchedule();
        $horaRegresoComida = $idUltimoRegistro['end_break'];
        $horaRegresoComidaArray = explode(' ', $horaRegresoComida);
        $fechaArray = explode('-' , $horaRegresoComidaArray[0]);
        $horaArray = explode(':' , $horaRegresoComidaArray[1]);

        $checarRegresoComidaEntrada = $this->crearFechaCarbon($fechaArray[0], $fechaArray[1], $fechaArray[2], $horaArray[0], $horaArray[1], $horaArray[2]);

        return $checarRegresoComidaEntrada;
        
    }

    public function crearFechaCarbon($año, $mes, $dia, $hora, $minuto, $segundo)
    {

        return Carbon::create($año, $mes, $dia, $hora, $minuto, $segundo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
