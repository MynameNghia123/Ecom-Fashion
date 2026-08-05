<?php
namespace App\Services\Client\Implements;
use App\Models\CustomerAddress;
use App\Repositories\Client\Interfaces\CustomerAddressRepositoryInterface;
use App\Services\Client\Interfaces\CustomerAddressServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class CustomerAddressService implements CustomerAddressServiceInterface
{
    public function __construct(private readonly CustomerAddressRepositoryInterface $repo) {}

    public function getAddresses(int $customerId): Collection
    {
        return $this->repo->getByCustomerId($customerId);
    }

    public function addAddress(int $customerId, array $data): array
    {
        DB::beginTransaction();
        try {
            $isDefault = $data['is_default'] ?? false;

            if ($this->repo->countByCustomerId($customerId) === 0) {
                $isDefault = true;
            }

            if ($isDefault) {
                $this->repo->resetDefaultByCustomerId($customerId);
            }

            $address = $this->repo->create([
                'customer_id'    => $customerId,
                'receiver_name'  => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'province'       => $data['province'],
                'district'       => $data['district'],
                'ward'           => $data['ward'],
                'detail_address' => $data['detail_address'],
                'is_default'     => $isDefault,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Thêm địa chỉ mới thành công.',
                'data'    => $address,
            ];
        } catch (Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi thêm địa chỉ: ' . $e->getMessage(),
            ];
        }
    }

    public function updateAddress(int $customerId, int $id, array $data): array
    {
        $address = $this->repo->findByIdAndCustomerId($id, $customerId);

        if (!$address) {
            return ['success' => false, 'message' => 'Không tìm thấy địa chỉ.'];
        }

        DB::beginTransaction();
        try {
            $isDefault = $data['is_default'] ?? $address->is_default;

            if ($isDefault && !$address->is_default) {
                $this->repo->resetDefaultByCustomerId($customerId);
            }

            if (!$isDefault && $address->is_default) {
                $hasOther = $this->repo->getOtherAddress($customerId, $id);
                if ($hasOther) {
                    $this->repo->update($hasOther, ['is_default' => true]);
                } else {
                    $isDefault = true;
                }
            }

            $this->repo->update($address, [
                'receiver_name'  => $data['receiver_name'],
                'receiver_phone' => $data['receiver_phone'],
                'province'       => $data['province'],
                'district'       => $data['district'],
                'ward'           => $data['ward'],
                'detail_address' => $data['detail_address'],
                'is_default'     => $isDefault,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Cập nhật địa chỉ thành công.',
                'data'    => $address,
            ];
        } catch (Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi cập nhật địa chỉ: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteAddress(int $customerId, int $id): array
    {
        $address = $this->repo->findByIdAndCustomerId($id, $customerId);

        if (!$address) {
            return ['success' => false, 'message' => 'Không tìm thấy địa chỉ.'];
        }

        DB::beginTransaction();
        try {
            $wasDefault = $address->is_default;
            $this->repo->delete($address);

            if ($wasDefault) {
                $nextAddress = $this->repo->getLatestAddress($customerId);
                if ($nextAddress) {
                    $this->repo->update($nextAddress, ['is_default' => true]);
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Xóa địa chỉ thành công.',
            ];
        } catch (Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa địa chỉ: ' . $e->getMessage(),
            ];
        }
    }

    public function setDefaultAddress(int $customerId, int $id): array
    {
        $address = $this->repo->findByIdAndCustomerId($id, $customerId);

        if (!$address) {
            return ['success' => false, 'message' => 'Không tìm thấy địa chỉ.'];
        }

        DB::beginTransaction();
        try {
            $this->repo->resetDefaultByCustomerId($customerId);
            $this->repo->update($address, ['is_default' => true]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Đặt địa chỉ mặc định thành công.',
                'data'    => $address,
            ];
        } catch (Throwable $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Lỗi khi đặt địa chỉ mặc định: ' . $e->getMessage(),
            ];
        }
    }
}
