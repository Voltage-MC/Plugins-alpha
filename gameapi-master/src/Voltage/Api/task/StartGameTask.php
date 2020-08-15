<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 14:53
 */

namespace Voltage\Api\task;

use pocketmine\scheduler\Task;
use Voltage\Api\event\GameStartEvent;
use Voltage\Api\event\GameWaitingEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;
use Voltage\Core\base\Server;
use Voltage\Core\resources\LoadResources;

class StartGameTask extends Task
{
    public $time;

    public function __construct()
    {
        Game::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20);
        $this->time = Game::getInstance()->data["starttime"];
    }

    public function onRun(int $currentTick)
    {
        if (Game::getInstance()->getMode() === GAPlayer::MODE_START) {

            if ($this->time > 0) {

                if (count(Game::getInstance()->getPlaying()) < Game::getInstance()->getMinSlots()) {

                    $event = new GameStartEvent($this->time);
                    $event->call();

                    $this->time--;

                } else {

                    Game::getInstance()->setMode(GAPlayer::MODE_WAITING);
                    new WaitingGameTask();
                    Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

                }

            } else {

                foreach (Game::getInstance()->getAllPlayerIsJoin() as $player) {

                    if ($player->getMode() !== GAPlayer::MODE_SPECTATOR) {

                        $player->setMode(GAPlayer::MODE_PLAYER);

                    }

                }

                Server::ingameServer(Game::getInstance()->getServer()->getPort());
                Game::getInstance()->setMode(GAPlayer::MODE_GAME);
                new GameTask();
                Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

            }

        } else {

            Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

        }

    }

}