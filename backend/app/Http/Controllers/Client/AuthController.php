<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone_number' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
        ], [
            'email.unique' => 'Email này đã được đăng ký sử dụng.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $request->password, // model cast 'hashed' tự động hash
            'status' => 1,
        ]);

        $token = $customer->createToken('customer_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Customer registered successfully',
            'token' => $token,
            'user' => $customer,
        ], 201);
    }   
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first()
            ], 422);
        }

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.',
            ], 401);
        }

        if ($customer->status !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản của bạn đã bị khóa.',
            ], 403);
        }

        $token = $customer->createToken('customer_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Customer logged in successfully',
            'token' => $token,
            'user' => $customer,
        ], 200);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer logged out successfully',
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email',
        ], [
            'email.required' => 'Vui lòng cung cấp email.',
            'email.email' => 'Email không hợp lệ.',
            'email.exists' => 'Email này chưa được đăng ký trong hệ thống.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Generate 6 digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Save to DB
        DB::table('customer_password_otps')->insert([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send Email
        try {
            Mail::to($request->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi gửi email: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã OTP đã được gửi về email của bạn.'
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ], [
            'otp_code.required' => 'Mã OTP không được để trống.',
            'otp_code.size' => 'Mã OTP phải có đúng 6 chữ số.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Find verification record
        $otpRecord = DB::table('customer_password_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp_code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không hợp lệ hoặc đã hết hạn.'
            ], 422);
        }

        // Mark OTP as used
        DB::table('customer_password_otps')
            ->where('id', $otpRecord->id)
            ->update(['used' => true]);

        // Generate temporary reset token (stored in Cache for 10 minutes)
        $resetToken = Str::random(64);
        Cache::put('reset_token_' . $resetToken, $request->email, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'reset_token' => $resetToken
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Mật khẩu mới không được để trống.',
            'password.min' => 'Mật khẩu phải chứa ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Get email from token
        $email = Cache::get('reset_token_' . $request->token);

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Token xác thực đã hết hạn hoặc không hợp lệ.'
            ], 422);
        }

        // Update password
        $customer = Customer::where('email', $email)->first();
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng.'
            ], 404);
        }

        $customer->update([
            'password' => $request->password // model cast 'hashed' tự động hash
        ]);

        // Clear Cache
        Cache::forget('reset_token_' . $request->token);

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu của bạn đã được cập nhật thành công.'
        ], 200);
    }
}
