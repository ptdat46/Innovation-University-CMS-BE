<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Post;
use App\Helpers\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return Common::errorResponse('Thông tin đăng nhập không chính xác.', ['auth' => 'Email hoặc mật khẩu sai'], 401);
        }

        // Kiểm tra role admin
        if ($user->role !== 'admin') {
            return Common::errorResponse('Bạn không có quyền truy cập trang Admin.', ['role' => 'Unauthorized'], 403);
        }

        // Xóa token cũ và tạo token mới
        $user->tokens()->delete();
        $token = $user->createToken('admin-token', ['admin'])->plainTextToken;

        return Common::successResponse('Đăng nhập Admin thành công', [
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

    public function getPendingPostsList()
    {
        try {
            $pendingPosts = Post::getPendingPosts();
            if (empty($pendingPosts)) {
                return Common::successResponse('Không có bài viết chờ duyệt', ['posts' => []]);
            }
            return Common::successResponse('Danh sách bài viết chờ duyệt', ['posts' => $pendingPosts]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết chờ duyệt', ['error' => $e->getMessage()], 500);
        }
    }

    public function getPostDetails($postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', ['post' => 'Not Found'], 404);
            }
            return Common::successResponse('Chi tiết bài viết', ['post' => $post]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy chi tiết bài viết', ['error' => $e->getMessage()], 500);
        }
    }

    public function approvePost($postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', ['post' => 'Not Found'], 404);
            }
            $post->status = 'posted';
            $post->save();

            return Common::successResponse('Duyệt bài viết thành công', ['post' => $post]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi duyệt bài viết', ['error' => $e->getMessage()], 500);
        }
    }
}