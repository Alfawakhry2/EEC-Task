<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class SearchCheapestPharmacies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:search-cheapest {product_id : The ID of the product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search for the 5 cheapest pharmacies that have a specific product';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->argument('product_id');
        
        // Find the product
        $product = Product::find($productId);
        
        if (!$product) {
            $this->error("Product with ID {$productId} not found.");
            return 1; // Exit code 1 for error
        }
        
        // Get cheapest 5 pharmacies
        $cheapestPharmacies = $product->pharmacies()
            ->orderBy('pharmacy_product.price', 'asc')
            ->limit(5)
            ->get(['pharmacies.id', 'pharmacies.name', 'pharmacy_product.price']);
        
        if ($cheapestPharmacies->isEmpty()) {
            $this->warn("Product '{$product->title}' is not available in any pharmacy.");
            return 0;
        }
        
        // Prepare data for JSON output
        $result = [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'base_price' => (float) $product->price,
            'cheapest_pharmacies' => $cheapestPharmacies->map(function ($pharmacy) {
                return [
                    'id' => $pharmacy->id,
                    'name' => $pharmacy->name,
                    'price' => (float) $pharmacy->pivot->price,
                ];
            })->toArray(),
            'count' => $cheapestPharmacies->count(),
        ];
        
        // Output as JSON
        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return 0; // Exit code 0 for success
    }
}