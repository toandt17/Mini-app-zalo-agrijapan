<?php

namespace App\Repositories\Game;

interface GameInterface
{
    public function getAll();
    public function getById($id);
    public function update($id, array $data);
    public function validate(array $data);

    // Phương thức cho Mission
    public function getAllMissions();
    public function getMissionById($id);
    public function getUserMissions($userId);
    public function getMissionDetail($missionId, $userId);
    public function completeMission($userId, $missionId, $actionData = null);
    public function getMissionHistory($userId);
}
