<?php

namespace App\Repositories\Mission;

interface MissionInterface
{
    /**
     * Get all missions
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMissions();

    /**
     * Get mission by ID
     *
     * @param int $id
     * @return \App\Models\Mission|null
     */
    public function getMissionById($id);

    /**
     * Create a new mission
     *
     * @param array $data
     * @return \App\Models\Mission
     */
    public function createMission(array $data);

    /**
     * Update mission
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateMission($id, array $data);

    /**
     * Delete mission
     *
     * @param int $id
     * @return bool
     */
    public function deleteMission($id);

    /**
     * Get missions by difficulty level
     *
     * @param string $level
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMissionsByDifficultyLevel($level);

    /**
     * Get missions with completion statistics
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMissionsWithStats();

    /**
     * Mark mission as completed for a user
     *
     * @param int $userId
     * @param int $missionId
     * @param int $spinTicketsEarned
     * @return \App\Models\UserMission
     */
    public function completeMission($userId, $missionId, $spinTicketsEarned = 0);

    /**
     * Get completed missions for a user
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCompletedMissions($userId);
}
