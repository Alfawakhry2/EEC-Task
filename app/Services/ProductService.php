<?php
namespace App\Services;

use App\Models\Product;
use Storage;

class ProductService
{
    public function getAllProducts($perPage=10)
    {
        return Product::orderBy('created_at', 'desc')->paginate($perPage);
    }


    public function getProduct($id)
    {
        return Product::findOrFail($id);
    }

    public function createProduct(array $data)
    {
        //handle image if exist
        if (isset($data['image']) && $data['image']) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return Product::create($data);
    }


    public function updateProduct(int $id, array $data): bool
    {
        $product = $this->getProduct($id);

        if (!$product) {
            return false;
        }

        // Handle image upload if exists
        if (isset($data['image']) && $data['image']) {
            // Delete old
            $this->deleteImage($product->image);
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return $product->update($data);
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->getProduct($id);

        if (!$product) {
            return false;
        }
        $this->deleteImage($product->image);
        return $product->delete();
    }

    public function getProductWithPharmacies(int $id)
    {
        return Product::with(['pharmacies'])->findOrFail($id);
    }


    public function searchProducts(string $query, int $perPage = 10)
    {
        return Product::where('title', 'LIKE', "%{$query}%")
            ->orderBy('title', 'asc')
            ->paginate($perPage);
    }


    private function handleImageUpload($image): string
    {
        $path = $image->store('products', 'public');
        return $path;
    }
    private function deleteImage(?string $imagePath): void
    {
        // Only delete if image path exists and file is in our storage
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}

