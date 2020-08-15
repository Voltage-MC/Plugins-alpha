<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\staff\utils\kick;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use Voltage\Core\base\Ban;
use Voltage\Core\base\PlayerInfo;
use Voltage\Core\base\Request;
use Voltage\Core\Core;
use Voltage\Core\utils\API;
use Voltage\Core\utils\BroadCast;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class KickCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('kick', 'Kick the player','/kick [name]');
        $this->setPermission("trainee");
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
                    $sender->hasPermission("trainee")
                ) {

                    if (!empty($args[0])) {

                        $name = Network::getPlayer($args[0]);

                        unset($args[0]);

                        if (count($args) > 0) {

                            $reason = null;

                        } else {

                            $reason = implode(" ",$args);

                        }

                        if (!is_null($name)) {

                            BroadCast::sendMessageNetwork("KICK_BROADCAST", array($name, $sender->getName()));
                            Request::add("KICK_REQUEST_ADD", array($sender->getName(), $name, $reason));

                        } else {

                            $sender->sendMessage(TextFormat::RED . "The player has not been found ");

                        }


                    }

                    $sender->sendMessage(Core::getPrefix() . "Kick system:\n" . TextFormat::YELLOW . "/kick" . TextFormat::GOLD." [name] [reason]");

                }

            }

        }
        return true;

    }

}