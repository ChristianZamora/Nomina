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

        $horaDeComidaUsuarioClear = $this->horaDeComidaUsuario(); // Obtener hora de comida del usuario

        $horaDeSalidaUsuarioClear = $this->horaDeSalidaUsuario(); // Obtener hora de salida del usuario

        $idUltimoRegistro = $this->ultimoRegistroScheduleUser(); //Obtener último registro de la tabla ScheduleUser

        //$prueba = $this->convertirFechaHoraCompletaEnArray();

        if ($checadasUsuario['exit'] == null) {// //Si la última checada de la salida está vacia se podrá guardar la checada

            $Extras = $SalidaUsuario->diffForHumans($horaDeSalidaUsuarioClear);

            $claves = explode(' ', $Extras);
            $claves1 = $claves[0];

            $insertarSalida = Schedule::where("id", '=', $idUltimoRegistro)->update([
                    'exit' => Carbon::now('America/Mexico_City'),
                ]);

            if ($checadasUsuario['break'] == null) {

                $checarHoraComida = $this->crearFechaCarbon($fechaActualClear[0], $fechaActualClear[1], $fechaActualClear[2], $horaDeComidaUsuarioClear[0], $horaDeComidaUsuarioClear[1], $horaDeComidaUsuarioClear[2]);

                $checarHoraFinComida = $this->crearFechaCarbon($fechaActualClear[0], $fechaActualClear[1], $fechaActualClear[2], $horaDeComidaUsuarioClear[0], $horaDeComidaUsuarioClear[1], $horaDeComidaUsuarioClear[2])->addHours(1);

                $insertarComida = Schedule::where("id", '=', $idUltimoRegistro)->update([
                        'break' => $checarHoraComida,
                        'end_break' => $checarHoraFinComida,
                    ]);

                return 'guardado';
            }

            return 'guardado';
        }

        return 0;
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

        $horasEstablecidasDeUsuario = Usuario::where("id", '=', $idUsuarioLogeado)->get();
        //$horasEstablecidasDeUsuarioArray = $horasEstablecidasDeUsuario->toArray(); 
        $horaDeSalidaUsuario = $horasEstablecidasDeUsuario[0]['hora_salida'];

        $array1 = explode(' ', $horaDeSalidaUsuario);
        $array2 = explode('-', $array1[0]);
        $array3 = explode(':', $array1[1]);

        $checarHoraSalida = $this->crearFechaCarbon($array2[0], $array2[1], $array2[2], $array3[0], $array3[1], $array3[2]);

        return $checarHoraSalida;
        //
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
