<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\staff;

use Voltage\Core\base\Gambler;
use Voltage\Core\utils\BroadCast;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use Voltage\Core\Core;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class RankCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('rank', 'Changed the rank','/rank [player] <rank>');
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

            if ($sender instanceof CommandSender or $sender instanceof VOLTPlayer) {

                if (
                    $sender->isOp()
                    or
                    $sender->hasPermission("admin")
                ) {

                    if (!empty($args[1])) {

                        $name = !is_null(Network::getPlayer($args[1]))
                            ?
                            Network::getPlayer($args[1])
                            :
                            $args[0];

                        switch (strtolower($args[1])) {

                            case "player":
                                Gambler::setRank($name, VOLTPlayer::RANK_PLAYER);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_PLAYER];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "famous":
                                Gambler::setRank($name, VOLTPlayer::RANK_FAMOUS);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_FAMOUS];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "vip":
                                Gambler::setRank($name, VOLTPlayer::RANK_VIP);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_VIP];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "vip+":
                                Gambler::setRank($name, VOLTPlayer::RANK_VIP_PLUS);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_VIP_PLUS];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "volt":
                                Gambler::setRank($name, VOLTPlayer::RANK_VOLT);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_VOLT];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "partner":
                                Gambler::setRank($name, VOLTPlayer::RANK_PARTNER);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_PARTNER];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "trainee":
                                Gambler::setRank($name, VOLTPlayer::RANK_TRAINEE);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_TRAINEE];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "moderator":
                                Gambler::setRank($name, VOLTPlayer::RANK_MOD);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_MOD];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "administrator":
                                Gambler::setRank($name, VOLTPlayer::RANK_ADMIN);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_ADMIN];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            case "owner":
                                Gambler::setRank($name, VOLTPlayer::RANK_OWNER);
                                $rank = VOLTPlayer::RANK[VOLTPlayer::RANK_OWNER];
                                $sender->sendMessage(Core::getPrefix() . "§cYou put the " . $rank . " §r§crank in §7" . $name);
                                BroadCast::sendMessageNetwork("RANK_BROADCAST", array($name,$rank));
                                return true;
                            default:
                                $sender->sendMessage("§cThis rank does not exist");
                                return false;

                        }

                    }
                    $sender->sendMessage("Rank system:\n" . TextFormat::YELLOW . "/rank " . TextFormat::GOLD." [player]" . TextFormat::RED . " <rank>");
                    return false;

                }

            }

        }
        return true;

    }

}