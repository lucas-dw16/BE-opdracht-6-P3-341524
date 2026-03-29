<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Leverancier extends Model
{
    protected $table = 'Leverancier';

    protected $fillable = [
        'Naam',
        'Contactpersoon',
        'Leveranciernummer',
        'Mobiel',
        'ContactId'
    ];

    /**
     * Get supplier details with contact information
     */
    public function sp_GetLeverancierDetails(int $leverancierId)
    {
        $result = DB::select('CALL sp_GetLeverancierDetails(?)', [$leverancierId]);
        return $result ? $result[0] : null;
    }

    /**
     * Update supplier and contact information
     */
    public function sp_UpdateLeverancier(
        int $leverancierId,
        string $naam,
        string $contactpersoon,
        string $leveranciernummer,
        string $mobiel,
        int $contactId,
        string $straat,
        string $huisnummer,
        string $postcode,
        string $stad
    ) {
        try {
            $result = DB::select('CALL sp_UpdateLeverancier(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $leverancierId,
                $naam,
                $contactpersoon,
                $leveranciernummer,
                $mobiel,
                $contactId,
                $straat,
                $huisnummer,
                $postcode,
                $stad
            ]);
            return $result ? $result[0] : null;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get supplier overview with pagination
     */
    public function sp_GetSupplierOverview()
    {
        return DB::select('CALL sp_GetSupplierOverview()');
    }
}
