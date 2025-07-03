<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    protected $fillable = [
        'room_id',
        'tipo',
        'descripcion',
        'estado',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}