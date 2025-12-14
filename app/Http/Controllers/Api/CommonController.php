<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\Common;
use App\Models\Post;
use App\Models\Comment;
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

            $liked = false;
            if ($request->user()) {
                $liked = \DB::table('post_likes')
                    ->where('post_id', $postId)
                    ->where('user_id', $request->user()->id)
                    ->exists();
            }

            return Common::successResponse('Chi tiết bài viết', [
                'post' => $post,
                'liked' => $liked,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy chi tiết bài viết', ['error' => $e->getMessage()], 500);
        }
    }

    public function getComments(Request $request, $postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', [], 404);
            }

            $comments = Comment::where('post_id', $postId)
                ->whereNull('reply_to')
                ->with(['user:id,name', 'replies.user:id,name'])
                ->orderBy('created_at', 'desc')
                ->get();

            return Common::successResponse('Danh sách comments', [
                'comments' => $comments,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi lấy comments', ['error' => $e->getMessage()], 500);
        }
    }

    public function createComment(Request $request, $postId)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
                'reply_to' => 'nullable|exists:comments,id',
            ]);

            $post = Post::find($postId);
            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', [], 404);
            }

            $comment = Comment::create([
                'post_id' => $postId,
                'user_id' => $request->user()?->id,
                'content' => $request->input('content'),
                'reply_to' => $request->input('reply_to'),
            ]);

            $comment->load('user:id,name');

            return Common::successResponse('Tạo comment thành công', [
                'comment' => $comment,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi tạo comment', ['error' => $e->getMessage()], 500);
        }
    }

    public function toggleLike(Request $request, $postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', [], 404);
            }

            $userId = $request->user()->id;
            $likeRecord = \DB::table('post_likes')
                ->where('post_id', $postId)
                ->where('user_id', $userId)
                ->first();

            if ($likeRecord) {
                // Unlike
                \DB::table('post_likes')
                    ->where('post_id', $postId)
                    ->where('user_id', $userId)
                    ->delete();
                
                $post->decrement('likes');
                $liked = false;
            } else {
                // Like
                \DB::table('post_likes')->insert([
                    'post_id' => $postId,
                    'user_id' => $userId,
                    'created_at' => now(),
                ]);
                
                $post->increment('likes');
                $liked = true;
            }

            return Common::successResponse($liked ? 'Đã thích bài viết' : 'Đã bỏ thích bài viết', [
                'liked' => $liked,
                'likes' => $post->fresh()->likes,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi xử lý like', ['error' => $e->getMessage()], 500);
        }
    }

    public function incrementView(Request $request, $postId)
    {
        try {
            $post = Post::find($postId);
            if (!$post) {
                return Common::errorResponse('Bài viết không tồn tại', [], 404);
            }

            $post->increment('views');

            return Common::successResponse('Đã tăng view', [
                'views' => $post->fresh()->views,
            ]);
        } catch (\Exception $e) {
            return Common::errorResponse('Lỗi khi tăng view', ['error' => $e->getMessage()], 500);
        }
    }
}