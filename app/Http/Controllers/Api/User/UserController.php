<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Helpers\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return Common::errorResponse('Thông tin đăng nhập không chính xác.', ['auth' => 'Email hoặc mật khẩu sai'], 401);
        }

        // Kiểm tra role user
        if ($user->role !== 'user') {
            return Common::errorResponse('Vui lòng sử dụng trang đăng nhập dành cho bạn.', ['role' => 'Redirect required'], 403);
        }

        // Xóa token cũ và tạo token mới
        $user->tokens()->delete();
        $token = $user->createToken('user-token', ['user'])->plainTextToken;

        return Common::successResponse('Đăng nhập thành công', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return Common::successResponse('Đăng xuất thành công', []);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi đăng xuất', ['error' => $e->getMessage()], 500);
        }   
    }
}