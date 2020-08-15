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

class GameWaitingEvent extends PluginEvent
{
    /**
     * GameWaitingEvent constructor.
     */
    public function __construct(){
        parent::__construct(Game::getInstance());
    }

}