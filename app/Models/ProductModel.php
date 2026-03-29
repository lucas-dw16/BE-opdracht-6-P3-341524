<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductModel extends Model
{
    /**
     * Execute a stored procedure call via PDO.
     */
    private function callProcedure(string $sql, array $params = []): array
    {
        $pdo = DB::connection()->getPdo();
        $statement = $pdo->prepare($sql);

        foreach ($params as $index => $value) {
            $type = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            if ($value === null) {
                $type = \PDO::PARAM_NULL;
            }

            $statement->bindValue($index + 1, $value, $type);
        }

        $statement->execute();
        $rows = $statement->fetchAll(\PDO::FETCH_OBJ);
        $statement->closeCursor();

        return $rows;
    }

    public function sp_GetAllProducts()
    {
        return DB::select('CALL sp_GetAllProducts()');
    }

    /**
     * User Story 1: overzicht producten uit assortiment binnen datumbereik.
     */
    public function sp_GetDeliveredProductsByDateRange(?string $startDate, ?string $endDate): array
    {
        return $this->callProcedure('CALL sp_GetDeliveredProductsByDateRange(?, ?)', [$startDate, $endDate]);
    }

    /**
     * User Story 1: detail van een product met allergenen-boolean velden.
     */
    public function sp_GetProductDeliveryDetails(int $productId): ?object
    {
        $rows = $this->callProcedure('CALL sp_GetProductDeliveryDetailsByDateRange(?)', [$productId]);
        return count($rows) ? $rows[0] : null;
    }

    /**
     * User Story 1: verwijder product uit assortiment indien toegestaan.
     */
    public function sp_DeleteProductFromAssortiment(int $productId, string $currentDate): ?object
    {
        try {
            $rows = $this->callProcedure('CALL sp_DeleteProductFromAssortiment(?, ?)', [$productId, $currentDate]);
            return count($rows) ? $rows[0] : null;
        } catch (\Throwable $e) {
            throw new RuntimeException($this->parseDatabaseError($e->getMessage()));
        }
    }

    private function parseDatabaseError(string $message): string
    {
        if (preg_match('/:\s*\d+\s+([^(]+)(?:\s*\(Connection:)?/', $message, $matches)) {
            return trim($matches[1]);
        }

        return $message;
    }

    /**
     * Call stored procedure sp_GetLeverantieInfo which returns supplier basic info
     * (Naam, Contactpersoon, Leveranciernummer, Mobiel) for a product.
     */
    public function sp_GetLeverantieInfo(int $productId)
    {
        return DB::select('CALL sp_GetLeverantieInfo(?)', [$productId]);
    }

    /**
     * Call stored procedure sp_GetLeverancierInfo which returns delivery history
     * (DatumLevering, Aantal, DatumEerstVolgendeLevering, AantalAanwezig) for a product.
     */
    public function sp_GetLeverancierInfo(int $productId)
    {
        return DB::select('CALL sp_GetLeverancierInfo(?)', [$productId]);
    }

    /**
     * Stored procedure: get supplier overview with count of distinct products
     */
    public function sp_GetSupplierOverview()
    {
        return DB::select('CALL sp_GetSupplierOverview()');
    }

    /**
     * Stored procedure: get products delivered by a supplier with stock and last delivery
     */
    public function sp_GetProductsBySupplier(int $leverancierId)
    {
        return DB::select('CALL sp_GetProductsBySupplier(?)', [$leverancierId]);
    }

    /**
     * Stored procedure: add a delivery record and update magazijn
     * returns the updated magazijn row or throws SQL error when SP signals
     */
    public function sp_AddDelivery(int $leverancierId, int $productId, int $aantal, string $datumLevering, ?string $datumEerstVolgende)
    {
        // Use CALL which may return a resultset
        $params = [$leverancierId, $productId, $aantal, $datumLevering, $datumEerstVolgende];
        return DB::select('CALL sp_AddDelivery(?,?,?,?,?)', $params);
    }

    /**
     * Get allergenen for a given product (joins ProductPerAllergeen -> Allergeen)
     */
    public function getProductAllergenen(int $productId)
    {
        return DB::select('CALL sp_GetProductAllergenen(?)', [$productId]);
    }

    /**
     * Get product basic info (Naam, Barcode) by id
     */
    public function getProductById(int $productId)
    {
        $rows = DB::select('CALL sp_GetProductById(?)', [$productId]);
        return count($rows) ? $rows[0] : null;
    }

    /**
     * Get supplier basic info by id
     */
    public function getSupplierById(int $supplierId)
    {
        // Use stored procedure to keep SQL centralized and ensure aliasing consistency
        $rows = DB::select('CALL sp_GetSupplierById(?)', [$supplierId]);
        return count($rows) ? $rows[0] : null;
    }
}
