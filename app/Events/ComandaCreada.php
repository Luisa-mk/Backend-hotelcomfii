<?php

namespace App\Events;

use App\Models\Comanda;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ComandaCreada implements ShouldBroadcast
{
    use SerializesModels;

    public $comanda;

    public function __construct(Comanda $comanda)
    {
        $this->comanda = $comanda->load('room'); // para incluir datos de habitación
    }

    public function broadcastOn(): Channel
    {
        return new Channel('comandas');
    }

    public function broadcastAs(): string
    {
        return 'comanda.creada';
    }
}