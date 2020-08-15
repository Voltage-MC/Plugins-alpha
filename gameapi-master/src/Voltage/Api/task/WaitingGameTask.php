<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 14:53
 */

namespace Voltage\Api\task;

use pocketmine\scheduler\Task;
use Voltage\Api\event\GameWaitingEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class WaitingGameTask extends Task
{

    public function __construct()
    {
        Game::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20);
    }

    public function onRun(int $currentTick)
    {
        if (Game::getInstance()->getMode() === GAPlayer::MODE_WAITING) {

            if (count(Game::getInstance()->getAllPlayerIsJoin()) < Game::getInstance()->getMinSlots()) {

                $event = new GameWaitingEvent();
                $event->call();

            } else {

                Game::getInstance()->setMode(GAPlayer::MODE_START);
                new StartGameTask();
                Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

            }

        } else {

            Game::getInstance()->setMode(GAPlayer::MODE_START);
            new StartGameTask();
            Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

        }

    }

}