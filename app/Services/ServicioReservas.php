<?php

namespace App\Services;

use App\Exceptions\CuposInsuficientesException;
use App\Models\Reserva;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ServicioReservas
{
    public function reservar(int $clienteId, array $items): Reserva
    {
        if (empty($items)) {
            throw new InvalidArgumentException('La reserva debe incluir al menos una salida.');
        }

        return DB::transaction(function () use ($clienteId, $items) {
            usort($items, fn ($a, $b) => $a['salida_id'] <=> $b['salida_id']);

            $reserva = Reserva::create([
                'cliente_id' => $clienteId,
                'fecha' => now()->toDateString(),
                'total' => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $cantidad = (int) $item['cantidad_personas'];
                $salida = Salida::with('tour')->lockForUpdate()->findOrFail($item['salida_id']);

                $afectadas = Salida::whereKey($salida->id)
                    ->where('cupos_disponibles', '>=', $cantidad)
                    ->decrement('cupos_disponibles', $cantidad);

                if ($afectadas === 0) {
                    throw new CuposInsuficientesException("La salida {$salida->id} no tiene cupos suficientes.");
                }

                $precio = (float) $salida->tour->precio_por_persona;

                $reserva->salidas()->attach($salida->id, [
                    'cantidad_personas' => $cantidad,
                    'precio_unitario' => $precio,
                ]);

                $total += $precio * $cantidad;
            }

            $reserva->update(['total' => round($total, 2)]);

            return $reserva->load('salidas');
        });
    }
}
