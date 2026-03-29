<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    // Userstory 1 - overview of suppliers with counts
    public function index()
    {
        $suppliers = $this->model->sp_GetSupplierOverview();

        return view('leveranciers.index', [
            'title' => 'Overzicht Leveranciers',
            'suppliers' => $suppliers
        ]);
    }

    // Show products for supplier (details)
    public function products($id)
    {
        $supplierId = (int) $id;
        $products = $this->model->sp_GetProductsBySupplier($supplierId);

        // fetch supplier info for header
        $supplier = $this->model->getSupplierById($supplierId);

        // attach VerpakkingsEenheid (if present in Magazijn) for each product
        foreach ($products as $idx => $p) {
            try {
                $row = DB::select('SELECT VerpakkingsEenheid FROM Magazijn WHERE ProductId = ? LIMIT 1', [$p->ProductId]);
                $products[$idx]->VerpakkingsEenheid = count($row) ? $row[0]->VerpakkingsEenheid : null;
            } catch (\Exception $e) {
                $products[$idx]->VerpakkingsEenheid = null;
            }
        }

        return view('suppliers.products', [
            'title' => 'Geleverde producten',
            'products' => $products,
            'supplierId' => $supplierId,
            'supplier' => $supplier
        ]);
    }

    // Show form to add new delivery for a given supplier+product
    public function createDelivery($supplierId, $productId)
    {
        $supplier = $this->model->getSupplierById((int)$supplierId);
        $product = $this->model->getProductById((int)$productId);

        return view('product.delivery', [
            'title' => 'Levering product',
            'supplierId' => (int) $supplierId,
            'productId' => (int) $productId,
            'supplier' => $supplier,
            'product' => $product,
        ]);
    }

    // Store delivery -> call sp_AddDelivery
    public function storeDelivery(Request $request, $supplierId, $productId)
    {
        $data = $request->validate([
            'aantal' => 'required|integer|min:1',
            'datum_eerstvolgende' => 'nullable|date',
            'datum_levering' => 'nullable|date',
        ]);

        $aantal = (int) $data['aantal'];
        $datumLevering = $data['datum_levering'] ?? now();
        $datumEerstVolgende = $data['datum_eerstvolgende'] ?? null;

        try {
            $result = $this->model->sp_AddDelivery((int)$supplierId, (int)$productId, $aantal, $datumLevering, $datumEerstVolgende);

            // success -> flash and redirect back to supplier products
            session()->flash('success', 'Levering toegevoegd en voorraad bijgewerkt.');
            return redirect()->route('suppliers.products', ['id' => $supplierId]);
        } catch (\Exception $e) {
            // Get product and supplier info for error message
            $product = $this->model->getProductById((int)$productId);
            $supplier = $this->model->getSupplierById((int)$supplierId);

            // Extract the actual error message from SQL error
            $errorMessage = $e->getMessage();

            // Parse SQL error to get only the message text
            // Format: SQLSTATE[45000]: <<Unknown error>>: 1644 Message (Connection: mysql, SQL: ...)
            if (preg_match('/:\s*\d+\s+([^(]+)(?:\s*\(Connection:)?/', $errorMessage, $matches)) {
                $errorMessage = trim($matches[1]);
            }

            session()->flash('error', $errorMessage);
            session()->flash('redirectUrl', route('suppliers.products', ['id' => $supplierId]));

            // when product not active, follow userstory: show message on delivery page then redirect after 4 sec
            return redirect()->route('suppliers.delivery', ['supplierId' => $supplierId, 'productId' => $productId]);
        }
    }
}
