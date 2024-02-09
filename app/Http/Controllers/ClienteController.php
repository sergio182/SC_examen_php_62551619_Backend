<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function getClientes()
    {
        return response()->json(Cliente::all(), 200);
    }
    public function crearCliente(Request $request)
    {

        $datos = $request->only(['name', 'email', 'celular']);
        $cliente = Cliente::create($datos);

        return response()->json($cliente, 200);
    }
}