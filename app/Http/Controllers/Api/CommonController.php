<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\Common;
use App\Models\Post;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getPostsForHomepage(Request $request)
    {
        try {
            $newsPosts = Post::getNewestPostsByNews(4)->get();
            $eventsPosts = Post::getNewestPostsByEvents(4)->get();
            $clubsPosts = Post::getNewestPostsByClubs(4)->get();
            $studentLifePosts = Post:: getNewestPostsByStudentLife(4)->get();

            return Common::successResponse('Danh sách bài viết cho trang chủ', [
                'posts' => [
                    'news' => $newsPosts,
                    'events' => $eventsPosts,
                    'clubs' => $clubsPosts,
                    'student_life' => $studentLifePosts,
                ]
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết', ['error' => $e->getMessage()], 500);
        }
    }

    public function getAllNewsPosts(Request $request)
    {
        try {
            $newsPosts = Post::getNewestPostsByNews()
                ->select('id', 'title', 'excerpt', 'featured_image', 'post_day', 'category', 'views', 'likes', 'writer_id')
                ->paginate(10);

            return Common::successResponse('Danh sách bài viết News', [
                'posts' => $newsPosts,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết News', ['error' => $e->getMessage()], 500);
        }
    }

    public function getAllEventsPosts(Request $request)
    {
        try {
            $eventsPosts = Post::getNewestPostsByEvents()
                ->select('id', 'title', 'excerpt', 'featured_image', 'post_day', 'category', 'views', 'likes', 'writer_id')
                ->paginate(10);

            return Common::successResponse('Danh sách bài viết Events', [
                'posts' => $eventsPosts,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết Events', ['error' => $e->getMessage()], 500);
        }
    }

    public function getAllClubsPosts(Request $request)
    {
        try {
            $clubsPosts = Post::getNewestPostsByClubs()
                ->select('id', 'title', 'excerpt', 'featured_image', 'post_day', 'category', 'views', 'likes', 'writer_id')
                ->paginate(10);

            return Common::successResponse('Danh sách bài viết Clubs', [
                'posts' => $clubsPosts,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết Clubs', ['error' => $e->getMessage()], 500);
        }
    }

    public function getAllStudentLifePosts(Request $request) 
    {
        try {
            $studentLifePosts = Post::getNewestPostsByStudentLife()
                ->select('id', 'title', 'excerpt', 'featured_image', 'post_day', 'category', 'views', 'likes', 'writer_id')
                ->paginate(10);

            return Common::successResponse('Danh sách bài viết Student Life', [
                'posts' => $studentLifePosts,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết Student Life', ['error' => $e->getMessage()], 500);
        }
    }

    public function getPostDetails(Request $request, $postId)
    {
        try {
            if (!is_numeric($postId) || (int)$postId <= 0) {
                return Common::errorResponse('ID bài viết không hợp lệ. ID phải là số nguyên', [], 400);
            }
            
            $postId = (int)$postId;
            $post = Post::getPostById($postId)->published()->first();

            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', [], 404);
            }

            return Common::successResponse('Chi tiết bài viết', [
                'post' => $post,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy chi tiết bài viết', ['error' => $e->getMessage()], 500);
        }
    }
}