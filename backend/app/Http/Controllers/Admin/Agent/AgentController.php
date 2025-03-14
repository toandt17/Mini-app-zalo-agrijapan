<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Repositories\Agent\AgentInterface;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    protected $agentRepository;

    public function __construct(AgentInterface $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    public function index()
    {
        $agents = $this->agentRepository->getAllAgents();
        return view('admin.agents.index', compact('agents'));
    }

    public function add()
    {
        $provinces = \App\Models\Province::orderBy('name')->get();
        return view('admin.agents.add', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'required|exists:wards,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/agents', $imageName);
            $data['image'] = 'agents/' . $imageName;
        }

        $this->agentRepository->create($data);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Đại lý đã được thêm thành công.');
    }

    public function edit($id)
    {
        $agent = $this->agentRepository->findById($id);
        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        $provinces = \App\Models\Province::orderBy('name')->get();
        return view('admin.agents.edit', compact('agent', 'provinces'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'required|exists:wards,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/agents', $imageName);
            $data['image'] = 'agents/' . $imageName;
        }

        $this->agentRepository->update($id, $data);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Đại lý đã được cập nhật thành công.');
    }

    public function delete($id)
    {
        $this->agentRepository->delete($id);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Đại lý đã được xóa thành công.');
    }
}
