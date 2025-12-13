<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\Common;
use App\Models\Post;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getPostsForHomepage(Request $request)
    {
        try {
            $newsPosts = Post::getNewestPostsByNews(4);
            $eventsPosts = Post::getNewestPostsByEvents(4);
            $clubsPosts = Post::getNewestPostsByClubs(4);
            $studentLifePosts = Post:: getNewestPostsByStudentLife(4);

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
            $newsPosts = Post::getNewestPostsByNews();

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
            $eventsPosts = Post::getNewestPostsByEvents();

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
            $clubsPosts = Post::getNewestPostsByClubs();

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
            $studentLifePosts = Post::getNewestPostsByStudentLife();

            return Common::successResponse('Danh sách bài viết Student Life', [
                'posts' => $studentLifePosts,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy danh sách bài viết Student Life', ['error' => $e->getMessage()], 500);
        }

    }
}