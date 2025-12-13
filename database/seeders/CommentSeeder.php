<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::all();
        $users = User::where('role', 'user')->get();

        if ($posts->isEmpty() || $users->isEmpty()) {
            return;
        }

        $vietnameseComments = [
            'Bài viết rất hay và bổ ích!',
            'Cảm ơn admin đã chia sẻ thông tin này.',
            'Mình rất mong chờ sự kiện này!',
            'Thông tin rất hữu ích cho sinh viên chúng mình.',
            'Có thể cho mình biết thêm chi tiết được không ạ?',
            'Tuyệt vời! Mình sẽ tham gia ngay.',
            'Câu lạc bộ này có hoạt động gì thú vị không nhỉ?',
            'Mình đã tham gia và cảm thấy rất tuyệt vời!',
            'Cảm ơn trường đã tổ chức sự kiện ý nghĩa này.',
            'Mình có thể đăng ký tham gia ở đâu vậy?',
            'Bài viết viết rất chi tiết và dễ hiểu.',
            'Hy vọng sẽ có nhiều hoạt động như thế này.',
            'Thật sự rất cảm kích về sự quan tâm của nhà trường.',
            'Nội dung rất phù hợp với sinh viên hiện nay.',
            'Mong được trải nghiệm những hoạt động này sớm!',
        ];

        $replies = [
            'Cảm ơn bạn đã quan tâm!',
            'Mình cũng nghĩ vậy, rất đáng tham gia.',
            'Bạn có thể xem thông tin chi tiết trên website nhé.',
            'Mình cũng đã tham gia rồi, rất thú vị đấy!',
            'Chúc bạn có trải nghiệm tốt!',
            'Đồng ý với bạn luôn!',
            'Cảm ơn bạn đã chia sẻ!',
        ];

        // Seed comments cho từng post
        foreach ($posts as $post) {
            // Random 3-8 comments cho mỗi post
            $commentCount = rand(3, 8);
            
            for ($i = 0; $i < $commentCount; $i++) {
                $user = $users->random();
                $commentText = $vietnameseComments[array_rand($vietnameseComments)];
                
                // Tạo comment gốc
                $comment = Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'reply_to' => null,
                    'content' => $commentText,
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);

                // Random 0-3 replies cho mỗi comment
                $replyCount = rand(0, 3);
                for ($j = 0; $j < $replyCount; $j++) {
                    $replyUser = $users->random();
                    $replyText = $replies[array_rand($replies)];
                    
                    Comment::create([
                        'post_id' => $post->id,
                        'user_id' => $replyUser->id,
                        'reply_to' => $comment->id,
                        'content' => $replyText,
                        'created_at' => now()->subDays(rand(0, 25)),
                    ]);
                }
            }
        }
    }
}
