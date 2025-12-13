<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\CreatePostRequest;
use App\Models\User;
use App\Helpers\Common;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Services\WriterService;
use Illuminate\Support\Facades\Hash;

class WriterController extends Controller
{
    public function __construct(
        protected WriterService $writerService
    ) {
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return Common::errorResponse('Thông tin đăng nhập không chính xác.', ['auth' => 'Email hoặc mật khẩu sai'], 401);
        }

        // Kiểm tra role writer
        if ($user->role !== 'writer') {
            return Common::errorResponse('Bạn không có quyền truy cập trang Writer.', ['role' => 'Unauthorized'], 403);
        }

        // Xóa token cũ và tạo token mới
        $user->tokens()->delete();
        $token = $user->createToken('writer-token', ['writer'])->plainTextToken;

        return Common::successResponse('Đăng nhập Writer thành công', [
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

    public function createPost(CreatePostRequest $request) {
        try {
        $data = $request->validated();

        $post = Post::create($data);

        return Common::successResponse('Tạo bài viết thành công', ['post' => $post]);
        } catch (\Exception $e) {
            return Common::errorResponse('Tạo bài viết thất bại', ['error' => $e->getMessage()], 500);
        }
    }

    public function getListPosts(Request $request) {
        $writer = $request->user();

        $posts = Post::getPostsByWriter($writer->id);

        return Common::successResponse('Danh sách bài viết của tác giả', ['posts' => $posts]);
    }

    public function getPostDetail(Request $request, $postId) {
        $writer = $request->user();

        $post = Post::getPostById($postId);

        if (!$post || $post->writer_id !== $writer->id) {
            return Common::errorResponse('Bài viết không tồn tại hoặc bạn không có quyền truy cập.', [], 404);
        }

        return Common::successResponse('Chi tiết bài viết', ['post' => $post]);
    }
}