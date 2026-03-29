<?php

namespace App\Http\Controllers;

use App\Models\AllergeenModel;
use Illuminate\Http\Request;

class AllergeenController extends Controller
{
    private $allergeenModel;

    public function __construct()
    {
        $this->allergeenModel = new AllergeenModel();
    }

    /**
     * Display overview of products with allergens
     * Scenario 01: Show all products with allergens or filtered by specific allergen
     */
    public function index(Request $request)
    {
        // Get all allergens for dropdown
        $allergenen = $this->allergeenModel->getAllAllergenen();

        // Get selected allergen from request
        $selectedAllergeenId = $request->input('allergeen_id');

        // Get pagination parameters
        $page = $request->input('page', 1);
        $pageSize = 4; // Fixed page size

        // Get products with allergens (all or filtered) with pagination
        $products = $this->allergeenModel->getProductsWithAllergenen($selectedAllergeenId, $page, $pageSize);

        // Get total count from first product if available
        $totalCount = count($products) > 0 ? $products[0]->TotalCount : 0;
        $totalPages = ceil($totalCount / $pageSize);

        return view('allergeen.index', [
            'title' => 'Overzicht Allergenen',
            'allergenen' => $allergenen,
            'products' => $products,
            'selectedAllergeenId' => $selectedAllergeenId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $totalCount
        ]);
    }

    /**
     * Show supplier information for a product
     * Scenario 02 & 03: Show supplier details with or without contact information
     */
    public function leverancierInfo($productId)
    {
        try {
            // Get product information
            $product = $this->allergeenModel->getProductById((int) $productId);

            if (!$product) {
                return redirect()->route('allergeen.index')
                    ->with('error', 'Product niet gevonden');
            }

            // Get supplier information for this product
            $leverancier = $this->allergeenModel->getLeverancierByProduct((int) $productId);

            if (!$leverancier) {
                return redirect()->route('allergeen.index')
                    ->with('error', 'Geen leverancier gevonden voor dit product');
            }

            // Check if contact information is available
            $hasContactInfo = !empty($leverancier->Straat) &&
                            !empty($leverancier->Huisnummer) &&
                            !empty($leverancier->Postcode) &&
                            !empty($leverancier->Stad);

            return view('allergeen.leverancier-info', [
                'title' => 'Overzicht leverancier gegevens',
                'product' => $product,
                'leverancier' => $leverancier,
                'hasContactInfo' => $hasContactInfo
            ]);

        } catch (\Exception $e) {
            return redirect()->route('allergeen.index')
                ->with('error', 'Er is een fout opgetreden: ' . $e->getMessage());
        }
    }
}
