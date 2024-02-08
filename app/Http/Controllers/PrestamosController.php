<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Prestamos;

class PrestamosController extends Controller
{
    //--------------------------Lista de clientes con libros vencidos
    public function obtenerClientesConLibrosVencidos()
    {
        // Actualizar el estado de los préstamos vencidos antes de obtener la lista de clientes con libros vencidos
        $this->actualizarEstadoPrestamosVencidos();

        // Consulta SQL para obtener la lista de clientes con libros vencidos
        $clientesConLibrosVencidos = DB::table('clientes')
            ->join('prestamos', 'clientes.id', '=', 'prestamos.cliente_id')
            ->join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->where('prestamos.estado', '=', 'vencido')
            ->select('clientes.*')
            ->distinct()
            ->get();

        return response()->json($clientesConLibrosVencidos, 200);
    }


    public function actualizarEstadoPrestamosVencidos()
    {
        $prestamos = Prestamos::all();

        foreach ($prestamos as $prestamo) {
            // Calcular la fecha de vencimiento sumando la fecha de préstamo y los días de préstamo
            $fechaVencimiento = Carbon::parse($prestamo->fecha_prestamo)->addDays($prestamo->dias_prestamo);

            // Comparar la fecha actual con la fecha de vencimiento
            if (Carbon::now()->greaterThan($fechaVencimiento)) {
                // Actualizar el estado del préstamo si está vencido
                $prestamo->estado = 'vencido';
                $prestamo->save();
            }
        }
    }
    //-----------------------------Lista de préstamos segmentados por semana y mes
    public function obtenerPrestamosPorSemana()
    {
        // Obtener la lista de préstamos segmentados por semana
        $prestamosPorSemana = Prestamos::select(
            DB::raw('WEEK(fecha_prestamo) as semana'),
            DB::raw('COUNT(*) as cantidad_prestamos'),
            DB::raw('DATE_FORMAT(fecha_prestamo, "%Y-%m-%d") as fecha'),
            'clientes.name as cliente',
            'libros.titulo as libro',
            'autors.name as autor'
        )
            ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
            ->join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->join('autors', 'libros.autor_id', '=', 'autors.id')
            ->groupBy(DB::raw('WEEK(fecha_prestamo)'), 'clientes.name', 'libros.titulo', 'autors.name', 'fecha')
            ->orderBy('fecha')
            ->get();

        return response()->json($prestamosPorSemana, 200);
    }

    public function obtenerPrestamosPorMes()
    {
        // Obtener la lista de préstamos segmentados por mes
        $prestamosPorMes = Prestamos::select(
            DB::raw('MONTHNAME(fecha_prestamo) as mes'),
            DB::raw('COUNT(*) as cantidad_prestamos'),
            DB::raw('YEAR(fecha_prestamo) as año'),
            'clientes.name as cliente',
            'libros.titulo as libro',
            'autors.name as autor'
        )
            ->join('clientes', 'prestamos.cliente_id', '=', 'clientes.id')
            ->join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->join('autors', 'libros.autor_id', '=', 'autors.id')
            ->groupBy(DB::raw('YEAR(fecha_prestamo)'), DB::raw('MONTH(fecha_prestamo)'), 'clientes.name', 'libros.titulo', 'autors.name')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();

        return response()->json($prestamosPorMes, 200);
    }
}