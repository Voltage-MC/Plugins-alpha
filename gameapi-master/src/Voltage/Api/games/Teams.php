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

class Teams
{

    public function setTeams()
    {
        $i = 0;
        $players = Game::getInstance()->getPlaying();
        $p = array_chunk($players, ceil(count($players) / count(Game::getInstance()->data["teams"])));

        foreach (Game::getInstance()->data["teams"] as $team) {

            $players = $p[$i];

            foreach ($players as $player) {

                if ($player instanceof GAPlayer) {

                    $player->team = $team;

                }

            }
            $i++;

        }

    }

    /**
     * @param GAPlayer $player
     */
    public function TeleportToSpawn(GAPlayer $player)
    {
        $team = $player->getTeam();
        $player->teleport(new Vector3(Game::getInstance()->getData()[$team."-spawn"][0], Game::getInstance()->getData()[$team."-spawn"][1] + 1, Game::getInstance()->getData()[$team."-spawn"][2]),Game::getInstance()->getData()[$team."-spawn"][3]);
    }

    /**
     * @param GAPlayer $player
     * @return array
     */
    public function TeleportToSpawninVector(GAPlayer $player)
    {
        $team = $player->getTeam();
        return
            [
                new Vector3(
                    Game::getInstance()->getData()[$team."-spawn"][0],
                    Game::getInstance()->getData()[$team."-spawn"][1] + 1,
                    Game::getInstance()->getData()[$team."-spawn"][2]
                ),
                Game::getInstance()->getData()[$team."-spawn"][3]
            ];
    }

    /**
     * @return string[]
     */
    public function getTeamsNotDead() : array
    {
        $players = Game::getInstance()->getPlaying();
        $teams = [];

        foreach ($players as $player) {

             if (!in_array($player->getTeam(), $teams)) {

                $teams[] = $player->getTeam();

            }

        }

        return $teams;
    }

    /**
     * @return string[]
     */
    public function getTeams() : array
    {
        return Game::getInstance()->getData()["teams"];
    }

    /**
     * @return bool
     */
    public function isTeams() : bool
    {
        return isset(Game::getInstance()->getData()["teams"]);
    }

}