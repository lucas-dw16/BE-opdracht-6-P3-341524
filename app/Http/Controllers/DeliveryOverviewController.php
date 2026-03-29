<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeliveryOverviewController extends Controller
{
    public function __construct(private ProductModel $productModel)
    {
    }

    /**
     * User Story 1 - Scenario 01
     * Overzicht producten uit het assortiment.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        $hasFilter = !empty($startDate) && !empty($endDate);
        $pagination = null;
        $products = [];

        try {
            $results = $this->productModel->sp_GetDeliveredProductsByDateRange($startDate, $endDate);

            $page = (int) ($validated['page'] ?? 1);
            $perPage = 4;
            $total = count($results);
            $lastPage = (int) max(1, ceil($total / $perPage));
            $page = min($page, $lastPage);
            $offset = ($page - 1) * $perPage;

            $products = array_slice($results, $offset, $perPage);

            $pagination = (object) [
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
                'from' => $total > 0 ? $offset + 1 : 0,
                'to' => $total > 0 ? min($offset + $perPage, $total) : 0,
            ];
        } catch (\Throwable $e) {
            Log::error('Fout bij ophalen assortiment-overzicht: ' . $e->getMessage());
            return back()->with('error', 'Er is een fout opgetreden bij het ophalen van de producten.');
        }

        return view('delivery.overview', [
            'title' => 'Overzicht producten uit het assortiment',
            'products' => $products,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'hasFilter' => $hasFilter,
            'pagination' => $pagination,
        ]);
    }

    /**
     * User Story 1 - Scenario 02/03
     * Detailpagina product.
     */
    public function productDetails(int $productId)
    {
        try {
            $product = $this->productModel->sp_GetProductDeliveryDetails((int) $productId);
            if (!$product) {
                return redirect()->route('delivery.overview')
                    ->with('error', 'Product niet gevonden');
            }

            return view('delivery.product-details', [
                'title' => 'Product',
                'product' => $product,
            ]);
        } catch (\Throwable $e) {
            Log::error('Fout bij ophalen productdetails uit assortiment: ' . $e->getMessage());
            return redirect()->route('delivery.overview')
                ->with('error', 'Er is een fout opgetreden bij het ophalen van de gegevens');
        }
    }

    /**
     * User Story 1 - Scenario 02/03
     * Verwijder product (soft delete) als einddatum levering verstreken is.
     */
    public function deleteProduct(Request $request, int $productId)
    {
        $validated = $request->validate([
            'current_date' => ['nullable', 'date'],
        ]);

        $currentDate = $validated['current_date'] ?? now()->toDateString();

        try {
            $result = $this->productModel->sp_DeleteProductFromAssortiment($productId, $currentDate);

            if (($result->Success ?? 0) == 1) {
                return redirect()
                    ->route('delivery.product-details', ['productId' => $productId])
                    ->with('success', $result->Message ?? 'Product is succesvol verwijdert');
            }

            return redirect()
                ->route('delivery.product-details', ['productId' => $productId])
                ->with('error', $result->Message ?? 'Product kon niet worden verwijderd');
        } catch (\Throwable $e) {
            Log::warning('Product verwijderen mislukt: ' . $e->getMessage());

            return redirect()
                ->route('delivery.product-details', ['productId' => $productId])
                ->with('error', $e->getMessage());
        }
    }
}
