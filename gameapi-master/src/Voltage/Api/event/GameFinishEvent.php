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

class GameFinishEvent extends PluginEvent
{
    private $time;

    public function __construct( int $time){
        parent::__construct(Game::getInstance());
        $this->time = $time;
    }

    public function getTime() : int
    {
        return $this->time;
    }

}