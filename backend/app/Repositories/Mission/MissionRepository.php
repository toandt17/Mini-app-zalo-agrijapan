<?php

namespace App\Repositories\Mission;

use App\Models\Mission;
use App\Models\UserMission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MissionRepository implements MissionInterface
{
    /**
     * Get all missions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMissions()
    {
        return Mission::with('reward')->get();
    }

    /**
     * Get mission by ID
     *
     * @param int $id
     * @return \App\Models\Mission|null
     */
    public function getMissionById($id)
    {
        return Mission::with('reward')->find($id);
    }

    /**
     * Create a new mission
     *
     * @param array $data
     * @return \App\Models\Mission
     */
    public function createMission(array $data)
    {
        return Mission::create($data);
    }

    /**
     * Update mission
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateMission($id, array $data)
    {
        $mission = $this->getMissionById($id);
        if (!$mission) {
            return false;
        }

        return $mission->update($data);
    }

    /**
     * Delete mission
     *
     * @param int $id
     * @return bool
     */
    public function deleteMission($id)
    {
        $mission = $this->getMissionById($id);
        if (!$mission) {
            return false;
        }

        return $mission->delete();
    }

    /**
     * Get missions by difficulty level
     *
     * @param string $level
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMissionsByDifficultyLevel($level)
    {
        return Mission::where('difficulty_level', $level)
                     ->with('reward')
                     ->get();
    }

    /**
     * Get missions with completion statistics
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMissionsWithStats()
    {
        $missions = Mission::withCount('userMissions as completion_count')
                         ->with('reward')
                         ->get();

        foreach ($missions as $mission) {
            // Calculate completion percentage
            $mission->completion_percentage = $mission->userMissions()->count() > 0
                ? round(($mission->completion_count / DB::table('users')->count()) * 100, 2)
                : 0;
        }

        return $missions;
    }

    /**
     * Mark mission as completed for a user
     *
     * @param int $userId
     * @param int $missionId
     * @param int $spinTicketsEarned
     * @return \App\Models\UserMission
     */
    public function completeMission($userId, $missionId, $spinTicketsEarned = 0)
    {
        // Check if user already completed this mission
        $existingCompletion = UserMission::where('user_id', $userId)
                                       ->where('mission_id', $missionId)
                                       ->first();

        if ($existingCompletion) {
            return $existingCompletion;
        }

        // Create new completion record
        return UserMission::create([
            'user_id' => $userId,
            'mission_id' => $missionId,
            'completed_at' => Carbon::now(),
            'spin_tickets_earned' => $spinTicketsEarned
        ]);
    }

    /**
     * Get completed missions for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCompletedMissions($userId)
    {
        return Mission::whereHas('userMissions', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['reward', 'userMissions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();
    }
}
