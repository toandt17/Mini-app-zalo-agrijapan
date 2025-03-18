<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use App\Models\QuizQuestion;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Hiển thị danh sách câu hỏi
     */
    public function index()
    {
        $questions = QuizQuestion::with('reward')->orderBy('difficulty_level', 'asc')->get();
        $difficultyLevels = [
            1 => 'Dễ',
            2 => 'Trung bình',
            3 => 'Khó'
        ];

        return view('admin.game.questions.index', compact('questions', 'difficultyLevels'));
    }

    /**
     * Hiển thị form tạo câu hỏi mới
     */
    public function create()
    {
        $rewards = Reward::all();
        $difficultyLevels = [
            1 => 'Dễ',
            2 => 'Trung bình',
            3 => 'Khó'
        ];

        return view('admin.game.questions.create', compact('rewards', 'difficultyLevels'));
    }

    /**
     * Lưu câu hỏi mới vào database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
            'difficulty_level' => 'required|integer|min:1|max:3',
            'points_reward' => 'required|integer|min:0',
            'spin_tickets' => 'required|integer|min:0',
            'reward_id' => 'nullable|exists:rewards,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        QuizQuestion::create($request->all());

        return redirect()->route('admin.questions.index')
            ->with('success', 'Câu hỏi đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết câu hỏi
     */
    public function show($id)
    {
        $question = QuizQuestion::with('reward')->findOrFail($id);
        $difficultyLevels = [
            1 => 'Dễ',
            2 => 'Trung bình',
            3 => 'Khó'
        ];

        return view('admin.game.questions.show', compact('question', 'difficultyLevels'));
    }

    /**
     * Hiển thị form chỉnh sửa câu hỏi
     */
    public function edit($id)
    {
        $question = QuizQuestion::findOrFail($id);
        $rewards = Reward::all();
        $difficultyLevels = [
            1 => 'Dễ',
            2 => 'Trung bình',
            3 => 'Khó'
        ];

        return view('admin.game.questions.edit', compact('question', 'rewards', 'difficultyLevels'));
    }

    /**
     * Cập nhật thông tin câu hỏi
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
            'difficulty_level' => 'required|integer|min:1|max:3',
            'points_reward' => 'required|integer|min:0',
            'spin_tickets' => 'required|integer|min:0',
            'reward_id' => 'nullable|exists:rewards,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $question = QuizQuestion::findOrFail($id);
        $question->update($request->all());

        return redirect()->route('admin.questions.index')
            ->with('success', 'Câu hỏi đã được cập nhật thành công!');
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy($id)
    {
        $question = QuizQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Câu hỏi đã được xóa thành công!');
    }

    /**
     * Lọc câu hỏi theo cấp độ khó
     */
    public function filterByLevel(Request $request)
    {
        $level = $request->level;
        $questions = QuizQuestion::with('reward')
            ->when($level, function($query, $level) {
                return $query->where('difficulty_level', $level);
            })
            ->orderBy('difficulty_level', 'asc')
            ->get();

        $difficultyLevels = [
            1 => 'Dễ',
            2 => 'Trung bình',
            3 => 'Khó'
        ];

        return view('admin.game.questions.index', compact('questions', 'difficultyLevels', 'level'));
    }
}
