<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemReserva extends Pivot
{
    protected $table = 'item_reservas';

    public $incrementing = false;

    protected $fillable = ['reserva_id', 'salida_id', 'cantidad_personas', 'precio_unitario'];

    protected function casts(): array
    {
        return [
            'cantidad_personas' => 'integer',
            'precio_unitario' => 'decimal:2',
        ];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function salida(): BelongsTo
    {
        return $this->belongsTo(Salida::class);
    }
}
