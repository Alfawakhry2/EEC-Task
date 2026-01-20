<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Pharmacy;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PharmacyProductController extends Controller
{
    use ApiResponse;

    public function index($pharmacyId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);

            // Get all products in this pharmacy with pivot data
            $products = $pharmacy->products()->paginate();

            // Transform products with pivot data
            $productsData = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'base_price' => (float) $product->price,
                    'pharmacy_price' => (float) $product->pivot->price,
                    'quantity' => $product->pivot->quantity,
                    'created_at' => $product->created_at?->format('Y-m-d H:i:s'),
                    'updated_at' => $product->updated_at?->format('Y-m-d H:i:s'),
                ];
            });

            return $this->successResponse(
                200,
                'Pharmacy products retrieved successfully',
                [
                    'pharmacy' => [
                        'id' => $pharmacy->id,
                        'name' => $pharmacy->name,
                        'address' => $pharmacy->address,
                    ],
                    'products' => $productsData,
                    'pagination' => [
                        'total' => $products->total(),
                        'count' => $products->count(),
                        'per_page' => $products->perPage(),
                        'current_page' => $products->currentPage(),
                        'total_pages' => $products->lastPage(),
                        'links' => [
                            'first' => $products->url(1),
                            'last' => $products->url($products->lastPage()),
                            'prev' => $products->previousPageUrl(),
                            'next' => $products->nextPageUrl(),
                        ],
                    ],
                ]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Pharmacy not found');
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to retrieve pharmacy products',
                $e->getMessage()
            );
        }
    }
    /**
     * Get available products that can be added to pharmacy
     * GET /api/pharmacies/{pharmacyId}/available-products
     */
    public function create($pharmacyId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);

            // Get products that are NOT already in this pharmacy
            $availableProducts = Product::whereDoesntHave('pharmacies', function ($query) use ($pharmacyId) {
                $query->where('pharmacies.id', $pharmacyId);
            })->paginate();

            return $this->successResponse(
                200,
                'Available products retrieved successfully',
                [
                    'pharmacy_id' => $pharmacy->id,
                    'pharmacy_name' => $pharmacy->name,
                    'available_products' => ProductResource::collection($availableProducts),
                    'count' => $availableProducts->count(),
                ]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Pharmacy not found');
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to retrieve available products',
                $e->getMessage()
            );
        }
    }

    /**
     * Add product to pharmacy with price and quantity
     * 
     * POST /api/pharmacies/{pharmacyId}/products
     */
    public function store(Request $request, $pharmacyId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);

            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
            ]);

            // Check if product already exists in this pharmacy
            if ($pharmacy->products()->where('product_id', $validated['product_id'])->exists()) {
                return $this->errorResponse(
                    400,
                    'This product is already in the pharmacy'
                );
            }

            // Attach product to pharmacy with price and quantity
            $pharmacy->products()->attach($validated['product_id'], [
                'price' => $validated['price'],
                'quantity' => $validated['quantity'],
            ]);

            return $this->successResponse(
                201,
                'Product added to pharmacy successfully',
                [
                    'pharmacy_id' => $pharmacy->id,
                    'product_id' => $validated['product_id'],
                    'price' => (float) $validated['price'],
                    'quantity' => $validated['quantity'],
                ]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Pharmacy not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                422,
                'Validation failed',
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to add product to pharmacy',
                $e->getMessage()
            );
        }
    }

    /**
     * Get product details in pharmacy (for editing)
     * 
     * GET /api/pharmacies/{pharmacyId}/products/{productId}
     */
    public function edit($pharmacyId, $productId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);
            $product = Product::findOrFail($productId);

            // Get pivot data
            $pivotData = $pharmacy->products()->where('product_id', $productId)->first();

            if (!$pivotData) {
                return $this->errorResponse(
                    404,
                    'Product not found in this pharmacy'
                );
            }

            return $this->successResponse(
                200,
                'Product details retrieved successfully',
                [
                    'pharmacy' => [
                        'id' => $pharmacy->id,
                        'name' => $pharmacy->name,
                    ],
                    'product' => new ProductResource($product),
                    'current_price' => (float) $pivotData->pivot->price,
                    'current_quantity' => $pivotData->pivot->quantity,
                ]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Pharmacy or Product not found');
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to retrieve product details',
                $e->getMessage()
            );
        }
    }

    /**
     * Update product price/quantity in pharmacy
     * 
     * PUT /api/pharmacies/{pharmacyId}/products/{productId}
     */
    public function update(Request $request, $pharmacyId, $productId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);

            $validated = $request->validate([
                'price' => 'nullable|numeric|min:0',
                'quantity' => 'nullable|integer|min:0',
            ]);

            // Check if at least one field is provided
            if (!$request->has('price') && !$request->has('quantity')) {
                return $this->errorResponse(
                    422,
                    'At least one field (price or quantity) must be provided'
                );
            }

            // Check if product exists in pharmacy
            if (!$pharmacy->products()->where('product_id', $productId)->exists()) {
                return $this->errorResponse(
                    404,
                    'Product not found in this pharmacy'
                );
            }

            // Build update data (only include fields that were sent)
            $updateData = [];
            if ($request->has('price')) {
                $updateData['price'] = $validated['price'];
            }
            if ($request->has('quantity')) {
                $updateData['quantity'] = $validated['quantity'];
            }

            // Update pivot data
            $pharmacy->products()->updateExistingPivot($productId, $updateData);

            // Get current data for response
            $pivotData = $pharmacy->products()->where('product_id', $productId)->first()->pivot;

            return $this->successResponse(
                200,
                'Product updated successfully',
                [
                    'pharmacy_id' => $pharmacy->id,
                    'product_id' => $productId,
                    'price' => (float) $pivotData->price,
                    'quantity' => $pivotData->quantity,
                ]
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Pharmacy not found');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse(
                422,
                'Validation failed',
                $e->errors()
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to update product',
                $e->getMessage()
            );
        }
    }
    /**
     * Remove product from pharmacy
     * 
     * DELETE /api/pharmacies/{pharmacyId}/products/{productId}
     */
    public function destroy($pharmacyId, $productId): JsonResponse
    {
        try {
            $pharmacy = Pharmacy::findOrFail($pharmacyId);

            // Check if product exists in pharmacy
            if (!$pharmacy->products()->where('product_id', $productId)->exists()) {
                return $this->errorResponse(
                    404,
                    'Product not found in this pharmacy'
                );
            }

            // Detach product from pharmacy
            $pharmacy->products()->detach($productId);

            return $this->successResponse(
                200,
                'Product removed from pharmacy successfully'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(404, 'Pharmacy not found');
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to remove product from pharmacy',
                $e->getMessage()
            );
        }
    }
}