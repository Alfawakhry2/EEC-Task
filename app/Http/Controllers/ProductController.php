<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    protected $productService;
    public function __construct()
    {
        $this->productService = new ProductService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get("search");
        if ($search) {
            $products = $this->productService->searchProducts($search);
        } else {
            $products = $this->productService->getAllProducts();
        }
        return view("products.index", compact("products" , "search"));
    }

    public function search(Request $request)
    {
        $search = $request->get("q");
        $products = collect([]);

        if ($search) {
            $products = $this->productService->searchProducts($search);
        }

        return view('products.search', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("products.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        $product = $this->productService->createProduct($validated);
        return redirect()->route("products.index")->with("success", "Product created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productService->getProduct($id);
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = $this->productService->getProduct($id);
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }

        return view("products.edit", compact("product"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $validated = $request->validated();
        $product = $this->productService->updateProduct($id, $validated);
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }
        return redirect()->route("products.index")->with("success", "Product updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->productService->deleteProduct($id);
        if (!$product) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found.');
        }
        
        return redirect()->route("products.index")->with("success", "Product deleted successfully");
    }

}
