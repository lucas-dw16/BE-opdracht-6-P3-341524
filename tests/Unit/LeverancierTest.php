<?php

use App\Models\Leverancier;

test('leverancier model can be instantiated', function () {
    $leverancier = new Leverancier();
    expect($leverancier)->toBeInstanceOf(Leverancier::class);
});

test('leverancier has correct fillable attributes', function () {
    $leverancier = new Leverancier();
    $fillable = $leverancier->getFillable();

    expect($fillable)->toContain('Naam')
        ->and($fillable)->toContain('Contactpersoon')
        ->and($fillable)->toContain('Leveranciernummer')
        ->and($fillable)->toContain('Mobiel')
        ->and($fillable)->toContain('ContactId');
});

test('leverancier can set attributes', function () {
    $leverancier = new Leverancier();
    $leverancier->fill([
        'Naam' => 'Test Leverancier',
        'Contactpersoon' => 'Jan Jansen',
        'Leveranciernummer' => 'L12345',
        'Mobiel' => '0612345678',
        'ContactId' => 1
    ]);

    expect($leverancier->Naam)->toBe('Test Leverancier')
        ->and($leverancier->Contactpersoon)->toBe('Jan Jansen')
        ->and($leverancier->Leveranciernummer)->toBe('L12345')
        ->and($leverancier->Mobiel)->toBe('0612345678')
        ->and($leverancier->ContactId)->toBe(1);
});

test('leverancier uses correct table name', function () {
    $leverancier = new Leverancier();
    expect($leverancier->getTable())->toBe('Leverancier');
});
