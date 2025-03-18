<?php

namespace App\Http\Controllers\Admin\Reward;

use App\Http\Controllers\Controller;
use App\Repositories\Reward\RewardInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RewardController extends Controller
{
    protected $rewardRepo;

    public function __construct(RewardInterface $rewardRepo)
    {
        $this->rewardRepo = $rewardRepo;
    }

    /**
     * Hiển thị danh sách quà tặng
     */
    public function index()
    {
        $rewards = $this->rewardRepo->getAll();
        return view('admin.game.rewards.index', compact('rewards'));
    }

    /**
     * Hiển thị form tạo quà tặng mới
     */
    public function create()
    {
        return view('admin.game.rewards.create');
    }

    /**
     * Lưu quà tặng mới vào database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->rewardRepo->create($request->all());

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Quà tặng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết quà tặng
     */
    public function show($id)
    {
        $reward = $this->rewardRepo->findById($id);
        return view('admin.game.rewards.show', compact('reward'));
    }

    /**
     * Hiển thị form chỉnh sửa quà tặng
     */
    public function edit($id)
    {
        $reward = $this->rewardRepo->findById($id);
        return view('admin.game.rewards.edit', compact('reward'));
    }

    /**
     * Cập nhật thông tin quà tặng
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $this->rewardRepo->update($id, $request->all());

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Quà tặng đã được cập nhật thành công!');
    }

    /**
     * Xóa quà tặng
     */
    public function destroy($id)
    {
        $this->rewardRepo->delete($id);

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Quà tặng đã được xóa thành công!');
    }

    /**
     * Tìm kiếm quà tặng
     */
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $rewards = $this->rewardRepo->search($keyword);

        return view('admin.game.rewards.index', compact('rewards', 'keyword'));
    }
}
