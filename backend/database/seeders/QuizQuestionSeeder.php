<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use App\Models\Reward;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get reward IDs for random assignment
        $rewardIds = Reward::pluck('id')->toArray();

        $questions = [
            // Easy questions
            [
                'question' => 'Nhật Bản nằm ở châu lục nào?',
                'option_a' => 'Châu Á',
                'option_b' => 'Châu Âu',
                'option_c' => 'Châu Mỹ',
                'option_d' => 'Châu Phi',
                'correct_answer' => 'a',
                'points_reward' => 10,
                'spin_tickets' => 1,
                'reward_id' => null,
            ],
            [
                'question' => 'Cây lúa thuộc họ thực vật nào?',
                'option_a' => 'Họ Đậu',
                'option_b' => 'Họ Hòa Thảo',
                'option_c' => 'Họ Cúc',
                'option_d' => 'Họ Cà',
                'correct_answer' => 'b',
                'points_reward' => 10,
                'spin_tickets' => 1,
                'reward_id' => null,
            ],
            [
                'question' => 'Loại gạo nào nổi tiếng của Nhật Bản?',
                'option_a' => 'Gạo Basmati',
                'option_b' => 'Gạo Jasmine',
                'option_c' => 'Gạo Koshihikari',
                'option_d' => 'Gạo Arborio',
                'correct_answer' => 'c',
                'points_reward' => 10,
                'spin_tickets' => 1,
                'reward_id' => null,
            ],

            // Medium questions
            [
                'question' => 'Kỹ thuật làm đất nào được sử dụng phổ biến trong nông nghiệp Nhật Bản?',
                'option_a' => 'Cày sâu',
                'option_b' => 'Cày nông',
                'option_c' => 'Không cày đất',
                'option_d' => 'Cày tối thiểu',
                'correct_answer' => 'd',
                'points_reward' => 20,
                'spin_tickets' => 2,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
            ],
            [
                'question' => 'Hệ thống canh tác lúa-cá là sự kết hợp giữa trồng lúa và nuôi loài thủy sản nào?',
                'option_a' => 'Cá rô phi',
                'option_b' => 'Cá chép',
                'option_c' => 'Tôm càng xanh',
                'option_d' => 'Cá trê',
                'correct_answer' => 'b',
                'points_reward' => 20,
                'spin_tickets' => 2,
                'reward_id' => null,
            ],
            [
                'question' => 'Phương pháp bảo quản nông sản nào phổ biến tại Nhật Bản?',
                'option_a' => 'Sấy khô',
                'option_b' => 'Muối chua',
                'option_c' => 'Lên men',
                'option_d' => 'Tất cả các phương pháp trên',
                'correct_answer' => 'd',
                'points_reward' => 20,
                'spin_tickets' => 2,
                'reward_id' => null,
            ],

            // Hard questions
            [
                'question' => 'Kỹ thuật "Fukuoka" trong nông nghiệp Nhật Bản được phát triển bởi ai?',
                'option_a' => 'Masanobu Fukuoka',
                'option_b' => 'Teruo Higa',
                'option_c' => 'Yoshikazu Kawaguchi',
                'option_d' => 'Shiro Nakagawa',
                'correct_answer' => 'a',
                'points_reward' => 50,
                'spin_tickets' => 5,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
            ],
            [
                'question' => 'Công nghệ "Smart Agriculture" của Nhật Bản sử dụng công nghệ nào?',
                'option_a' => 'Internet vạn vật (IoT)',
                'option_b' => 'Trí tuệ nhân tạo (AI)',
                'option_c' => 'Dữ liệu lớn (Big Data)',
                'option_d' => 'Tất cả các công nghệ trên',
                'correct_answer' => 'd',
                'points_reward' => 50,
                'spin_tickets' => 5,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
            ],
            [
                'question' => 'Giống lúa Koshihikari của Nhật Bản được phát triển bằng phương pháp lai tạo nào?',
                'option_a' => 'Lai hữu tính truyền thống',
                'option_b' => 'Biến đổi gen',
                'option_c' => 'Đột biến phóng xạ',
                'option_d' => 'Genome editing',
                'correct_answer' => 'a',
                'points_reward' => 50,
                'spin_tickets' => 5,
                'reward_id' => null,
            ],
            [
                'question' => 'Hệ thống canh tác nào được sử dụng để trồng cây trong nhà kính tại Nhật Bản?',
                'option_a' => 'Thủy canh',
                'option_b' => 'Khí canh',
                'option_c' => 'Canh tác đất',
                'option_d' => 'Cả A và B',
                'correct_answer' => 'd',
                'points_reward' => 50,
                'spin_tickets' => 5,
                'reward_id' => $rewardIds[array_rand($rewardIds)],
            ],
        ];

        foreach ($questions as $question) {
            QuizQuestion::create($question);
        }
    }
}
