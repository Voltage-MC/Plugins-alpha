<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 15:56
 */

namespace Voltage\Api\games;

use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class Solo
{

    public function TeleportToSpawns()
    {
        $players = Game::getInstance()->getPlaying();
        $n = 1;

        foreach ($players as $player) {

            $player->teleport(new Vector3(Game::getInstance()->getData()[$n."-spawn"][0], Game::getInstance()->getData()[$n."-spawn"][1] + 1, Game::getInstance()->getData()[$n."-spawn"][2]),Game::getInstance()->getData()[$n."-spawn"][3]);
            $n++;

        }

    }

}