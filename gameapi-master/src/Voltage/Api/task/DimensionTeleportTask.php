<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 14:53
 */

namespace Voltage\Api\task;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class DimensionTeleportTask extends Task
{
    /**
     * @var GAPlayer
     */
    public $player;

    private $time = 1;
    private $pos;

    /**
     * Start constructor.
     */
    public function __construct(GAPlayer $player, Vector3 $pos)
    {
        Game::getInstance()->getScheduler()->scheduleRepeatingTask($this, 1);
        $this->player = $player;
        $this->pos = $pos;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        switch ($this->time) {

            case 0:
                $this->player->teleport($this->pos);
                Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());
                break;
            case 1:
                $this->player->teleport(new Vector3(0,100, 0));
                break;

        }
        $this->time--;
    }

}