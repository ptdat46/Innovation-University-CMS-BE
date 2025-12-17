<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have users with writer role
        $writers = User::where('role', 'writer')->get();
        
        if ($writers->isEmpty()) {
            // Create some writers if none exist
            $writers = User::factory()->count(3)->create(['role' => 'writer']);
        }

        $categories = ['news', 'events', 'clubs', 'student-life'];
        $statuses = ['posted', 'pending'];

        $posts = [
            [
                'title' => 'Đại học Đổi mới Sáng tạo công bố Phòng thí nghiệm AI mới',
                'excerpt' => 'Trường đại học công bố cơ sở nghiên cứu trí tuệ nhân tạo hiện đại để thúc đẩy công nghệ tiên tiến.',
                'category' => 'news',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/3B82F6/FFFFFF?text=Phong+thi+nghiem+AI',
            ],
            [
                'title' => 'Lễ hội Công nghệ thường niên 2025: Đăng ký ngay!',
                'excerpt' => 'Tham gia cùng chúng tôi trong ba ngày đổi mới sáng tạo, hội thảo và kết nối mạng lưới tại sự kiện công nghệ hàng đầu.',
                'category' => 'events',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/10B981/FFFFFF?text=Le+hoi+Cong+nghe',
            ],
            [
                'title' => 'Câu lạc bộ Robotics giành Vô địch Toàn quốc',
                'excerpt' => 'Đội Robotics tài năng của chúng ta mang về huy chương vàng từ Cuộc thi Robotics Quốc gia.',
                'category' => 'clubs',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/F59E0B/FFFFFF?text=Robotics+Vo+dich',
            ],
            [
                'title' => 'Một ngày trong cuộc sống sinh viên Khoa học Máy tính',
                'excerpt' => 'Theo dõi Nguyễn Minh Anh khi cô điều hướng các lớp học, dự án lập trình và đời sống sinh viên tại Đại học Đổi mới Sáng tạo.',
                'category' => 'student-life',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/8B5CF6/FFFFFF?text=Doi+song+sinh+vien',
            ],
            [
                'title' => 'Chương trình Học bổng mới cho Sinh viên Quốc tế',
                'excerpt' => 'Đại học Đổi mới Sáng tạo ra mắt sáng kiến học bổng toàn diện để hỗ trợ sinh viên tài năng trên toàn thế giới.',
                'category' => 'news',
                'status' => 'pending',
                'featured_image' => 'https://via.placeholder.com/800x450/EC4899/FFFFFF?text=Hoc+bong',
            ],
            [
                'title' => 'Hackathon 2025: Lập trình vì Sự thay đổi',
                'excerpt' => '48 giờ lập trình, sáng tạo và hợp tác. Xây dựng các giải pháp tạo ra sự khác biệt.',
                'category' => 'events',
                'status' => 'pending',
                'featured_image' => 'https://via.placeholder.com/800x450/EF4444/FFFFFF?text=Hackathon',
            ],
            [
                'title' => 'Triển lãm Câu lạc bộ Nhiếp ảnh khai mạc tuần này',
                'excerpt' => 'Các nhiếp ảnh gia sinh viên trưng bày những tác phẩm xuất sắc nhất trong triển lãm mùa xuân thường niên tại phòng trưng bày khuôn viên.',
                'category' => 'clubs',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/06B6D4/FFFFFF?text=Trien+lam+Anh',
            ],
            [
                'title' => 'Sáng kiến Bền vững Khuôn viên cho thấy Kết quả',
                'excerpt' => 'Đại học Đổi mới Sáng tạo giảm 30% lượng khí thải carbon thông qua các chương trình xanh do sinh viên lãnh đạo.',
                'category' => 'student-life',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/10B981/FFFFFF?text=Ben+vung',
            ],
            [
                'title' => 'Giáo sư Nguyễn Văn Hải nhận Giải thưởng Nghiên cứu danh giá',
                'excerpt' => 'Giảng viên Khoa Khoa học Máy tính được vinh danh vì công trình đột phá về thuật toán máy tính lượng tử.',
                'category' => 'news',
                'status' => 'posted',
                'featured_image' => 'https://via.placeholder.com/800x450/6366F1/FFFFFF?text=Giai+thuong',
            ],
            [
                'title' => 'Hội chợ Du học: Khám phá Cơ hội Toàn cầu',
                'excerpt' => 'Gặp gỡ đại diện từ các trường đại học đối tác trên toàn thế giới và lên kế hoạch cho trải nghiệm quốc tế của bạn.',
                'category' => 'events',
                'status' => 'pending',
                'featured_image' => 'https://via.placeholder.com/800x450/F97316/FFFFFF?text=Du+hoc',
            ],
        ];

        foreach ($posts as $index => $postData) {
            // Sample Editor.js JSON content
            $content = [
                'time' => now()->timestamp,
                'blocks' => [
                    [
                        'type' => 'header',
                        'data' => [
                            'text' => $postData['title'],
                            'level' => 1,
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => $postData['excerpt'],
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Đây là một bước tiến quan trọng trong sự phát triển của trường đại học, mở ra nhiều cơ hội mới cho sinh viên và giảng viên tham gia vào các dự án nghiên cứu hàng đầu. Chúng tôi tin rằng sáng kiến này sẽ đóng góp tích cực cho cộng đồng và ngành công nghệ Việt Nam.',
                        ],
                    ],
                    [
                        'type' => 'list',
                        'data' => [
                            'style' => 'unordered',
                            'items' => [
                                'Tăng cường năng lực nghiên cứu và đào tạo',
                                'Hợp tác với các tổ chức quốc tế hàng đầu',
                                'Tạo cơ hội việc làm cho sinh viên và cựu sinh viên',
                            ],
                        ],
                    ],
                    [
                        'type' => 'paragraph',
                        'data' => [
                            'text' => 'Chúng tôi kêu gọi tất cả sinh viên, giảng viên và đối tác quan tâm hãy tham gia và đóng góp ý kiến để cùng xây dựng một tương lai tốt đẹp hơn. Mọi thông tin chi tiết vui lòng liên hệ qua email hoặc trang web chính thức của trường.',
                        ],
                    ],
                ],
                'version' => '2.28.0',
            ];

            Post::create([
                'title' => $postData['title'],
                'content' => $content,
                'excerpt' => $postData['excerpt'],
                'status' => $postData['status'],
                'post_day' => $postData['status'] === 'posted' 
                    ? now()->subDays(rand(1, 30)) 
                    : now()->addDays(rand(1, 14)),
                'writer_id' => $writers->random()->id,
                'views' => $postData['status'] === 'posted' ? rand(50, 5000) : 0,
                'likes' => $postData['status'] === 'posted' ? rand(5, 500) : 0,
                'featured_image' => "https://img.freepik.com/premium-photo/stack-textbooks-notebooks-with-pen_1061909-3228.jpg",
                'category' => $postData['category'],
                'created_at' => now()->subDays(rand(0, 60)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        $this->command->info('Đã tạo ' . count($posts) . ' bài viết thành công!');
    }
}
