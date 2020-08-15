<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 14:53
 */

namespace Voltage\Api\task;

use pocketmine\scheduler\Task;
use Voltage\Api\event\GameFinishEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;
use Voltage\Core\Core;

class FinishGameTask extends Task
{
    public $time;

    public function __construct()
    {
        Game::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20);
        $this->time = Game::getInstance()->data["finishtime"];
    }

    public function onRun(int $currentTick)
    {
        if (Game::getInstance()->getMode() === GAPlayer::MODE_FINISH) {

            if ($this->time > 0) {

                $event = new GameFinishEvent($this->time);
                $event->call();

                $this->time--;

            } else {

                Core::getInstance()->setRestart();
                Game::getInstance()->setFinishAll();
                Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

            }

        } else {

            Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

        }

    }

}