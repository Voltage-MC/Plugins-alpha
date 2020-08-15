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

class GameJoinEvent extends PluginEvent
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * @var int
     */
    private $type;

    const FULL = 0;
    const START = 1;
    const NORMAL = 2;

    /**
     * GameJoinEvent constructor.
     * @param GAPlayer $player
     * @param int $type
     */
    public function __construct(GAPlayer $player, int $type){
        parent::__construct(Game::getInstance());
        $this->player = $player;
        $this->type = $type;
    }

    /**
     * @return GAPlayer
     */
    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getType() : int
    {
        return $this->type;
    }

}