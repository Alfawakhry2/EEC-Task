<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuantityController extends Controller
{
    /**
     * Update product quantity via AJAX
     */
    public function updateProductQuantity(Request $request, $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            
            $validated = $request->validate([
                'quantity' => 'required|integer|min:0|max:999999',
            ]);

            $product->quantity = $validated['quantity'];
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product quantity updated successfully',
                'quantity' => $product->quantity,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quantity: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update pharmacy product quantity via AJAX
     */
    public function updatePharmacyProductQuantity(Request $request, $pharmacyId, $productId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);
            
            // Check if product exists in this pharmacy
            $pivotData = $pharmacy->products()->where('product_id', $productId)->first();
            
            if (!$pivotData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in this pharmacy',
                ], 404);
            }

            $validated = $request->validate([
                'quantity' => 'required|integer|min:0|max:999999',
            ]);

            // Update pivot table
            $pharmacy->products()->updateExistingPivot($productId, [
                'quantity' => $validated['quantity'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pharmacy product quantity updated successfully',
                'quantity' => $validated['quantity'],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quantity: ' . $e->getMessage(),
            ], 500);
        }
    }
}