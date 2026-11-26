<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salida extends Model
{
    use HasFactory;

    protected $fillable = ['tour_id', 'guia_id', 'fecha', 'cupos_disponibles'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cupos_disponibles' => 'integer',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class);
    }
}
