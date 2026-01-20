<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    /**
     * Display a listing of the resource.
     * 
     * GET /api/products
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get("search");
        $perPage = $request->get("per_page", 10);

        if ($search) {
            $products = $this->productService->searchProducts($search);
        } else {
            $products = $this->productService->getAllProducts($perPage);
        }

        return $this->successResponse(
            200,
            'Products retrieved successfully',
            [
                ProductResource::collection($products),
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
                ]
            ]

        );
    }

    /**
     * Search products
     * 
     * GET /api/products/search
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get("q");
        $products = collect([]);

        if ($search) {
            $products = $this->productService->searchProducts($search);
        }

        return $this->successResponse(
            200,
            $search ? 'Search results retrieved successfully' : 'No search query provided',
            [
                // 'query' => $search,
                [
                    'products' => ProductResource::collection($products),
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
                    ]
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * 
     * POST /api/products
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $product = $this->productService->createProduct($validated);

            return $this->successResponse(
                201,
                'Product created successfully',
                new ProductResource($product)
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to create product',
                $e->getMessage()
            );
        }
    }

    /**
     * Display the specified resource.
     * 
     * GET /api/products/{id}
     */
    public function show(string $id): JsonResponse
    {
        $product = $this->productService->getProduct($id);

        if (!$product) {
            return $this->errorResponse(404, 'Product not found');
        }

        return $this->successResponse(
            200,
            'Product retrieved successfully',
            new ProductResource($product)
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * PUT /api/products/{id}
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        try {
            // First check if product exists
            $product = $this->productService->getProduct($id);

            if (!$product) {
                return $this->errorResponse(404, 'Product not found');
            }

            // Update the product
            $validated = $request->validated();
            $this->productService->updateProduct($id, $validated);

            // Get the updated product
            $updatedProduct = $this->productService->getProduct($id);

            return $this->successResponse(
                200,
                'Product updated successfully',
                new ProductResource($updatedProduct)
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
     * Remove the specified resource from storage.
     * 
     * DELETE /api/products/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $product = $this->productService->deleteProduct($id);

            if (!$product) {
                return $this->errorResponse(404, 'Product not found');
            }

            return $this->successResponse(
                200,
                'Product deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to delete product',
                $e->getMessage()
            );
        }
    }
}