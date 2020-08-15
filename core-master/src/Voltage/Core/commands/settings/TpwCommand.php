<?php
/**
 * Created by PhpStorm.
 * User: Walid
 * Date: 11/29/2018
 * Time: 3:03 PM
 */

namespace Voltage\Core\commands\settings;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use Voltage\Core\VOLTPlayer;

class TpwCommand extends Command
{
    /**
     * XyzCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('tpw', 'teleport to world');
        $this->setPermission("owner");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof VOLTPlayer) {

            if (isset($args[0])) {

                if (!Server::getInstance()->isLevelLoaded($args[0])) {

                    if (Server::getInstance()->loadLevel($args[0])){

                        $level = Server::getInstance()->getLevelByName($args[0]);
                        $spawn = $level->getSafeSpawn();
                        $sender->teleport(new Position($spawn->getX(),$spawn->getY(), $spawn->getZ(), $level));
                        $sender->sendMessage($sender->messageToTranslate("TPW_TELEPORT", array($args[0])));
                        return true;

                    } else {

                        $sender->sendMessage($sender->messageToTranslate("TPW_NOT_EXIST"));
                        return true;

                    }

                } else {

                    $level = Server::getInstance()->getLevelByName($args[0]);
                    $spawn = $level->getSafeSpawn();
                    $sender->teleport(new Position($spawn->getX(),$spawn->getY(), $spawn->getZ(), $level));
                    $sender->sendMessage($sender->messageToTranslate("TPW_TELEPORT", array($args[0])));
                    return true;

                }

            }

            $sender->sendMessage($sender->messageToTranslate("TPW_LIST"));
        }
        return true;

    }

}