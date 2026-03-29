<?php

namespace App\Http\Controllers;

use App\Models\Leverancier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeverancierController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Leverancier();
    }

    // Overzicht leveranciers met paginatie
    public function index()
    {
        $leveranciers = $this->model->sp_GetSupplierOverview();

        // Handmatige paginatie (4 per pagina)
        $perPage = 4;
        $currentPage = request()->get('page', 1);
        $leveranciersCollection = collect($leveranciers);

        $paginatedLeveranciers = new \Illuminate\Pagination\LengthAwarePaginator(
            $leveranciersCollection->forPage($currentPage, $perPage),
            $leveranciersCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        return view('leveranciers.index', [
            'title' => 'Overzicht Leveranciers',
            'leveranciers' => $paginatedLeveranciers
        ]);
    }

    // Leverancier details
    public function details($id)
    {
        $leverancierId = (int) $id;
        $leverancier = $this->model->sp_GetLeverancierDetails($leverancierId);

        if (!$leverancier) {
            return redirect()->route('leveranciers.index')
                ->with('error', 'Leverancier niet gevonden');
        }

        return view('leveranciers.details', [
            'title' => 'Leverancier Details',
            'leverancier' => $leverancier
        ]);
    }

    // Wijzig leverancier formulier
    public function edit($id)
    {
        $leverancierId = (int) $id;
        $leverancier = $this->model->sp_GetLeverancierDetails($leverancierId);

        if (!$leverancier) {
            return redirect()->route('leveranciers.index')
                ->with('error', 'Leverancier niet gevonden');
        }

        return view('leveranciers.edit', [
            'title' => 'Wijzig Leveranciergegevens',
            'leverancier' => $leverancier
        ]);
    }

    // Update leverancier
    public function update(Request $request, $id)
    {
        $leverancierId = (int) $id;

        // Validatie
        $validated = $request->validate([
            'naam' => 'required|max:60',
            'contactpersoon' => 'required|max:60',
            'leveranciernummer' => 'required|max:11',
            'mobiel' => 'required|max:11',
            'straatnaam' => 'required|max:100',
            'huisnummer' => 'required|max:10',
            'postcode' => 'required|max:10',
            'stad' => 'required|max:60',
            'contact_id' => 'required|integer'
        ]);

        try {
            $result = $this->model->sp_UpdateLeverancier(
                $leverancierId,
                $validated['naam'],
                $validated['contactpersoon'],
                $validated['leveranciernummer'],
                $validated['mobiel'],
                $validated['contact_id'],
                $validated['straatnaam'],
                $validated['huisnummer'],
                $validated['postcode'],
                $validated['stad']
            );

            $message = $result->Message ?? 'De wijzigingen zijn doorgevoerd';

            return redirect()->route('leveranciers.details', $leverancierId)
                ->with('success', $message);

        } catch (\Exception $e) {
            $message = 'Door een technische storing is het niet mogelijk de wijziging door te voeren. Probeer het op een later moment nog eens';

            return redirect()->route('leveranciers.details', $leverancierId)
                ->with('error', $message);
        }
    }
}
