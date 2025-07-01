<?php
namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;

class ComandaController extends Controller
{
    public function index()
    {
        return Comanda::with('room')->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tipo' => 'required|in:limpieza,comida,otro',
            'descripcion' => 'required|string',
        ]);

        return Comanda::create($request->all());
    }

    public function updateEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:pendiente,en_proceso,completado']);

        $comanda = Comanda::findOrFail($id);
        $comanda->estado = $request->estado;
        $comanda->save();

        return response()->json(['mensaje' => 'Estado actualizado']);
    }

    public function destroy($id)
    {
        $comanda = Comanda::findOrFail($id);
        $comanda->delete();

        return response()->json(['mensaje' => 'Comanda eliminada']);
    }
}