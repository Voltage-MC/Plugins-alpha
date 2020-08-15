<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 13:04
 */

namespace Voltage\Core\task;

use Voltage\Core\base\Friends;
use Voltage\Core\Core;
use pocketmine\scheduler\Task;

class FriendRemoveDelayTask extends Task
{
    private $name;
    private $player;

    public function __construct(string $player, string $name)
    {
        $this->name = $name;
        $this->player = $player;
        Core::getInstance()->getScheduler()->scheduleDelayedTask($this, 20 * 30);
    }

    public function onRun(int $currentTick)
    {
        Friends::delFriendRequest($this->player,$this->name);
    }

}