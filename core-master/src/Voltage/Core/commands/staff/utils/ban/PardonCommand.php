<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\staff\utils\ban;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Voltage\Core\base\Ban;
use Voltage\Core\base\PlayerInfo;
use Voltage\Core\base\Request;
use Voltage\Core\Core;
use Voltage\Core\utils\API;
use Voltage\Core\utils\BroadCast;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class PardonCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('pardon', 'Pardon the player','/pardon [name]');
        $this->setPermission("admin");
    }

    /**
     * @param CommandSender $sender
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $label, array $args) : bool
    {
        $args = array_map('strtolower', $args);

        if (Core::getInstance()->isEnabled()) {

            if ($sender instanceof CommandSender) {

                if (
                    $sender->isOp()
                    or
                    $sender->hasPermission("admin")
                ) {

                    if (!empty($args[0])) {

                        if (Ban::existsName($args[0])) {

                            Ban::delByName($args[0]);
                            $sender->sendMessage(Core::getPrefix() . "§aThe player is unbanned " . $args[0]);

                        } else {

                            $sender->sendMessage(Core::getPrefix() . "§cThe player isn't banned");

                        }

                    }

                }

            }

        }
        return true;

    }

}