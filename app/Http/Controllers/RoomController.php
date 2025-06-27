<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        try {
            $estado = $request->query('estado');
            $rooms = $estado ? Room::where('estado', $estado)->get() : Room::all();

            return response()->json($rooms);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener habitaciones', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'numero' => 'required|unique:rooms',
                'tipo' => 'required|string',
                'estado' => 'required|in:disponible,ocupada,reservada,limpieza',
            ]);

            $room = Room::create($request->all());

            return response()->json(['message' => 'Habitación agregada correctamente', 'room' => $room], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error inesperado al guardar habitación',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $room = Room::findOrFail($id);

            $request->validate([
                'numero' => 'required|unique:rooms,numero,' . $id,
                'tipo' => 'required|string',
                'estado' => 'required|in:disponible,ocupada,reservada,limpieza',
            ]);

            $room->update($request->all());

            return response()->json(['message' => 'Habitación actualizada correctamente', 'room' => $room]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error inesperado al actualizar',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $room = Room::findOrFail($id);
            $room->delete();

            return response()->json(['message' => 'Habitación eliminada correctamente']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar habitación',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}