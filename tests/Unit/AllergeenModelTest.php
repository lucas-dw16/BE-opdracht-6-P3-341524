<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\AllergeenModel;

class AllergeenModelTest extends TestCase
{
    private $allergeenModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->allergeenModel = new AllergeenModel();
    }

    /**
     * Test that getAllAllergenen returns all allergens
     */
    public function test_get_all_allergenen_returns_data(): void
    {
        $allergenen = $this->allergeenModel->getAllAllergenen();

        $this->assertNotEmpty($allergenen);
        $this->assertIsArray($allergenen);

        // Check that we have at least the 5 allergens from the database
        $this->assertGreaterThanOrEqual(5, count($allergenen));

        // Verify structure of first allergen
        if (count($allergenen) > 0) {
            $this->assertObjectHasProperty('Id', $allergenen[0]);
            $this->assertObjectHasProperty('Naam', $allergenen[0]);
            $this->assertObjectHasProperty('Omschrijving', $allergenen[0]);
        }
    }

    /**
     * Test that getProductsWithAllergenen returns products with allergens
     */
    public function test_get_products_with_allergenen_returns_data(): void
    {
        // Get all products with allergens (no filter, large page size for testing)
        $products = $this->allergeenModel->getProductsWithAllergenen(null, 1, 999);

        $this->assertNotEmpty($products);
        $this->assertIsArray($products);

        // Verify structure of first product
        if (count($products) > 0) {
            $this->assertObjectHasProperty('ProductId', $products[0]);
            $this->assertObjectHasProperty('ProductNaam', $products[0]);
            $this->assertObjectHasProperty('Allergenen', $products[0]);
        }
    }

    /**
     * Test that filtering by specific allergen works
     */
    public function test_get_products_filtered_by_allergen(): void
    {
        // Filter by Lactose (ID = 4, large page size for testing)
        $products = $this->allergeenModel->getProductsWithAllergenen(4, 1, 999);

        $this->assertNotEmpty($products);

        // All products should contain Lactose in their allergen list
        foreach ($products as $product) {
            $this->assertStringContainsString('Lactose', $product->Allergenen);
        }
    }

    /**
     * Test that getLeverancierByProduct returns correct supplier info
     */
    public function test_get_leverancier_by_product_returns_data(): void
    {
        // Test with Product ID 12 (Kruis Drop)
        $leverancier = $this->allergeenModel->getLeverancierByProduct(12);

        $this->assertNotNull($leverancier);
        $this->assertObjectHasProperty('LeverancierNaam', $leverancier);
        $this->assertObjectHasProperty('Contactpersoon', $leverancier);
        $this->assertObjectHasProperty('Mobiel', $leverancier);

        // Kruis Drop is supplied by De Bron
        $this->assertEquals('De Bron', $leverancier->LeverancierNaam);
    }

    /**
     * Test that getLeverancierByProduct returns contact info when available
     */
    public function test_get_leverancier_with_contact_info(): void
    {
        // Test with Product ID 12 (Kruis Drop) - has contact info
        $leverancier = $this->allergeenModel->getLeverancierByProduct(12);

        $this->assertNotNull($leverancier);
        $this->assertNotEmpty($leverancier->Straat);
        $this->assertNotEmpty($leverancier->Huisnummer);
        $this->assertNotEmpty($leverancier->Postcode);
        $this->assertNotEmpty($leverancier->Stad);
    }

    /**
     * Test that getLeverancierByProduct returns null contact fields when not available
     */
    public function test_get_leverancier_without_contact_info(): void
    {
        // Test with Product ID 14 (Drop ninja's) - no contact info (Hom Ken Food has NULL ContactId)
        $leverancier = $this->allergeenModel->getLeverancierByProduct(14);

        $this->assertNotNull($leverancier);
        $this->assertEquals('Hom Ken Food', $leverancier->LeverancierNaam);

        // Contact fields should be null
        $this->assertNull($leverancier->Straat);
        $this->assertNull($leverancier->Huisnummer);
        $this->assertNull($leverancier->Postcode);
        $this->assertNull($leverancier->Stad);
    }

    /**
     * Test that getProductById returns correct product
     */
    public function test_get_product_by_id_returns_correct_product(): void
    {
        // Test with Product ID 12 (Kruis Drop)
        $product = $this->allergeenModel->getProductById(12);

        $this->assertNotNull($product);
        $this->assertObjectHasProperty('Naam', $product);
        $this->assertObjectHasProperty('Barcode', $product);
        $this->assertEquals('Kruis Drop', $product->Naam);
        $this->assertEquals('8719587322265', $product->Barcode);
    }

    /**
     * Test that invalid product ID returns null
     */
    public function test_get_product_by_invalid_id_returns_null(): void
    {
        $product = $this->allergeenModel->getProductById(99999);

        $this->assertNull($product);
    }

    /**
     * Test that products are sorted alphabetically
     */
    public function test_products_are_sorted_alphabetically(): void
    {
        $products = $this->allergeenModel->getProductsWithAllergenen(null, 1, 999);

        $this->assertNotEmpty($products);

        // Check that products are in alphabetical order
        for ($i = 0; $i < count($products) - 1; $i++) {
            $current = $products[$i]->ProductNaam;
            $next = $products[$i + 1]->ProductNaam;

            $this->assertLessThanOrEqual(0, strcasecmp($current, $next),
                "Products should be sorted alphabetically. Found '$current' before '$next'");
        }
    }

    /**
     * Test that all allergens have valid names (not empty or null)
     */
    public function test_all_allergenen_have_valid_names(): void
    {
        $allergenen = $this->allergeenModel->getAllAllergenen();

        $this->assertNotEmpty($allergenen);

        // Verify that each allergen has a valid name
        foreach ($allergenen as $allergeen) {
            $this->assertNotNull($allergeen->Naam, "Allergen name should not be null");
            $this->assertNotEmpty($allergeen->Naam, "Allergen name should not be empty");
            $this->assertIsString($allergeen->Naam, "Allergen name should be a string");
            $this->assertGreaterThan(0, strlen(trim($allergeen->Naam)),
                "Allergen name should not be whitespace only");
        }
    }

    /**
     * Test that all allergens have descriptions
     */
    public function test_all_allergenen_have_descriptions(): void
    {
        $allergenen = $this->allergeenModel->getAllAllergenen();

        $this->assertNotEmpty($allergenen);

        // Verify that each allergen has a description
        foreach ($allergenen as $allergeen) {
            $this->assertObjectHasProperty('Omschrijving', $allergeen,
                "Allergen should have 'Omschrijving' property");

            // Description should exist and be a string (can be empty but not null)
            $this->assertNotNull($allergeen->Omschrijving,
                "Allergen '{$allergeen->Naam}' should have a description");
            $this->assertIsString($allergeen->Omschrijving,
                "Allergen description should be a string");
        }
    }

    /**
     * Test that pagination works correctly for products with allergens
     */
    public function test_products_pagination_works(): void
    {
        // Get first page with 4 items
        $page1 = $this->allergeenModel->getProductsWithAllergenen(null, 1, 4);

        $this->assertCount(4, $page1, "First page should have exactly 4 items");

        // Verify TotalCount field exists
        $this->assertObjectHasProperty('TotalCount', $page1[0]);
        $totalCount = $page1[0]->TotalCount;

        // Get second page
        if ($totalCount > 4) {
            $page2 = $this->allergeenModel->getProductsWithAllergenen(null, 2, 4);

            $this->assertNotEmpty($page2);

            // Verify pages contain different products
            $this->assertNotEquals($page1[0]->ProductId, $page2[0]->ProductId,
                "Different pages should contain different products");
        }
    }
}
