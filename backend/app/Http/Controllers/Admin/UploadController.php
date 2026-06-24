<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file'   => 'required|file|image|mimes:jpeg,png,webp,gif|max:5120', // tối đa 5MB
            'folder' => 'nullable|string|max:50|alpha_dash',
        ]);

        $folder = $request->input('folder', 'products');
        
        $file = $request->file('file');
        
        // Tạo tên file duy nhất: uuid + đuôi gốc
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename  = Str::uuid() . '.' . $extension;

        // Lưu vào storage/app/public/images/{folder}/{filename}
        // → Accessible qua /storage/images/{folder}/{filename} (sau khi php artisan storage:link)
        $path = $file->storeAs(
            "images/{$folder}",
            $filename,
            'public'
        );

        // Xây dựng URL đầy đủ: APP_URL + /storage/ + path
        $url = rtrim(config('app.url'), '/') . '/storage/' . $path;

        return response()->json([
            'success' => true,
            'url'     => $url,
            'path'    => $path,   // đường dẫn tương đối (để xóa sau nếu cần)
        ], 201);
    }

    /**
     * Xóa một file ảnh đã upload (dùng khi user bỏ ảnh trước khi lưu SP)
     *
     * DELETE /admin/upload-image
     * Body: { path: "images/products/xxx.webp" }
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string|max:500',
        ]);

        $path = $request->input('path');

        // Bảo vệ: chỉ cho phép xóa trong thư mục images/
        if (!str_starts_with($path, 'images/')) {
            return response()->json([
                'success' => false,
                'message' => 'Đường dẫn không hợp lệ.',
            ], 422);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa ảnh thành công.',
        ]);
    }
}
