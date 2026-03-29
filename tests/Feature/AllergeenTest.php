<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllergeenTest extends TestCase
{
    /**
     * Test that the allergeen index page loads successfully
     */
    public function test_allergeen_index_page_loads(): void
    {
        $response = $this->get('/allergenen');

        $response->assertStatus(200);
        $response->assertViewIs('allergeen.index');
        $response->assertViewHas('allergenen');
        $response->assertViewHas('products');
        $response->assertSee('Overzicht Allergenen');
    }

    /**
     * Test that filtering by allergen works correctly
     */
    public function test_allergeen_filter_works(): void
    {
        // Test filtering by Lactose (ID = 4)
        $response = $this->get('/allergenen?allergeen_id=4');

        $response->assertStatus(200);
        $response->assertViewHas('selectedAllergeenId', '4');
        $response->assertSee('Maak Selectie');
    }

    /**
     * Test that the leverancier info page loads for a product
     */
    public function test_leverancier_info_page_loads_for_product(): void
    {
        // Test with Kruis Drop (Product ID = 12, has Lactose allergen, supplier De Bron with contact info)
        $response = $this->get('/allergenen/product/12/leverancier');

        $response->assertStatus(200);
        $response->assertViewIs('allergeen.leverancier-info');
        $response->assertViewHas('product');
        $response->assertViewHas('leverancier');
        $response->assertViewHas('hasContactInfo');
    }

    /**
     * Test that products without contact info show warning message
     */
    public function test_leverancier_without_contact_shows_warning(): void
    {
        // Test with Drop ninja's (Product ID = 14, has Soja allergen, supplier Hom Ken Food without contact info)
        $response = $this->get('/allergenen/product/14/leverancier');

        $response->assertStatus(200);
        $response->assertSee('Er zijn geen adresgegevens bekend');
        $response->assertViewHas('hasContactInfo', false);
    }

    /**
     * Test that all products with allergens are sorted alphabetically by name
     */
    public function test_products_are_sorted_alphabetically(): void
    {
        $response = $this->get('/allergenen');

        $response->assertStatus(200);

        // Get the products from the view data
        $products = $response->viewData('products');

        // Check that products are sorted (first product should come before last alphabetically)
        if (count($products) >= 2) {
            $firstProduct = $products[0]->ProductNaam;
            $lastProduct = end($products)->ProductNaam;

            // Verify alphabetical order
            $this->assertLessThanOrEqual(0, strcasecmp($firstProduct, $lastProduct));
        }
    }

    /**
     * Test that invalid product ID redirects with error
     */
    public function test_invalid_product_id_redirects_with_error(): void
    {
        $response = $this->get('/allergenen/product/99999/leverancier');

        $response->assertRedirect(route('allergeen.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test that the allergen dropdown contains all allergens
     */
    public function test_allergen_dropdown_shows_all_allergens(): void
    {
        $response = $this->get('/allergenen');

        $response->assertStatus(200);
        $response->assertSee('Gluten');
        $response->assertSee('Gelatine');
        $response->assertSee('AZO-Kleurstof');
        $response->assertSee('Lactose');
        $response->assertSee('Soja');
    }

    /**
     * Test that products with specific allergen are displayed correctly
     */
    public function test_filtering_by_lactose_shows_correct_products(): void
    {
        // Filter by Lactose (ID = 4)
        $response = $this->get('/allergenen?allergeen_id=4');

        $response->assertStatus(200);

        // Products with Lactose: Honingdrop, Kruis Drop, Zoute Ruitjes
        $response->assertSee('Kruis Drop');
    }
}
