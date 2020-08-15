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

class GameStartEvent extends PluginEvent
{
    /**
     * @var int
     */
    private $time;

    /**
     * GameStartEvent constructor.
     * @param int $time
     */
    public function __construct(int $time){
        parent::__construct(Game::getInstance());
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getTime() : int
    {
        return $this->time;
    }

}