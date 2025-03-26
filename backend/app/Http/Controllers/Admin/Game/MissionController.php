<?php

namespace App\Http\Controllers\Admin\Game;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Repositories\Mission\MissionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissionController extends Controller
{
    /**
     * @var MissionInterface
     */
    protected $missionRepository;

    /**
     * MissionController constructor.
     *
     * @param MissionInterface $missionRepository
     */
    public function __construct(MissionInterface $missionRepository)
    {
        $this->missionRepository = $missionRepository;
    }

    /**
     * Display a listing of missions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $missions = $this->missionRepository->getMissionsWithStats();
        return view('admin.game.missions.index', compact('missions'));
    }

    /**
     * Show the form for creating a new mission.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $rewards = Reward::all();

        return view('admin.game.missions.create', compact('rewards'));
    }

    /**
     * Store a newly created mission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'action_required' => 'required|string',
            'action_data' => 'nullable|array',
            'points_reward' => 'required|integer|min:0',
            'spin_tickets' => 'required|integer|min:0',
            'reward_id' => 'nullable|exists:rewards,id',
        ]);

        // Xử lý action_data và chuyển thành chuỗi JSON
        if (isset($validated['action_data'])) {
            $validated['action_data'] = json_encode($validated['action_data']);
        }

        DB::beginTransaction();
        try {
            // Create the mission
            $mission = $this->missionRepository->createMission($validated);

            DB::commit();
            return redirect()->route('admin.missions.index')
                ->with('success', 'Nhiệm vụ đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified mission.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $mission = $this->missionRepository->getMissionById($id);

        if (!$mission) {
            return redirect()->route('admin.missions.index')
                ->with('error', 'Không tìm thấy nhiệm vụ!');
        }

        // Get users who completed this mission
        $completions = $mission->userMissions()
            ->with('user')
            ->orderBy('completed_at', 'desc')
            ->paginate(10);

        return view('admin.game.missions.show', compact('mission', 'completions'));
    }

    /**
     * Show the form for editing the specified mission.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $mission = $this->missionRepository->getMissionById($id);

        if (!$mission) {
            return redirect()->route('admin.missions.index')
                ->with('error', 'Không tìm thấy nhiệm vụ!');
        }

        $rewards = Reward::all();

        return view('admin.game.missions.edit', compact('mission', 'rewards'));
    }

    /**
     * Update the specified mission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $mission = $this->missionRepository->getMissionById($id);

        if (!$mission) {
            return redirect()->route('admin.missions.index')
                ->with('error', 'Không tìm thấy nhiệm vụ!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'action_required' => 'required|string',
            'action_data' => 'nullable|array',
            'points_reward' => 'required|integer|min:0',
            'spin_tickets' => 'required|integer|min:0',
            'reward_id' => 'nullable|exists:rewards,id',
        ]);

        // Xử lý action_data và chuyển thành chuỗi JSON
        if (isset($validated['action_data'])) {
            $validated['action_data'] = json_encode($validated['action_data']);
        }

        DB::beginTransaction();
        try {
            // Update the mission
            $this->missionRepository->updateMission($id, $validated);

            DB::commit();
            return redirect()->route('admin.missions.index')
                ->with('success', 'Nhiệm vụ đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified mission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $mission = $this->missionRepository->getMissionById($id);

        if (!$mission) {
            return redirect()->route('admin.missions.index')
                ->with('error', 'Không tìm thấy nhiệm vụ!');
        }

        // Check if the mission has completions
        if ($mission->userMissions()->count() > 0) {
            return redirect()->route('admin.missions.index')
                ->with('error', 'Không thể xóa nhiệm vụ đã có người hoàn thành!');
        }

        $this->missionRepository->deleteMission($id);

        return redirect()->route('admin.missions.index')
            ->with('success', 'Nhiệm vụ đã được xóa thành công!');
    }
}
