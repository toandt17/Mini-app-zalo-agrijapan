<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Game\GameInterface;

class GameController extends Controller
{
    protected $gameRepository;

    public function __construct(gameInterface $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }
    public function reward(Request $request)
    {
        $data = $request->all();
        $phone = $data['phone'];
        $reward = $data['reward'];
        $message = "Chúc mừng bạn đã nhận được phần thưởng $reward từ chúng tôi!";
        return response()->json([
            'message' => $message
        ]);
    }

    public function index()
{
    $gameLk = $this->gameRepository->getAll();

    // Chỉ lấy các trường cần thiết và bọc trong key "lucky_wheel"
    $formattedData = $gameLk->map(function ($item) {
        return [
            'id' => $item->id,
            'reward' => $item->reward
        ];
    });

    return response()->json(['lucky_wheel' => $formattedData], 200, [], JSON_UNESCAPED_UNICODE);
}

    public function index_lucky()
    {
        $lucky = $this->gameRepository->getAll();
        return view('admin.game.index', compact('lucky'));
    }

    public function add_lucky()
    {
        return view('admin.game.add');
    }




    public function edit_lucky($id)
    {
        $lucky = $this->gameRepository->getById($id);
        if (!$lucky) {
            return redirect()->route('admin.game.index_lucky')->with('error', 'Loại sản phẩm không tồn tại.');
        }
        return view('admin.game.edit_lucky', compact('new_lucky'));
    }

    public function update_lucky(Request $request, $id)
    {
        $validator = $this->gameRepository->validate($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $lucky = $this->gameRepository->getById($id);

        if ($lucky) {
            // Xử lý tải lên ảnh mới nếu có
            if ($request->hasFile('img')) {
                // Xóa ảnh cũ nếu có
                if ($lucky->img) {
                    Storage::disk('public')->delete($lucky->img);
                }
                $img = $request->file('img');
                $imagePath = $img->store('lucky', 'public');
                $data['img'] = $imagePath;
            }

            $this->gameRepository->update($id, $data);
            return redirect()->route('admin.game.index_lucky')->with('success', 'Loại sản phẩm đã được cập nhật thành công.');
        }

        return redirect()->route('admin.game.index_lucky')->with('error', 'Loại sản phẩm không tồn tại.');
    }
}
