<?php 
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\Admin\Implements\StaffService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Http\Requests\Admin\Staff\StoreStaffRequest;
use App\Http\Requests\Admin\Staff\UpdateStaffRequest;
use App\Http\Resources\Admin\Staff\StaffResource;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Staffs',
    description: 'Quản lý nhân viên'
)]
class StaffController extends Controller
{
    public function __construct(
        private readonly StaffService $staffService,
    ) {}

    #[OA\Get(
        path: '/api/admin/staffs',
        summary: 'Lấy danh sách nhân viên (có phân trang & lọc)',
        tags: ['Staffs'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Từ khóa tìm kiếm (tên, email, số điện thoại)', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'is_active', in: 'query', description: 'Lọc theo trạng thái (1=active, 0=inactive)', required: false, schema: new OA\Schema(type: 'integer', enum: [0, 1])),
            new OA\Parameter(name: 'per_page', in: 'query', description: 'Số bản ghi mỗi trang (mặc định: 4)', required: false, schema: new OA\Schema(type: 'integer', default: 4)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lấy danh sách thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    new OA\Property(property: 'meta', type: 'object', properties: [
                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                        new OA\Property(property: 'per_page', type: 'integer', example: 4),
                        new OA\Property(property: 'total', type: 'integer', example: 10),
                        new OA\Property(property: 'last_page', type: 'integer', example: 3),
                    ]),
                ])
            ),
        ]
    )]
    public function index(Request $request) : JsonResponse
    {
        $paginator = $this->staffService->getList([
            'search' => $request->query('search'),
            'is_active' => $request->query('is_active'),
            'per_page' => (int) $request->query('per_page', 4),
        ]);

        return response()->json([
            'success'   => true,
            'data'      => StaffResource::collection($paginator->items()),
            'meta'      => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);    
    }

    #[OA\Post(
        path: '/api/admin/staffs',
        summary: 'Tạo nhân viên mới',
        tags: ['Staffs'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['full_name', 'phone_number', 'email', 'password', 'is_active'],
                properties: [
                    new OA\Property(property: 'full_name', type: 'string', example: 'Nguyễn Văn Admin', description: 'Tên nhân viên'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '0901234567'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com', description: 'Email duy nhất'),
                    new OA\Property(property: 'password', type: 'string', example: 'secret123', description: 'Mật khẩu (ít nhất 6 ký tự)'),
                    new OA\Property(property: 'avatar', type: 'string', nullable: true, example: 'https://example.com/avatar.jpg'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true, description: 'Trạng thái hoạt động'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tạo thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Thêm mới nhân viên thành công'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function store(StoreStaffRequest $request) : JsonResponse
    {
        $staff = $this->staffService->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new StaffResource($staff),
            'message' => 'Thêm mới nhân viên thành công',
        ], 201);
    }

    #[OA\Put(
        path: '/api/admin/staffs/{staff}',
        summary: 'Cập nhật thông tin nhân viên',
        tags: ['Staffs'],
        parameters: [
            new OA\Parameter(name: 'staff', in: 'path', description: 'ID của nhân viên cần cập nhật', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['full_name', 'phone_number', 'email', 'is_active'],
                properties: [
                    new OA\Property(property: 'full_name', type: 'string', example: 'Nguyễn Văn B'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '0987654321'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'new@example.com', description: 'Email (ngoại trừ nhân viên hiện tại)'),
                    new OA\Property(property: 'password', type: 'string', nullable: true, example: null, description: 'Nullable, chỉ điền khi muốn đổi mật khẩu'),
                    new OA\Property(property: 'avatar', type: 'string', nullable: true, example: 'https://example.com/new_avatar.jpg'),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cập nhật thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                    new OA\Property(property: 'message', type: 'string', example: 'Cập nhật thông tin nhân viên thành công'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhân viên'),
            new OA\Response(response: 422, description: 'Lỗi validate dữ liệu'),
        ]
    )]
    public function update(UpdateStaffRequest $request, Staff $staff) : JsonResponse
    {
        $staff = $this->staffService->update($staff, $request->validated());

        return response()->json([
            'success' => true,
            'data'    => new StaffResource($staff),
            'message' => 'Cập nhật thông tin nhân viên thành công',
        ]);
    } 
    
    #[OA\Get(
        path: '/api/admin/staffs/{staff}',
        summary: 'Xem chi tiết nhân viên',
        tags: ['Staffs'],
        parameters: [
            new OA\Parameter(name: 'staff', in: 'path', description: 'ID của nhân viên', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'data', type: 'object'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhân viên'),
        ]
    )]
    public function show(Staff $staff) : JsonResponse 
    {
        return response()->json([
            'success' => true,
            'data'    => new StaffResource($staff),
        ]);
    } 

    #[OA\Delete(
        path: '/api/admin/staffs/{staff}',
        summary: 'Xóa nhân viên',
        tags: ['Staffs'],
        parameters: [
            new OA\Parameter(name: 'staff', in: 'path', description: 'ID của nhân viên cần xóa', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Xóa nhân viên thành công'),
                ])
            ),
            new OA\Response(response: 404, description: 'Không tìm thấy nhân viên'),
        ]
    )]
    public function destroy(Staff $staff) : JsonResponse
    {
        $this->staffService->delete($staff);

        return response()->json([
            'success' => true,
            'message' => 'Xóa nhân viên thành công',
        ]);
    }
}