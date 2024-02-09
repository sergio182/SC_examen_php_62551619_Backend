<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

class LibroController extends Controller
{
    public function getLibro()
    {
        return response()->json(Libro::all(), 200);
    }



    public function crearLibro(Request $request)
    {
        $datos = $request->only(['titulo', 'autor_id', 'lote', 'description']);
        $libro = Libro::create($datos);

        return response()->json($libro, 201);
    }
}
