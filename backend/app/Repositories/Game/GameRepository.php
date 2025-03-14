<?php

namespace App\Repositories\Game;

use App\Models\Lucky_wheel;

class GameRepository implements GameInterface
{
    protected ?Lucky_wheel $game;

    public function __construct(Lucky_wheel $game)
    {
        $this->game = $game;
    }

    public function getAll()
    {
        return $this->game->all();
    }

}

