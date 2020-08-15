<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 18:29
 */

namespace Voltage\Core\task;

use Voltage\Core\base\Server;
use Voltage\Core\Core;
use pocketmine\scheduler\Task;

class UploadServerTask extends Task
{

    public function __construct()
    {
        Core::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20 * 15);
    }

    public function onRun(int $currentTick)
    {
        if (Server::getOnlineServer(Core::getInstance()->getServer()->getPort())) {

            $online = count(Core::getInstance()->getServer()->getOnlinePlayers());
            Server::setServer(Core::getInstance()->getServer()->getPort(), $online, Core::getInstance()->getServer()->getOnlinePlayers());

        }

    }

}