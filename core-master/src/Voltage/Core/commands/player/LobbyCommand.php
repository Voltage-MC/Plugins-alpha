<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\player;

use Voltage\Core\Core;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;

class LobbyCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('lobby', 'Teleported to the main network server','/lobby');
    }

    /**
     * @param CommandSender $sender
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $label, array $args) : bool
    {
        if (Core::getInstance()->isEnabled()) {

            if ($sender instanceof VOLTPlayer) {

                if (Core::getInstance()->getServer()->getPort() === Network::NAME["Lobby"]) {

                    $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("LOBBY_TELEPORT"));

                    $level = Core::getInstance()->getServer()->getDefaultLevel();
                    $pos = $level->getSafeSpawn();

                    $sender->teleport(new Position($pos->getX(), $pos->getY(), $pos->getZ(), $level), 0, 0);

                } else {

                    $sender->transfer(Network::IP,Network::NAME["Lobby"]);

                }

            }

        }
        return true;

    }

}