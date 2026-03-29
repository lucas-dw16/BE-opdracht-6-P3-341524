<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AllergeenModel extends Model
{
    protected $table = 'Allergeen';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    /**
     * Get all allergens with optional pagination
     * @param int|null $page - Page number (null = get all)
     * @param int|null $pageSize - Items per page (default: 4)
     */
    public function getAllAllergenen($page = null, $pageSize = null)
    {
        // If no pagination requested, get all allergens
        if ($page === null || $pageSize === null) {
            $page = 1;
            $pageSize = 999; // Large number to get all
        }

        $result = DB::select('CALL Sp_GetAllAllergenen(?, ?)', [$page, $pageSize]);

        return $result;
    }

    /**
     * Get products with allergens (all or filtered by allergen)
     * @param int|null $allergeenId - If null, returns all products with allergens
     * @param int $page - Page number (default: 1)
     * @param int $pageSize - Items per page (default: 4)
     */
    public function getProductsWithAllergenen($allergeenId = null, $page = 1, $pageSize = 4)
    {
        return DB::select('CALL sp_GetProductsWithAllergenen(?, ?, ?)', [$allergeenId, $page, $pageSize]);
    }

    /**
     * Get supplier information for a specific product
     */
    public function getLeverancierByProduct(int $productId)
    {
        $result = DB::select('CALL sp_GetLeverancierByProduct(?)', [$productId]);
        return $result[0] ?? null;
    }

    /**
     * Get product by ID
     */
    public function getProductById(int $productId)
    {
        $result = DB::select('CALL sp_GetProductById(?)', [$productId]);
        return $result[0] ?? null;
    }
}
