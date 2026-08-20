<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Upload',
    description: 'Upload / xóa ảnh lên server storage. Ảnh được upload trước khi lưu sản phẩm.'
)]
class UploadController extends Controller
{
    /**
     * Upload một file ảnh lên storage/public/images/{folder}
     * và trả về URL công khai để frontend lưu vào form.
     *
     * POST /admin/upload-image
     * Body (multipart/form-data):
     *   - file: file ảnh (required)
     *   - folder: string thư mục con (optional, default: 'products')
     *
     * Response: { success: true, url: "http://..../storage/images/products/xxx.webp" }
     */
    #[OA\Post(
        path: '/api/admin/upload-image',
        summary: 'Upload một ảnh lên server storage',
        description: "Upload file ảnh lên `storage/app/public/images/{folder}` và trả về URL đầy đủ.\nFile được lưu với tên `uuid.ext` để tránh trùng.\nYêu cầu `php artisan storage:link` đã được chạy.",
        tags: ['Upload'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['file'],
                    properties: [
                        new OA\Property(property: 'file', type: 'string', format: 'binary', description: 'File ảnh (jpeg, png, webp, gif). Tối đa 10MB.'),
                        new OA\Property(property: 'folder', type: 'string', default: 'products', example: 'products', description: 'Thư mục con trong images/. Tối đa 50 ký tự, chỉ chữ cái, số, dấu gạch.'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Upload thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'url', type: 'string', format: 'uri', example: 'http://localhost/storage/images/products/550e8400-e29b-41d4-a716-446655440000.jpg', description: 'URL đầy đủ để hiển thị ảnh'),
                    new OA\Property(property: 'path', type: 'string', example: 'images/products/550e8400-e29b-41d4-a716-446655440000.jpg', description: 'Đường dẫn tương đối (dùng để xóa ảnh sau nếu cần)'),
                ])
            ),
            new OA\Response(response: 422, description: 'Lỗi validate (sai định dạng, quá dung lượng...)'),
        ]
    )]
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|image|mimes:jpeg,png,webp,gif|max:10240', // tối đa 10MB
            'folder' => 'nullable|string|max:50|alpha_dash',
        ]);

        $folder = $request->input('folder', 'products');
        $file = $request->file('file');

        try {
            // Khởi tạo Cloudinary (tự động lấy CLOUDINARY_URL từ env)
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));

            // Upload ảnh lên Cloudinary
            $response = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
            ]);

            // Lấy URL và public_id từ response
            $url = $response['secure_url'];
            $publicId = $response['public_id'];

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $publicId,   // dùng public_id làm path để xóa sau này
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Lỗi upload image: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Lỗi lưu trữ hình ảnh: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Xóa một file ảnh đã upload (dùng khi user bỏ ảnh trước khi lưu SP)
     *
     * DELETE /admin/upload-image
     * Body: { path: "products/xxx" } // đây là public_id của Cloudinary
     */
    #[OA\Delete(
        path: '/api/admin/upload-image',
        summary: 'Xóa ảnh đã upload khỏi storage',
        description: "Xóa file ảnh khỏi disk 'public'. Dùng khi user bỏ ảnh trước khi lưu sản phẩm.\nChỉ cho phép xóa trong thư mục `images/`.",
        tags: ['Upload'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['path'],
                properties: [
                    new OA\Property(property: 'path', type: 'string', example: 'products/550e8400-e29b-41d4-a716-446655440000', description: 'Đường dẫn tương đối nhận từ response upload. Đây là public_id trên Cloudinary.'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Xóa thành công',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'Đã xóa ảnh thành công.'),
                ])
            ),
            new OA\Response(
                response: 422,
                description: 'Đường dẫn không hợp lệ',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: false),
                    new OA\Property(property: 'message', type: 'string', example: 'Đường dẫn không hợp lệ.'),
                ])
            ),
        ]
    )]
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string|max:500',
        ]);

        $publicId = $request->input('path');

        try {
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $cloudinary->uploadApi()->destroy($publicId);
        } catch (\Exception $e) {
            \Log::error('Lỗi xóa image Cloudinary: '.$e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa ảnh thành công.',
        ]);
    }
}
