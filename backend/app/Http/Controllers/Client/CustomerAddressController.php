<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CustomerAddress\CustomerAddressRequest;
use App\Services\Client\Interfaces\CustomerAddressServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    public function __construct(private readonly CustomerAddressServiceInterface $addressService) {}

    /**
     * GET /client/addresses — Lấy danh sách địa chỉ của khách hàng.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $this->addressService->getAddresses($customer->id),
        ]);
    }

    /**
     * POST /client/addresses — Thêm mới một địa chỉ.
     */
    public function store(CustomerAddressRequest $request): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();
        $validated['is_default'] = $request->input('is_default', false);

        $result = $this->addressService->addAddress($customer->id, $validated);

        if (! $result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 500);
        }

        return response()->json($result, 201);
    }

    /**
     * PUT /client/addresses/{id} — Cập nhật một địa chỉ.
     */
    public function update(CustomerAddressRequest $request, $id): JsonResponse
    {
        $customer = Auth::user();
        $validated = $request->validated();
        if ($request->has('is_default')) {
            $validated['is_default'] = $request->input('is_default');
        }

        $result = $this->addressService->updateAddress($customer->id, $id, $validated);

        if (! $result['success']) {
            $status = str_contains($result['message'], 'Không tìm thấy') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $status);
        }

        return response()->json($result);
    }

    /**
     * DELETE /client/addresses/{id} — Xóa một địa chỉ.
     */
    public function destroy($id): JsonResponse
    {
        $customer = Auth::user();

        $result = $this->addressService->deleteAddress($customer->id, $id);

        if (! $result['success']) {
            $status = str_contains($result['message'], 'Không tìm thấy') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $status);
        }

        return response()->json($result);
    }

    /**
     * PATCH /client/addresses/{id}/default — Đặt làm địa chỉ mặc định.
     */
    public function setDefault($id): JsonResponse
    {
        $customer = Auth::user();

        $result = $this->addressService->setDefaultAddress($customer->id, $id);

        if (! $result['success']) {
            $status = str_contains($result['message'], 'Không tìm thấy') ? 404 : 500;

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], $status);
        }

        return response()->json($result);
    }
}
