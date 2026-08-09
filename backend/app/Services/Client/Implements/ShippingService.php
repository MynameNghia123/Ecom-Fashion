<?php
namespace App\Services\Client\Implements;

use Illuminate\Support\Facades\Http;

class ShippingService
{
    private string $baseUrl;
    private string $token;
    private int    $shopId;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ghn.url'), '/');
        $this->token   = config('services.ghn.token');
        $this->shopId  = (int) config('services.ghn.shop_id');
    }

    /** Lấy danh sách tỉnh/thành từ GHN */
    public function getProvinces(): array
    {
        $res = Http::withHeaders(['token' => $this->token])
            ->get("{$this->baseUrl}/master-data/province");

        return $res->successful() ? ($res->json('data') ?? []) : [];
    }

    /** Lấy danh sách quận/huyện theo tỉnh */
    public function getDistricts(int $provinceId): array
    {
        $res = Http::withHeaders(['token' => $this->token])
            ->get("{$this->baseUrl}/master-data/district", [
                'province_id' => $provinceId,
            ]);

        return $res->successful() ? ($res->json('data') ?? []) : [];
    }

    /** Lấy danh sách phường/xã theo quận */
    public function getWards(int $districtId): array
    {
        $res = Http::withHeaders(['token' => $this->token])
            ->get("{$this->baseUrl}/master-data/ward", [
                'district_id' => $districtId,
            ]);

        return $res->successful() ? ($res->json('data') ?? []) : [];
    }

    /** Lấy dịch vụ vận chuyển khả dụng */
    public function getAvailableServices(int $toDistrictId): array
    {
        $res = Http::withHeaders(['token' => $this->token])
            ->post("{$this->baseUrl}/v2/shipping-order/available-services", [
                'shop_id'          => $this->shopId,
                'from_district'    => 0,   // sẽ tự resolve theo shop_id
                'to_district'      => $toDistrictId,
            ]);

        return $res->successful() ? ($res->json('data') ?? []) : [];
    }

    /** Tính phí vận chuyển theo GHN */
    public function calculateFee(int $districtId, string $wardCode, int $serviceId, int $weightGram = 500): array
    {
        $res = Http::withHeaders([
            'token'   => $this->token,
            'shop_id' => $this->shopId,
        ])->post("{$this->baseUrl}/v2/shipping-order/fee", [
            'service_id'       => $serviceId,
            'to_district_id'   => $districtId,
            'to_ward_code'     => $wardCode,
            'weight'           => $weightGram,
            'length'           => 20,
            'width'            => 15,
            'height'           => 5,
        ]);

        if ($res->successful() && isset($res->json('data')['total'])) {
            return [
                'success'    => true,
                'total'      => $res->json('data.total'),
                'service_fee' => $res->json('data.service_fee'),
            ];
        }

        return [
            'success' => false,
            'message' => $res->json('message') ?? 'Không thể tính phí vận chuyển',
        ];
    }
}
