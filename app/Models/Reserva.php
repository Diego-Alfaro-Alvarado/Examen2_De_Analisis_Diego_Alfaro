<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'fecha', 'total'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function salidas(): BelongsToMany
    {
        return $this->belongsToMany(Salida::class, 'item_reservas')
            ->using(ItemReserva::class)
            ->withPivot('cantidad_personas', 'precio_unitario')
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemReserva::class);
    }
}
