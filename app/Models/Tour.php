<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'precio_por_persona', 'duracion_horas'];

    protected function casts(): array
    {
        return [
            'precio_por_persona' => 'decimal:2',
            'duracion_horas' => 'decimal:1',
        ];
    }

    public function salidas(): HasMany
    {
        return $this->hasMany(Salida::class);
    }
}
