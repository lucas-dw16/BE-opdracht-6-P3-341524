<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeliveryOverviewTest extends TestCase
{
    /**
     * Set up the test environment before each test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure stored procedures exist before running tests
        $this->createStoredProcedures();
    }

    /**
     * Create the required stored procedures if they don't exist
     */
    private function createStoredProcedures(): void
    {
        try {
            // Drop existing procedure if it exists
            DB::statement('DROP PROCEDURE IF EXISTS sp_GetDeliveredProductsByDateRange');

            // Create sp_GetDeliveredProductsByDateRange
            DB::unprepared("
                CREATE PROCEDURE sp_GetDeliveredProductsByDateRange(
                    IN p_StartDatum DATE,
                    IN p_EindDatum DATE
                )
                BEGIN
                    SELECT
                        lev.Id AS LeverancierId,
                        lev.Naam AS LeverancierNaam,
                        lev.Contactpersoon,
                        lev.Leveranciernummer,
                        lev.Mobiel,
                        prod.Id AS ProductId,
                        prod.Naam AS ProductNaam,
                        SUM(ppl.Aantal) AS TotaalGeleverd,
                        MIN(ppl.DatumLevering) AS EersteLevering,
                        MAX(ppl.DatumLevering) AS LaatsteLevering
                    FROM
                        ProductPerLeverancier ppl
                    INNER JOIN
                        Leverancier lev ON ppl.LeverancierId = lev.Id
                    INNER JOIN
                        Product prod ON ppl.ProductId = prod.Id
                    WHERE
                        ppl.DatumLevering >= p_StartDatum
                        AND ppl.DatumLevering <= p_EindDatum
                        AND ppl.IsActief = 1
                        AND lev.IsActief = 1
                        AND prod.IsActief = 1
                    GROUP BY
                        lev.Id, lev.Naam, lev.Contactpersoon, lev.Leveranciernummer, lev.Mobiel,
                        prod.Id, prod.Naam
                    ORDER BY
                        lev.Naam ASC, prod.Naam ASC;
                END
            ");
        } catch (\Exception $e) {
            // Log the error but continue - tests will fail if procedures are really needed
            \Log::warning('Could not create stored procedures for tests: ' . $e->getMessage());
        }
    }

    /**
     * Test that delivered products can be retrieved within a date range
     * Tests User Story 1 - Scenario 01
     */
    public function test_get_delivered_products_within_date_range(): void
    {
        // Test with the specific date range from the user story (08-04-2023 to 19-04-2023)
        $startDate = '2023-04-08';
        $endDate = '2023-04-19';

        try {
            $results = DB::select('CALL sp_GetDeliveredProductsByDateRange(?, ?)', [$startDate, $endDate]);

            // Assert we got results
            $this->assertNotEmpty($results, 'Er moeten geleverde producten zijn in de periode 08-04-2023 t/m 19-04-2023');

            // Check structure of first result
            if (count($results) > 0) {
                $firstProduct = $results[0];

                $this->assertObjectHasProperty('LeverancierNaam', $firstProduct);
                $this->assertObjectHasProperty('ProductNaam', $firstProduct);
                $this->assertObjectHasProperty('TotaalGeleverd', $firstProduct);
                $this->assertObjectHasProperty('Contactpersoon', $firstProduct);

                // Verify that total delivered is a positive number
                $this->assertGreaterThan(0, $firstProduct->TotaalGeleverd);
            }

            // Verify that Mintnopjes is in the results (as per wireframe requirements)
            $mintnopjesFound = false;
            foreach ($results as $product) {
                if ($product->ProductNaam === 'Mintnopjes') {
                    $mintnopjesFound = true;
                    // Mintnopjes should have been delivered twice: 23 on 09-04 and 21 on 18-04 = 44 total
                    $this->assertEquals(44, $product->TotaalGeleverd, 'Mintnopjes moet 44 stuks geleverd zijn in deze periode');
                    break;
                }
            }

            $this->assertTrue($mintnopjesFound, 'Mintnopjes moet in de resultaten staan voor deze periode');

        } catch (\Exception $e) {
            $this->fail('Stored procedure sp_GetDeliveredProductsByDateRange faalde: ' . $e->getMessage());
        }
    }

    /**
     * Test that no deliveries message is shown for empty period
     * Tests User Story 1 - Scenario 03
     */
    public function test_no_deliveries_in_empty_period(): void
    {
        // Test with a date range that has no deliveries (07-05-2024 to 14-05-2025)
        $startDate = '2024-05-07';
        $endDate = '2025-05-14';

        try {
            $results = DB::select('CALL sp_GetDeliveredProductsByDateRange(?, ?)', [$startDate, $endDate]);

            // Assert we have no results for this period
            $this->assertEmpty($results, 'Er mogen geen leveringen zijn in de periode 07-05-2024 t/m 14-05-2025');

        } catch (\Exception $e) {
            $this->fail('Stored procedure sp_GetDeliveredProductsByDateRange faalde: ' . $e->getMessage());
        }
    }

    /**
     * Test that product delivery details can be retrieved (Scenario 02)
     * Tests User Story 1 - Scenario 02
     */
    public function test_get_product_delivery_details(): void
    {
        // Get Mintnopjes (Product ID 1) details for the date range
        $productId = 1;
        $startDate = '2023-04-08';
        $endDate = '2023-04-19';

        try {
            // Get product info
            $product = DB::selectOne('
                SELECT Id, Naam, Barcode
                FROM Product
                WHERE Id = ? AND IsActief = 1
            ', [$productId]);

            $this->assertNotNull($product, 'Mintnopjes product moet gevonden worden');
            $this->assertEquals('Mintnopjes', $product->Naam);
            $this->assertEquals('8719587231278', $product->Barcode);

            // Get deliveries for this product in date range
            $deliveries = DB::select('
                SELECT
                    ppl.DatumLevering,
                    ppl.Aantal,
                    ppl.DatumEerstVolgendeLevering,
                    lev.Naam AS LeverancierNaam,
                    lev.Contactpersoon
                FROM
                    ProductPerLeverancier ppl
                INNER JOIN
                    Leverancier lev ON ppl.LeverancierId = lev.Id
                WHERE
                    ppl.ProductId = ?
                    AND ppl.DatumLevering >= ?
                    AND ppl.DatumLevering <= ?
                    AND ppl.IsActief = 1
                    AND lev.IsActief = 1
                ORDER BY
                    ppl.DatumLevering DESC
            ', [$productId, $startDate, $endDate]);

            // Assert we have deliveries
            $this->assertNotEmpty($deliveries, 'Mintnopjes moet leveringen hebben in deze periode');
            $this->assertCount(2, $deliveries, 'Mintnopjes moet 2 leveringen hebben in deze periode');

            // Check first delivery (most recent should be 18-04-2023 with 21 items)
            $firstDelivery = $deliveries[0];
            $this->assertEquals('2023-04-18', date('Y-m-d', strtotime($firstDelivery->DatumLevering)));
            $this->assertEquals(21, $firstDelivery->Aantal);
            $this->assertEquals('Venco', $firstDelivery->LeverancierNaam);

            // Check second delivery (should be 09-04-2023 with 23 items)
            $secondDelivery = $deliveries[1];
            $this->assertEquals('2023-04-09', date('Y-m-d', strtotime($secondDelivery->DatumLevering)));
            $this->assertEquals(23, $secondDelivery->Aantal);

            // Get allergenen for Mintnopjes
            $allergenen = DB::select('
                SELECT
                    a.Id AS AllergeenId,
                    a.Naam AS AllergeenNaam,
                    a.Omschrijving
                FROM
                    ProductPerAllergeen ppa
                INNER JOIN
                    Allergeen a ON ppa.AllergeenId = a.Id
                WHERE
                    ppa.ProductId = ?
                    AND ppa.IsActief = 1
                    AND a.IsActief = 1
                ORDER BY
                    a.Naam ASC
            ', [$productId]);

            // Assert Mintnopjes has allergenen (Gluten, Gelatine, AZO-kleurstof according to spec)
            $this->assertNotEmpty($allergenen, 'Mintnopjes moet allergenen hebben');
            $this->assertGreaterThanOrEqual(3, count($allergenen), 'Mintnopjes moet minstens 3 allergenen hebben');

            // Verify specific allergens
            $allergenNames = array_column($allergenen, 'AllergeenNaam');
            $this->assertContains('Gluten', $allergenNames);
            $this->assertContains('Gelatine', $allergenNames);

        } catch (\Exception $e) {
            $this->fail('Product delivery details query faalde: ' . $e->getMessage());
        }
    }

    /**
     * Test that results are ordered by supplier name (A-Z)
     */
    public function test_results_ordered_by_supplier_name(): void
    {
        $startDate = '2023-04-08';
        $endDate = '2023-04-19';

        try {
            $results = DB::select('CALL sp_GetDeliveredProductsByDateRange(?, ?)', [$startDate, $endDate]);

            $this->assertNotEmpty($results);

            // Verify ordering (supplier names should be in alphabetical order)
            $previousSupplier = '';
            foreach ($results as $product) {
                if ($previousSupplier !== '') {
                    $this->assertLessThanOrEqual(0, strcmp($previousSupplier, $product->LeverancierNaam),
                        'Leveranciers moeten gesorteerd zijn op naam (A-Z)');
                }
                $previousSupplier = $product->LeverancierNaam;
            }

        } catch (\Exception $e) {
            $this->fail('Ordering test faalde: ' . $e->getMessage());
        }
    }
}
