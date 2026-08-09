<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\Client\Implements\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(private readonly ShippingService $shippingService) {}

    /** GET /api/client/shipping/provinces */
    public function provinces(): JsonResponse
    {
        $data = $this->shippingService->getProvinces();
        return response()->json(['success' => true, 'data' => $data]);
    }

    /** GET /api/client/shipping/districts?province_id=xxx */
    public function districts(Request $request): JsonResponse
    {
        $provinceId = (int) $request->query('province_id', 0);
        if ($provinceId <= 0) {
            return response()->json(['success' => false, 'message' => 'province_id is required'], 422);
        }
        $data = $this->shippingService->getDistricts($provinceId);
        return response()->json(['success' => true, 'data' => $data]);
    }

    /** GET /api/client/shipping/wards?district_id=xxx */
    public function wards(Request $request): JsonResponse
    {
        $districtId = (int) $request->query('district_id', 0);
        if ($districtId <= 0) {
            return response()->json(['success' => false, 'message' => 'district_id is required'], 422);
        }
        $data = $this->shippingService->getWards($districtId);
        return response()->json(['success' => true, 'data' => $data]);
    }

    /** POST /api/client/shipping/fee */
    public function fee(Request $request): JsonResponse
    {
        $request->validate([
            'district_id' => 'required|integer',
            'ward_code'   => 'required|string',
            'service_id'  => 'required|integer',
            'weight'      => 'nullable|integer|min:1',
        ]);

        $result = $this->shippingService->calculateFee(
            (int) $request->district_id,
            (string) $request->ward_code,
            (int) $request->service_id,
            (int) ($request->weight ?? 500)
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /** GET /api/client/shipping/services?district_id=xxx */
    public function services(Request $request): JsonResponse
    {
        $districtId = (int) $request->query('district_id', 0);
        if ($districtId <= 0) {
            return response()->json(['success' => false, 'message' => 'district_id is required'], 422);
        }
        $data = $this->shippingService->getAvailableServices($districtId);
        return response()->json(['success' => true, 'data' => $data]);
    }
}
