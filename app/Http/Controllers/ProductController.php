<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
       $this->productModel = new ProductModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productModel->sp_GetAllProducts();

        return view('product.index', [
            'title' => 'Overzicht Magazijn Jamin',
            'products' => $products
        ]);
    }

    public function allergenenInfo($id)
    {
        // fetch product basic info (Naam, Barcode)
        $product = $this->productModel->getProductById((int) $id);

        // fetch allergenen for product
        $allergenen = $this->productModel->getProductAllergenen((int) $id);
        $hasAllergenen = count($allergenen) > 0;

        return view('product.allergeenInfo', [
            'title' => 'Allergeen Informatie',
            'allergenen' => $allergenen,
            'hasAllergenen' => $hasAllergenen,
            'productId' => $id,
            'productName' => $product->Naam ?? null,
            'productBarcode' => $product->Barcode ?? null,
        ]);
    }

     public function leverantieInfo($id)
    {
        $productId = (int) $id;

        // supplier basic info (Naam, Contactpersoon, Leveranciernummer, Mobiel)
        $leveranciers = $this->productModel->sp_GetLeverantieInfo($productId);

        // delivery history and next delivery (DatumLevering, Aantal, DatumEerstVolgendeLevering, AantalAanwezig)
        $deliveries = $this->productModel->sp_GetLeverancierInfo($productId);

        // determine voorraad (if available from deliveries join)
        $aantalAanwezig = null;
        if (!empty($deliveries)) {
            // stored proc returns MAGA.AantalAanwezig on each row — take from first
            $aantalAanwezig = $deliveries[0]->AantalAanwezig ?? null;
        }

        // sort deliveries by DatumLevering ascending
        usort($deliveries, function ($a, $b) {
            $da = strtotime($a->DatumLevering);
            $db = strtotime($b->DatumLevering);
            return $da <=> $db;
        });

        return view('product.leverantieInfo', [
            'title' => 'Leverantie Informatie',
            'leveranciers' => $leveranciers,
            'deliveries' => $deliveries,
            'aantalAanwezig' => $aantalAanwezig,
            'productId' => $productId
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductModel $productModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductModel $productModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductModel $productModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductModel $productModel)
    {
        //
    }
}
