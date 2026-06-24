<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerRequest;
use App\Http\Requests\Admin\Customer\UpdateCustomerRequest;
use App\Http\Resources\Admin\Customer\CustomerResource;
use App\Services\Admin\Interfaces\CustomerServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerServiceInterface $customerService
    ){}

    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->customerService->getList([
            'search'   => $request->query('search'),
            'status'   => $request->query('status'),
            'per_page' => (int) $request->query('per_page', 10),
        ]);

        return response()->json([
            'success' => true,
            'data'    => CustomerResource::collection($paginator->items()),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }

    public function parents() : JsonResponse
    {
        $parents = $this->customerService->getAll();

        return response()->json([
            'success' => true,
            'data'    => CustomerResource::collection($parents),
        ]);
    }

    public function store(CustomerRequest $request)
    {
        $customer = $this->customerService->create($request->validated());
        return response()->json([
            'success' => true,
            'data'    => new CustomerResource($customer),
            'message' => 'Khách hàng đã được thêm thành công.',
        ], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'data'    => new CustomerResource($customer),
        ]);
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $updatedCustomer = $this->customerService->update($customer, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new CustomerResource($updatedCustomer),
            'message' => 'Khách hàng đã được cập nhật thành công.',
        ]);
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer);

        return response()->json([
            'success' => true,
            'message' => 'Khách hàng đã được xóa thành công.',
        ]);
    }
}