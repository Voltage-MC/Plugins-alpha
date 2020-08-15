<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 15:23
 */

namespace Voltage\Api\event;

use pocketmine\event\plugin\PluginEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class GameLostEvent extends PluginEvent
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * GameWinEvent constructor.
     * @param GAPlayer $player
     */
    public function __construct(GAPlayer $player){
        parent::__construct(Game::getInstance());
        $this->player = $player;
    }

    /**
     * @return GAPlayer
     */
    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

}