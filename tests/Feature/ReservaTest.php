<?php

use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Salida;
use App\Models\Tour;

test('reserva valida con dos salidas se registra y descuenta cupos', function () {
    $cliente = Cliente::factory()->create();
    $tourA = Tour::factory()->create(['precio_por_persona' => 25000]);
    $tourB = Tour::factory()->create(['precio_por_persona' => 18000]);
    $salida1 = Salida::factory()->for($tourA)->create(['cupos_disponibles' => 10]);
    $salida2 = Salida::factory()->for($tourB)->create(['cupos_disponibles' => 8]);

    $respuesta = $this->postJson('/reservas', [
        'cliente_id' => $cliente->id,
        'items' => [
            ['salida_id' => $salida1->id, 'cantidad_personas' => 3],
            ['salida_id' => $salida2->id, 'cantidad_personas' => 2],
        ],
    ]);

    $respuesta->assertCreated();

    $reservas = Reserva::where('cliente_id', $cliente->id)->get();
    expect($reservas)->toHaveCount(1);
    $reserva = $reservas->first();
    expect((float) $reserva->total)->toBe(111000.0);

    $this->assertDatabaseCount('item_reservas', 2);
    $this->assertDatabaseHas('item_reservas', [
        'reserva_id' => $reserva->id,
        'salida_id' => $salida1->id,
        'cantidad_personas' => 3,
        'precio_unitario' => 25000,
    ]);
    $this->assertDatabaseHas('item_reservas', [
        'reserva_id' => $reserva->id,
        'salida_id' => $salida2->id,
        'cantidad_personas' => 2,
        'precio_unitario' => 18000,
    ]);

    expect($salida1->fresh()->cupos_disponibles)->toBe(7)
        ->and($salida2->fresh()->cupos_disponibles)->toBe(6);

    $tourA->update(['precio_por_persona' => 30000]);

    $item = $reserva->items()->where('salida_id', $salida1->id)->first();
    expect((float) $item->precio_unitario)->toBe(25000.0);
});

test('si la segunda salida no tiene cupos no queda ningun registro', function () {
    $cliente = Cliente::factory()->create();
    $salida1 = Salida::factory()->create(['cupos_disponibles' => 10]);
    $salida2 = Salida::factory()->create(['cupos_disponibles' => 1]);

    $respuesta = $this->postJson('/reservas', [
        'cliente_id' => $cliente->id,
        'items' => [
            ['salida_id' => $salida1->id, 'cantidad_personas' => 3],
            ['salida_id' => $salida2->id, 'cantidad_personas' => 2],
        ],
    ]);

    $respuesta->assertStatus(422);

    $this->assertDatabaseCount('reservas', 0);
    $this->assertDatabaseCount('item_reservas', 0);

    expect($salida1->fresh()->cupos_disponibles)->toBe(10)
        ->and($salida2->fresh()->cupos_disponibles)->toBe(1);
});
