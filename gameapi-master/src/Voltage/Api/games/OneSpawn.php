<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 22/04/2019
 * Time: 14:20
 */

namespace Voltage\Api\games;

use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class OneSpawn
{
    /**
     * @param GAPlayer $player
     */
    public function TeleportToSpawn(GAPlayer $player)
    {
        $player->teleport(new Vector3(Game::getInstance()->getData()["spawn"][0], Game::getInstance()->getData()["spawn"][1]+1, Game::getInstance()->getData()["spawn"][2]),Game::getInstance()->getData()["spawn"][3]);
    }
}