<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePharmacyRequest;
use App\Services\PharmacyService;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    protected $pharmacyService;
    public function __construct()
    {
        $this->pharmacyService = new PharmacyService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        if($search){
            $pharmacies = $this->pharmacyService->searchPharmacies($search);
        }else{
            $pharmacies = $this->pharmacyService->getAllPharmacies();
        }
        return view("pharmacies.index", compact("pharmacies" , "search"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("pharmacies.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePharmacyRequest $request)
    {
        $validated = $request->validated();
        $pharmacy = $this->pharmacyService->createPharmacy($validated);
        return redirect()->route("pharmacies.index")->with("success", "Pharmacy created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pharmacy = $this->pharmacyService->getPharmacy($id);
        if (!$pharmacy) {
            return redirect()->route("pharmacies.index")
                ->with("error", "Pharmacy not found");
        }
        return view("pharmacies.show", compact("pharmacy"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pharmacy = $this->pharmacyService->getPharmacy($id);

        if (!$pharmacy) {
            return redirect()->route('pharmacies.index')
                ->with('error', 'Pharmacy not found.');
        }

        return view('pharmacies.edit', compact('pharmacy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validated();
        $pharmacy = $this->pharmacyService->updatePharmacy($id, $validated);
        if (!$pharmacy) {
            return redirect()->route("pharmacies.index")
                ->with("error", "Pharmacy not found");
        }
        return redirect()->route("pharmacies.index")->with("success", "Pharmacy updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pharmacy = $this->pharmacyService->getPharmacy($id);
        if (!$pharmacy) {
            return redirect()->route("pharmacies.index")
                ->with("error", "Pharmacy not found");
        }
        $this->pharmacyService->deletePharmacy($id);
        return redirect()->route("pharmacies.index")->with("success", "Pharmacy deleted successfully");
    }
}
