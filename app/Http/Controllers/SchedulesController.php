<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DataTables;


class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nombreUsuario = Auth::user()->name; //obtener el nombre del usuario autenticado
        $nombreUsuarioMayuscula = strtoupper($nombreUsuario); //mayúscula la cadena de texto

        $rolUsuario = Auth::user()->role_id;

        if($rolUsuario == 1){ //Admin
            $descripcionAdmin = 'Se le muestran los horarios de los empleados, puesto que usted es administrador y tiene los permisos para visualizar todos los registros. ';
        } else {
            $descripcionAdmin = '';
        }

        return view('vendor.voyager.schedules.browse',compact(['nombreUsuarioMayuscula'],['descripcionAdmin'],['rolUsuario']));

    }

    public function getPosts()
    {
        $idUsuario = Auth::id(); //obtener id de usuario autenticado
        $rolUsuario = Auth::user()->role_id;

        if($rolUsuario == 1){ //Admin

            $data = Datatables::of(DB::table('schedule_users')
                ->join('users', 'users.id', '=', 'schedule_users.user_id')
                ->join('schedules', 'schedules.id', '=', 'schedule_users.schedule_id')
                ->select('users.name', 'users.role_id', 'schedules.entry', 'schedules.break','schedules.exit', 'schedules.end_break', 'schedules.extra_time')->get())->make(true);
        } else{

            $data = Datatables::of(DB::table('schedule_users')
                ->join('users', 'users.id', '=', 'schedule_users.user_id')
                ->join('schedules', 'schedules.id', '=', 'schedule_users.schedule_id')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->select('users.name', 'users.role_id', 'schedules.entry', 'schedules.break','schedules.exit', 'schedules.end_break', 'schedules.extra_time')->where('schedule_users.user_id','=',$idUsuario)
                ->get())->make(true);
        }

        return $data;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
