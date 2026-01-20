<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePharmacyRequest;
use App\Http\Requests\UpdatePharmacyRequest;
use App\Http\Resources\PharmacyResource;
use App\Services\PharmacyService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    use ApiResponse;

    protected $pharmacyService;

    public function __construct()
    {
        $this->pharmacyService = new PharmacyService();
    }

    /**
     * Display a listing of the resource.
     * 
     * GET /api/pharmacies
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        if ($search) {
            $pharmacies = $this->pharmacyService->searchPharmacies($search);
        } else {
            $pharmacies = $this->pharmacyService->getAllPharmacies($perPage);
        }

        return $this->successResponse(
            200,
            'Pharmacies retrieved successfully',
            [
                'pharmacies' => PharmacyResource::collection($pharmacies),
                'pagination' => [
                    'total' => $pharmacies->total(),
                    'count' => $pharmacies->count(),
                    'per_page' => $pharmacies->perPage(),
                    'current_page' => $pharmacies->currentPage(),
                    'total_pages' => $pharmacies->lastPage(),
                    'links' => [
                        'first' => $pharmacies->url(1),
                        'last' => $pharmacies->url($pharmacies->lastPage()),
                        'prev' => $pharmacies->previousPageUrl(),
                        'next' => $pharmacies->nextPageUrl(),
                    ],
                ],
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * 
     * POST /api/pharmacies
     */
    public function store(StorePharmacyRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $pharmacy = $this->pharmacyService->createPharmacy($validated);

            return $this->successResponse(
                201,
                'Pharmacy created successfully',
                new PharmacyResource($pharmacy)
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to create pharmacy',
                $e->getMessage()
            );
        }
    }

    /**
     * Display the specified resource.
     * 
     * GET /api/pharmacies/{id}
     */
    public function show(string $id): JsonResponse
    {
        $pharmacy = $this->pharmacyService->getPharmacy($id);

        if (!$pharmacy) {
            return $this->errorResponse(404, 'Pharmacy not found');
        }

        return $this->successResponse(
            200,
            'Pharmacy retrieved successfully',
            new PharmacyResource($pharmacy)
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * PUT /api/pharmacies/{id}
     */
    public function update(UpdatePharmacyRequest $request, string $id): JsonResponse
    {
        try {
            // First check if pharmacy exists
            $pharmacy = $this->pharmacyService->getPharmacy($id);

            if (!$pharmacy) {
                return $this->errorResponse(404, 'Pharmacy not found');
            }

            // Update the pharmacy
            $validated = $request->validated();
            $this->pharmacyService->updatePharmacy($id, $validated);

            // Get the updated pharmacy
            $updatedPharmacy = $this->pharmacyService->getPharmacy($id);

            return $this->successResponse(
                200,
                'Pharmacy updated successfully',
                new PharmacyResource($updatedPharmacy)
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to update pharmacy',
                $e->getMessage()
            );
        }
    }
    /**
     * Remove the specified resource from storage.
     * 
     * DELETE /api/pharmacies/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $pharmacy = $this->pharmacyService->getPharmacy($id);

            if (!$pharmacy) {
                return $this->errorResponse(404, 'Pharmacy not found');
            }

            $this->pharmacyService->deletePharmacy($id);

            return $this->successResponse(
                200,
                'Pharmacy deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                500,
                'Failed to delete pharmacy',
                $e->getMessage()
            );
        }
    }
}