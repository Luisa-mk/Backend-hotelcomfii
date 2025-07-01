<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room; // <- Importar el modelo correcto

class Comanda extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'tipo', 'descripcion', 'estado'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}