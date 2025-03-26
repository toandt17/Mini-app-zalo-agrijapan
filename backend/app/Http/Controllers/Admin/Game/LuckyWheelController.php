<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use App\Models\SpinWheel;
use App\Repositories\Spin\SpinInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LuckyWheelController extends Controller
{
    /**
     * @var SpinInterface
     */
    protected $spinRepository;

    /**
     * LuckyWheelController constructor.
     *
     * @param SpinInterface $spinRepository
     */
    public function __construct(SpinInterface $spinRepository)
    {
        $this->spinRepository = $spinRepository;
    }

    /**
     * Hiển thị trang quản lý vòng quay
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $prizes = $this->spinRepository->getAllPrizes();
        $statistics = $this->spinRepository->getSpinStatistics();

        return view('admin.game.lucky_wheel.index', compact('prizes', 'statistics'));
    }

    /**
     * Hiển thị form tạo giải thưởng mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.game.lucky_wheel.create');
    }

    /**
     * Lưu giải thưởng mới vào database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prize_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'probability' => 'required|numeric|min:0|max:100',
            'remaining_quantity' => 'required|integer|min:0',
            'has_reward' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|integer|min:0|max:3',
        ]);

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('prizes', 'public');
            $validated['image'] = $imagePath;
        }

        // Format probability
        $validated['probability'] = (float) $validated['probability'];

        // Set default for has_reward
        $validated['has_reward'] = $request->has('has_reward') ? 1 : 0;

        $prize = $this->spinRepository->createPrize($validated);

        return redirect()->route('admin.lucky_wheel.index')
            ->with('success', 'Đã thêm giải thưởng thành công!');
    }

    /**
     * Hiển thị form sửa giải thưởng
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $prize = $this->spinRepository->getPrizeById($id);

        if (!$prize) {
            return redirect()->route('admin.lucky_wheel.index')
                ->with('error', 'Không tìm thấy giải thưởng!');
        }

        return view('admin.game.lucky_wheel.edit', compact('prize'));
    }

    /**
     * Cập nhật thông tin giải thưởng
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $prize = $this->spinRepository->getPrizeById($id);

        if (!$prize) {
            return redirect()->route('admin.lucky_wheel.index')
                ->with('error', 'Không tìm thấy giải thưởng!');
        }

        $validated = $request->validate([
            'prize_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'probability' => 'required|numeric|min:0|max:100',
            'remaining_quantity' => 'required|integer|min:0',
            'has_reward' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean',
            'category' => 'required|integer|min:0|max:3',
        ]);

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($prize->image) {
                Storage::disk('public')->delete($prize->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('prizes', 'public');
            $validated['image'] = $imagePath;
        }
        // Xử lý khi người dùng muốn xóa ảnh mà không tải lên ảnh mới
        else if ($request->has('remove_image') && $prize->image) {
            Storage::disk('public')->delete($prize->image);
            $validated['image'] = null;
        }

        // Format probability
        $validated['probability'] = (float) $validated['probability'];

        // Set default for has_reward
        $validated['has_reward'] = $request->has('has_reward') ? 1 : 0;

        // Remove remove_image from validated data
        if (isset($validated['remove_image'])) {
            unset($validated['remove_image']);
        }

        $this->spinRepository->updatePrize($id, $validated);

        return redirect()->route('admin.lucky_wheel.index')
            ->with('success', 'Đã cập nhật giải thưởng thành công!');
    }

    /**
     * Xóa giải thưởng
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = $this->spinRepository->getPrizeById($id);

        if (!$prize) {
            return redirect()->route('admin.lucky_wheel.index')
                ->with('error', 'Không tìm thấy giải thưởng!');
        }

        // Xóa ảnh liên quan
        if ($prize->image && Str::startsWith($prize->image, 'storage/')) {
            $imagePath = str_replace('storage/', 'public/', $prize->image);
            Storage::delete($imagePath);
        }

        $this->spinRepository->deletePrize($id);

        return redirect()->route('admin.lucky_wheel.index')
            ->with('success', 'Đã xóa giải thưởng thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết giải thưởng
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $prize = $this->spinRepository->getPrizeById($id);

        if (!$prize) {
            return redirect()->route('admin.lucky_wheel.index')
                ->with('error', 'Không tìm thấy giải thưởng!');
        }

        // Lấy số người đã trúng giải này
        $winnersCount = $prize->userSpins()->count();

        return view('admin.game.lucky_wheel.show', compact('prize', 'winnersCount'));
    }
}
