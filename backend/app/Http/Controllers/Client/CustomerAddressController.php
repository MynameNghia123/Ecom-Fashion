<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerAddressController extends Controller
{
    /**
     * GET /client/addresses — Lấy danh sách địa chỉ của khách hàng.
     */
    public function index(): JsonResponse
    {
        $customer = Auth::user();
        $addresses = CustomerAddress::where('customer_id', $customer->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $addresses,
        ]);
    }

    /**
     * POST /client/addresses — Thêm mới một địa chỉ.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'province'       => 'required|string|max:255',
            'district'       => 'required|string|max:255',
            'ward'           => 'required|string|max:255',
            'detail_address' => 'required|string|max:500',
            'is_default'     => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $customer = Auth::user();

        DB::beginTransaction();
        try {
            $isDefault = $request->input('is_default', false);

            $addressCount = CustomerAddress::where('customer_id', $customer->id)->count();
            if ($addressCount === 0) {
                $isDefault = true;
            }

            if ($isDefault) {
                CustomerAddress::where('customer_id', $customer->id)
                    ->update(['is_default' => false]);
            }

            $address = CustomerAddress::create([
                'customer_id'    => $customer->id,
                'receiver_name'  => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'province'       => $request->province,
                'district'       => $request->district,
                'ward'           => $request->ward,
                'detail_address' => $request->detail_address,
                'is_default'     => $isDefault,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Thêm địa chỉ mới thành công.',
                'data'    => $address,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm địa chỉ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /client/addresses/{id} — Cập nhật một địa chỉ.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'province'       => 'required|string|max:255',
            'district'       => 'required|string|max:255',
            'ward'           => 'required|string|max:255',
            'detail_address' => 'required|string|max:500',
            'is_default'     => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $customer = Auth::user();
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ.',
            ], 404);
        }

        DB::beginTransaction();
        try {
            $isDefault = $request->input('is_default', $address->is_default);

            if ($isDefault && !$address->is_default) {
                CustomerAddress::where('customer_id', $customer->id)
                    ->update(['is_default' => false]);
            }

            if (!$isDefault && $address->is_default) {
                $hasOther = CustomerAddress::where('customer_id', $customer->id)
                    ->where('id', '!=', $id)
                    ->first();
                if ($hasOther) {
                    $hasOther->update(['is_default' => true]);
                } else {
                    $isDefault = true;
                }
            }

            $address->update([
                'receiver_name'  => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'province'       => $request->province,
                'district'       => $request->district,
                'ward'           => $request->ward,
                'detail_address' => $request->detail_address,
                'is_default'     => $isDefault,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật địa chỉ thành công.',
                'data'    => $address,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật địa chỉ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /client/addresses/{id} — Xóa một địa chỉ.
     */
    public function destroy($id): JsonResponse
    {
        $customer = Auth::user();
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ.',
            ], 404);
        }

        DB::beginTransaction();
        try {
            $wasDefault = $address->is_default;
            $address->delete();

            if ($wasDefault) {
                $nextAddress = CustomerAddress::where('customer_id', $customer->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($nextAddress) {
                    $nextAddress->update(['is_default' => true]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa địa chỉ thành công.',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa địa chỉ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PATCH /client/addresses/{id}/default — Đặt làm địa chỉ mặc định.
     */
    public function setDefault($id): JsonResponse
    {
        $customer = Auth::user();
        $address = CustomerAddress::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy địa chỉ.',
            ], 404);
        }

        DB::beginTransaction();
        try {
            CustomerAddress::where('customer_id', $customer->id)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt địa chỉ mặc định thành công.',
                'data'    => $address,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đặt địa chỉ mặc định: ' . $e->getMessage(),
            ], 500);
        }
    }
}
