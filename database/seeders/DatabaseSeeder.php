<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Pharmacy;
use App\Models\Product;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        
        $this->command->info('Creating products...');
        $this->createProducts(1000); 
        
        $this->command->info('Creating pharmacies...');
        $this->createPharmacies(200);
        
        $this->command->info('Creating pharmacy-product relationships...');
        $this->createPharmacyProductRelationships();
        
        $this->command->info('Database seeding completed successfully!');

    }



    private function createProducts(int $count): void
    {
        $chunkSize = 1000;
        $chunks = ceil($count / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            // Calculate how many to create in this iteration
            $remaining = $count - ($i * $chunkSize);
            $toCreate = min($chunkSize, $remaining);
            
            Product::factory($toCreate)->create();
            $this->command->info("Created " . (($i + 1) * $toCreate) . " / {$count} products...");
        }
    }



    private function createPharmacies(int $count): void
    {
        $chunkSize = 1000;
        $chunks = ceil($count / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            // Calculate how many to create in this iteration
            $remaining = $count - ($i * $chunkSize);
            $toCreate = min($chunkSize, $remaining);
            
            Pharmacy::factory($toCreate)->create();
            $this->command->info("Created " . (($i + 1) * $toCreate) . " / {$count} pharmacies...");
        }
    }


    private function createPharmacyProductRelationships(): void
    {
        $products = Product::select('id', 'price')->get();
        // need to take all ids to make variation
        $pharmacyIds = Pharmacy::pluck('id')->toArray();

        $relationships = [];
        $batchSize = 10000;
        $count = 0;
        $totalProducts = $products->count();

        foreach ($products as $index => $product) {
            // Each product available in 5-15 random pharmacies
            $numberOfPharmacies = rand(5, 15);
            // array_flip will return the ids in pharmacy table(actual ids)
            $randomPharmacies = array_rand(array_flip($pharmacyIds), $numberOfPharmacies);

            foreach ($randomPharmacies as $pharmacyId) {
                // Price variation: (+/-)20% from base price (in product table)
                $priceVariation = $product->price * (rand(80, 120) / 100);

                $relationships[] = [
                    'pharmacy_id' => $pharmacyId,
                    'product_id' => $product->id,
                    'price' => round($priceVariation, 2),
                    'quantity' => rand(10, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $count++;

                // Insert in batches for performance (as chunking)
                if ($count >= $batchSize) {
                    DB::table('pharmacy_product')->insert($relationships);
                    $this->command->info("Inserted {$count} relationships...");
                    $relationships = [];
                    $count = 0;
                }
            }

            if (($index + 1) % 100 == 0) {
                $this->command->info("Processed " . ($index + 1) . " / {$totalProducts} products...");
            }
        }

        // Insert remaining relationships
        if (!empty($relationships)) {
            DB::table('pharmacy_product')->insert($relationships);
            $this->command->info("Inserted final batch of " . count($relationships) . " relationships");
        }
    }
}