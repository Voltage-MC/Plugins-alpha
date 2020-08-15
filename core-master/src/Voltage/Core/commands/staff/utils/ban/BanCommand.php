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
use pocketmine\utils\TextFormat;
use Voltage\Core\base\Ban;
use Voltage\Core\base\PlayerInfo;
use Voltage\Core\base\Request;
use Voltage\Core\Core;
use Voltage\Core\utils\API;
use Voltage\Core\utils\BroadCast;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class BanCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('ban', 'Ban the player','/ban [name] [time] [reason]');
        $this->setPermission("mod");
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
                    $sender->hasPermission("mod")
                ) {

                    if (!empty($args[0])) {

                        switch ($args[0]) {

                            case "list":

                                return true;
                            default:
                                $name = !is_null(Network::getPlayer($args[0]))
                                    ?
                                    Network::getPlayer($args[0])
                                    :
                                    $args[0];

                                $time = "indefinite";
                                $reason = "indefinite";

                                if (!empty($args[1])) {

                                    unset($args[0]);

                                    $times = [];

                                    foreach ($args as $arg) {

                                        $score = str_replace(array("d","h","n","m","s"),"",$arg, $count);

                                        if ($count > 0) {

                                            if (is_numeric($score)) {

                                                $times[] = $arg;
                                                unset($args[array_search($arg,$args)]);

                                            }

                                        }

                                    }

                                    if (count($times) > 0) {

                                        $temp = implode(" ", $times);

                                        $str = str_replace("n", " Month ", $temp);
                                        $str = str_replace("d", " Day ", $str);
                                        $str = str_replace("h", " Hour ", $str);
                                        $str = str_replace("m", " Minute ", $str);
                                        $str = str_replace("s", " Second ", $str);

                                        $time = strtotime("+" . strtolower($str));

                                    }

                                    if (count($args) > 0) {

                                        $reason = implode(" ", $args);

                                    }

                                }

                                $cid = PlayerInfo::getCid($name);
                                $uuid = PlayerInfo::getUUID($name);
                                $ip = PlayerInfo::getIp($name);

                                Ban::add($name, $sender->getName(), $ip, $cid, $uuid, $time, $reason);

                                $temp = $time === "indefinite" ? "indefinite" : API::getTimeFormat((int)$time - strtotime("now"));

                                Request::add("BAN_REQUEST_ADD", array($sender->getName(), $name, $reason, $temp));
                                BroadCast::sendMessageNetwork("BAN_BROADCAST", array($name, $sender->getName()));
                                return true;

                        }


                    }

                    $sender->sendMessage(Core::getPrefix() . "Ban system:\n" . TextFormat::YELLOW . "/ban" . TextFormat::GOLD." [name] [time] [reason]");

                }

            }

        }
        return true;

    }

}