<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Permit;
use DataTables;
use App\PermitUser;

class PermitsController extends Controller
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

        $idUsuario = Auth::user()->id;

        if ($rolUsuario == 1) { //Admin
            $descripcionAdmin = 'Como administrador, usted puede visualizar todos los permisos de los empleados.';
        } else {
            $descripcionAdmin = '';
        }

        return view('vendor.voyager.permits.permits', compact('nombreUsuarioMayuscula', 'descripcionAdmin', 'idUsuario'));
    }

    public function getPosts()
    {

        $idUsuario = Auth::id(); //obtener id de usuario autenticado
        $rolUsuario = Auth::user()->role_id;

        if ($rolUsuario == 1) { //Admin

            $data = Datatables::of(DB::table('permit_users')->join('users', 'users.id', '=', 'permit_users.user_id')->join('permits', 'permits.id', '=', 'permit_users.permit_id')->select('users.name', 'users.role_id', 'permits.nombre', 'permits.created_at')->get())->make(true);
        } else {

            $data = Datatables::of(DB::table('permit_users')->join('users', 'users.id', '=', 'permit_users.user_id')->join('permits', 'permits.id', '=', 'permit_users.permit_id')->select('users.name', 'users.role_id', 'permits.nombre', 'permits.created_at')->where('permit_users.user_id', '=', $idUsuario)->get())->make(true);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permit = Permit::create($request->all(['nombre']));

        $idPermit = $permit->id;

        $permitUser = PermitUser::create([
            'user_id' => Auth::user()->id,
            'permit_id' => $idPermit,
        ]);

        alert()->success('Permiso guardado')->confirmButton('OK');

        return redirect('permits');
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
