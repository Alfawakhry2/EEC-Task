<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\Product;
use Illuminate\Http\Request;

class PharmacyProductController extends Controller
{
    /**
     * Show form to add products to pharmacy
     */
    public function create($pharmacyId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        // Get products that are NOT already in this pharmacy
        $availableProducts = Product::whereDoesntHave('pharmacies', function($query) use ($pharmacyId) {
            $query->where('pharmacies.id', $pharmacyId);
        })->get();
        
        return view('pharmacies.add-product', compact('pharmacy', 'availableProducts'));
    }

    /**
     * Add product to pharmacy with price and quantity
     */
    public function store(Request $request, $pharmacyId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        // Check if product already exists in this pharmacy
        if ($pharmacy->products()->where('product_id', $validated['product_id'])->exists()) {
            return redirect()->back()->with('error', 'This product is already in the pharmacy.');
        }

        // Attach product to pharmacy with price and quantity
        $pharmacy->products()->attach($validated['product_id'], [
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('pharmacies.show', $pharmacyId)
            ->with('success', 'Product added to pharmacy successfully.');
    }

    /**
     * Show form to edit product pricing/quantity in pharmacy
     */
    public function edit($pharmacyId, $productId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        $product = Product::findOrFail($productId);
        
        // Get pivot data
        $pivotData = $pharmacy->products()->where('product_id', $productId)->first();
        
        if (!$pivotData) {
            return redirect()->route('pharmacies.show', $pharmacyId)
                ->with('error', 'Product not found in this pharmacy.');
        }
        
        return view('pharmacies.edit-product', compact('pharmacy', 'product', 'pivotData'));
    }

    /**
     * Update product price/quantity in pharmacy
     */
    public function update(Request $request, $pharmacyId, $productId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        // Update pivot data
        $pharmacy->products()->updateExistingPivot($productId, [
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('pharmacies.show', $pharmacyId)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove product from pharmacy
     */
    public function destroy($pharmacyId, $productId)
    {
        $pharmacy = Pharmacy::findOrFail($pharmacyId);
        
        // Detach product from pharmacy
        $pharmacy->products()->detach($productId);

        return redirect()->route('pharmacies.show', $pharmacyId)
            ->with('success', 'Product removed from pharmacy.');
    }
}