<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Reward;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get reward IDs for random assignment
        $rewardIds = Reward::pluck('id')->toArray();

        $missions = [
            // Easy missions
            [
                'name' => 'Đăng nhập hàng ngày',
                'description' => 'Đăng nhập vào ứng dụng mỗi ngày để nhận thưởng',
                'points_reward' => 5,
                'spin_tickets' => 1,
                'reward_id' => null,
                'action_required' => 'login_daily',
            ],
            [
                'name' => 'Hoàn thành hồ sơ cá nhân',
                'description' => 'Cập nhật đầy đủ thông tin trong hồ sơ cá nhân của bạn',
                'points_reward' => 10,
                'spin_tickets' => 1,
                'reward_id' => null,
                'action_required' => 'complete_profile',
            ],
            [
                'name' => 'Theo dõi fanpage AgriJapan',
                'description' => 'Theo dõi fanpage chính thức của AgriJapan trên Zalo',
                'points_reward' => 15,
                'spin_tickets' => 1,
                'reward_id' => null,
                'action_required' => 'follow_zalo_page',
            ],
            [
                'name' => 'Đọc 3 bài viết về nông nghiệp',
                'description' => 'Đọc ít nhất 3 bài viết trong mục Kiến thức nông nghiệp',
                'points_reward' => 15,
                'spin_tickets' => 1,
                'reward_id' => null,
                'action_required' => 'read_articles',
            ],
            [
                'name' => 'Xem video hướng dẫn trên YouTube',
                'description' => 'Xem video hướng dẫn kỹ thuật trồng trọt trên kênh YouTube AgriJapan',
                'points_reward' => 20,
                'spin_tickets' => 1,
                'reward_id' => null,
                'action_required' => 'watch_youtube',
            ],
            [
                'name' => 'Theo dõi kênh TikTok AgriJapan',
                'description' => 'Theo dõi kênh TikTok chính thức của AgriJapan để cập nhật tin tức mới nhất',
                'points_reward' => 25,
                'spin_tickets' => 2,
                'reward_id' => null,
                'action_required' => 'follow_tiktok',
            ],

            // Medium missions
            [
                'name' => 'Trả lời đúng 5 câu hỏi trắc nghiệm',
                'description' => 'Hoàn thành 5 câu hỏi trắc nghiệm với độ chính xác 80%',
                'points_reward' => 30,
                'spin_tickets' => 2,
                'reward_id' => null,
                'action_required' => 'quiz_completion',
            ],
            [
                'name' => 'Chia sẻ ứng dụng với bạn bè',
                'description' => 'Chia sẻ ứng dụng AgriJapan với ít nhất 3 người bạn',
                'points_reward' => 40,
                'spin_tickets' => 2,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
                'action_required' => 'share_app',
            ],
            [
                'name' => 'Chia sẻ bài viết trên Facebook',
                'description' => 'Chia sẻ một bài viết từ ứng dụng lên trang Facebook cá nhân của bạn',
                'points_reward' => 35,
                'spin_tickets' => 2,
                'reward_id' => null,
                'action_required' => 'share_facebook',
            ],
            [
                'name' => 'Bình luận 3 bài viết',
                'description' => 'Để lại bình luận có ích trên 3 bài viết khác nhau',
                'points_reward' => 35,
                'spin_tickets' => 2,
                'reward_id' => null,
                'action_required' => 'comment_posts',
            ],
            [
                'name' => 'Đăng nhập 7 ngày liên tiếp',
                'description' => 'Đăng nhập vào ứng dụng mỗi ngày trong 7 ngày liên tiếp',
                'points_reward' => 50,
                'spin_tickets' => 3,
                'reward_id' => null,
                'action_required' => 'login_streak',
            ],

            // Hard missions
            [
                'name' => 'Hoàn thành khảo sát nông nghiệp',
                'description' => 'Trả lời đầy đủ bảng khảo sát về kiến thức nông nghiệp',
                'points_reward' => 80,
                'spin_tickets' => 5,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
                'action_required' => 'complete_survey',
            ],
            [
                'name' => 'Tham gia livestream về kỹ thuật trồng trọt',
                'description' => 'Tham gia buổi livestream và tương tác với chuyên gia trong ít nhất 20 phút',
                'points_reward' => 90,
                'spin_tickets' => 5,
                'reward_id' => null,
                'action_required' => 'join_livestream',
            ],
            [
                'name' => 'Đăng video review sản phẩm trên TikTok',
                'description' => 'Đăng video ngắn đánh giá sản phẩm AgriJapan trên TikTok và gắn thẻ @AgriJapan',
                'points_reward' => 100,
                'spin_tickets' => 6,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
                'action_required' => 'tiktok_review',
            ],
            [
                'name' => 'Đóng góp bài viết cho cộng đồng',
                'description' => 'Viết một bài chia sẻ kinh nghiệm hoặc kiến thức nông nghiệp',
                'points_reward' => 100,
                'spin_tickets' => 5,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
                'action_required' => 'submit_article',
            ],
            [
                'name' => 'Giới thiệu 5 người dùng mới',
                'description' => 'Giới thiệu 5 người dùng mới đăng ký thành công vào hệ thống',
                'points_reward' => 120,
                'spin_tickets' => 8,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
                'action_required' => 'refer_users',
            ],
            [
                'name' => 'Đạt 500 điểm tích lũy',
                'description' => 'Tích lũy tổng cộng 500 điểm từ các hoạt động trong ứng dụng',
                'points_reward' => 150,
                'spin_tickets' => 10,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
                'action_required' => 'reach_points',
            ],
        ];

        foreach ($missions as $mission) {
            Mission::create($mission);
        }
    }
}
