<?php

namespace App\Http\Controllers\Api\Writer;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
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
        try {
            $writer = $request->user();

            $posts = Post::where('writer_id', $writer->id)
                ->select('id', 'title', 'status', 'views', 'likes', 'created_at', 'category')
                ->orderBy('created_at', 'desc')
                ->get();

            return Common::successResponse('Danh sách bài viết của tác giả', ['posts' => $posts]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết', ['error' => $e->getMessage()], 500);
        }
    }

    public function getPostDetail(Request $request, $postId) {
        $writer = $request->user();

        $post = Post::getPostById($postId);

        if (!$post || $post->writer_id !== $writer->id) {
            return Common::errorResponse('Bài viết không tồn tại hoặc bạn không có quyền truy cập.', [], 404);
        }

        return Common::successResponse('Chi tiết bài viết', ['post' => $post]);
    }

    public function deletePost(Request $request, $postId)
    {
        try {
            $writer = $request->user();
            $post = Post::find($postId);

            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', ['post' => 'Not Found'], 404);
            }

            if ($post->writer_id !== $writer->id) {
                return Common::errorResponse('Bạn không có quyền xóa bài viết này', ['post' => 'Forbidden'], 403);
            }

            $post->delete();

            return Common::successResponse('Xóa bài viết thành công', []);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi xóa bài viết', ['error' => $e->getMessage()], 500);
        }
    }

    public function uploadFeaturedImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            ], [
                'image.required' => 'Vui lòng chọn ảnh',
                'image.image' => 'File phải là ảnh',
                'image.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, webp',
                'image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            ]);

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('posts/featured', $filename, 'public');

            return Common::successResponse('Upload ảnh thành công', [
                'url' => asset('storage/' . $path),
                'path' => $path,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Common::errorResponse('Lỗi validate', $e->errors(), 422);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi upload ảnh', ['error' => $e->getMessage()], 500);
        }
    }

    public function uploadEditorImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:5120',
            ], [
                'image.required' => 'Vui lòng chọn ảnh',
                'image.image' => 'File phải là ảnh',
                'image.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, webp, gif',
                'image.max' => 'Kích thước ảnh không được vượt quá 5MB',
            ]);

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('posts/content', $filename, 'public');

            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => asset('storage/' . $path),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => 0,
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadPdf(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:pdf|max:10240', // Max 10MB
            ], [
                'file.required' => 'Vui lòng chọn file PDF',
                'file.mimes' => 'File phải có định dạng PDF',
                'file.max' => 'Kích thước file không được vượt quá 10MB',
            ]);

            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('posts/documents', $filename, 'public');

            return Common::successResponse('Upload file PDF thành công', [
                'url' => asset('storage/' . $path),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Common::errorResponse('Lỗi validate', $e->errors(), 422);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi upload file', ['error' => $e->getMessage()], 500);
        }
    }
}