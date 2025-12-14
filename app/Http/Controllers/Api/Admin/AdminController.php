<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\Admin\ListPostsRequest;
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

    public function getAllPosts(ListPostsRequest $request)
    {
        try {
            $query = Post::with('writer:id,name');

            // Filter by status
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter by category
            if ($request->category !== 'all') {
                $query->where('category', $request->category);
            }

            // Filter by writer
            if ($request->writer_id !== 'all' && $request->writer_id) {
                $query->where('writer_id', $request->writer_id);
            }

            // Filter by date range
            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Get total stats before pagination
            $statsQuery = clone $query;
            $totalViews = $statsQuery->sum('views');
            $totalLikes = $statsQuery->sum('likes');
            $totalPosts = $statsQuery->count();

            // Paginate
            $perPage = $request->get('per_page', 10);
            $posts = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return Common::successResponse('Danh sách bài viết', [
                'posts' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
                'stats' => [
                    'total_views' => (int) $totalViews,
                    'total_likes' => (int) $totalLikes,
                    'total_posts' => (int) $totalPosts,
                ],
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết', ['error' => $e->getMessage()], 500);
        }
    }

    public function getWritersList()
    {
        try {
            $writers = User::where('role', 'writer')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();

            return Common::successResponse('Danh sách writers', ['writers' => $writers]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách writers', ['error' => $e->getMessage()], 500);
        }
    }

    public function deletePost($postId)
    {
        try {
            $post = Post::find($postId);

            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', ['post' => 'Not Found'], 404);
            }

            $post->delete();

            return Common::successResponse('Xóa bài viết thành công', []);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi xóa bài viết', ['error' => $e->getMessage()], 500);
        }
    }
}