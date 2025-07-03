<?php
namespace App\Http\Controllers;

use App\Models\Comanda;
use Illuminate\Http\Request;
use App\Events\ComandaCreada;

class ComandaController extends Controller
{
    public function index()
    {
        $comandas = Comanda::with('room')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comandas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tipo' => 'required|in:limpieza,comida,otro',
            'descripcion' => 'required|string',
        ]);

        $comanda = Comanda::create($validated);

        broadcast(new ComandaCreada($comanda))->toOthers();

        return response()->json($comanda);
    }

    public function updateEstado(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,completado',
        ]);

        $comanda = Comanda::findOrFail($id);
        $comanda->estado = $validated['estado'];
        $comanda->save();

        return response()->json($comanda);
    }

    public function destroy($id)
    {
        Comanda::destroy($id);

        return response()->json(['message' => 'Comanda eliminada']);
    }
}