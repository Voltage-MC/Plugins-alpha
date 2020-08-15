<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 14:53
 */

namespace Voltage\Api\task;

use pocketmine\scheduler\Task;
use Voltage\Api\event\GameEvent;
use Voltage\Api\event\GameLostEvent;
use Voltage\Api\event\GameWinEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class GameTask extends Task
{
    public $time;

    public function __construct()
    {
        Game::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20);
        $this->time = Game::getInstance()->data["gametime"];
    }

    public function onRun(int $currentTick)
    {
        if ($this->time > 0 ) {

            $finish = Game::getInstance()->isFinish();

            if (!$finish) {

                if (count(Game::getInstance()->getPlaying()) >= Game::getInstance()->getMinSlots()) {

                    if (Game::getTeams()->isTeams()) {

                        if  (Game::getTeams()->getTeamsNotDead() > 1) {

                            $event = new GameEvent($this->time);
                            $event->call();

                            $this->time--;

                        } else {

                            foreach (Game::getInstance()->getPlaying() as $player) {

                                $player->setWin();
                                $player->closePlayer();
                                $event = new GameWinEvent($player);
                                $event->call();

                            }

                            Game::getInstance()->setMode(GAPlayer::MODE_FINISH);
                            new FinishGameTask();
                            Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

                        }

                    } else {

                        $event = new GameEvent($this->time);
                        $event->call();

                        $this->time--;
                    }

                } else {

                    foreach (Game::getInstance()->getPlaying() as $player) {

                        $player->setWin();
                        $player->closePlayer();
                        $event = new GameWinEvent($player);
                        $event->call();

                    }

                    Game::getInstance()->setMode(GAPlayer::MODE_FINISH);
                    new FinishGameTask();
                    Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

                }

            } else {

                Game::getInstance()->setMode(GAPlayer::MODE_FINISH);
                new FinishGameTask();
                Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

            }

        } else {

            foreach (Game::getInstance()->getPlaying() as $player) {
                
                $player->closePlayer();

            }

            Game::getInstance()->setMode(GAPlayer::MODE_FINISH);
            new FinishGameTask();
            Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

        }

    }

}