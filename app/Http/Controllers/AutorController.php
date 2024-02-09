<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autor;

class AutorController extends Controller
{

    public function getAutor()
    {
        return response()->json(Autor::all(), 200);
    }
    public function crearAutor(Request $request)
    {
        $datos = $request->only(['name']);
        $autor = Autor::create($datos);

        return response()->json($autor, 201);
    }
}
