<?php

use App\Models\ProductModel;

afterEach(function (): void {
    \Mockery::close();
});

test('product verwijderen toont succesmelding', function () {
    $mock = \Mockery::mock(ProductModel::class);
    $mock->shouldReceive('sp_DeleteProductFromAssortiment')
        ->once()
        ->with(2, '2024-05-25')
        ->andReturn((object) [
            'Success' => 1,
            'Message' => 'Product is succesvol verwijdert',
        ]);

    app()->instance(ProductModel::class, $mock);

    $response = $this->post(route('delivery.product-delete', ['productId' => 2]), [
        'current_date' => '2024-05-25',
    ]);

    $response->assertRedirect(route('delivery.product-details', ['productId' => 2]));
    $response->assertSessionHas('success', 'Product is succesvol verwijdert');
});

test('product verwijderen voor einddatum toont foutmelding', function () {
    $mock = \Mockery::mock(ProductModel::class);
    $mock->shouldReceive('sp_DeleteProductFromAssortiment')
        ->once()
        ->with(3, '2024-05-25')
        ->andThrow(new RuntimeException('Product kan niet worden verwijdert, datum van vandaag ligt voor einddatum levering'));

    app()->instance(ProductModel::class, $mock);

    $response = $this->post(route('delivery.product-delete', ['productId' => 3]), [
        'current_date' => '2024-05-25',
    ]);

    $response->assertRedirect(route('delivery.product-details', ['productId' => 3]));
    $response->assertSessionHas('error', 'Product kan niet worden verwijdert, datum van vandaag ligt voor einddatum levering');
});
