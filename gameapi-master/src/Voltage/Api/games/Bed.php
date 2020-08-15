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

class Bed
{

    /**
     * @return Vector3[]
     */
    public function getBeds() : array
    {
        $beds = [];
        foreach (Game::getInstance()->getData()["teams"] as $team) {

            $bed = Game::getInstance()->getData()[$team."-bed"];
            $beds[] = new Vector3($bed[0],$bed[1] + 1,$bed[2]);

        }
        return $beds;
    }

    /**
     * @param string $team
     * @return Vector3
     */
    public function getBed(string $team) : Vector3
    {
        $bed = Game::getInstance()->getData()[$team."-bed"];
        return new Vector3($bed[0], $bed[1] + 1, $bed[2]);
    }

}