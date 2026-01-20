<?php

namespace App\Services;

use App\Models\Pharmacy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PharmacyService
{
    /**
     * Get all pharmacies with pagination
     */
    public function getAllPharmacies(int $perPage = 10)
    {
        
        return Pharmacy::orderBy('name', 'asc')->paginate($perPage);
    }
    
    public function searchPharmacies(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return Pharmacy::where('name', 'LIKE', "%{$query}%")
            ->orWhere('address', 'LIKE', "%{$query}%")
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }
    /**
     * Get pharmacy by ID
     */
    public function getPharmacy(int $id)
    {
        return Pharmacy::find($id);
    }

    /**
     * Get pharmacy with products
     */
    public function getPharmacyWithProducts(int $id)
    {
        return Pharmacy::with('products')->find($id);
    }

    /**
     * Create new pharmacy
     */
    public function createPharmacy(array $data)
    {
        return Pharmacy::create($data);
    }

    /**
     * Update pharmacy
     */
    public function updatePharmacy(int $id, array $data):bool
    {
        $pharmacy = $this->getPharmacy($id);
        
        if (!$pharmacy) {
            return false;
        }

        return $pharmacy->update($data);
    }

    /**
     * Delete pharmacy
     */
    public function deletePharmacy(int $id):bool
    {
        $pharmacy = $this->getPharmacy($id);
        
        if (!$pharmacy) {
            return false;
        }

        return $pharmacy->delete();
    }
}