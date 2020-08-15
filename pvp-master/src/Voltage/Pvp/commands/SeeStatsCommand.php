<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Pvp\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Voltage\Core\Core;
use Voltage\Core\form\CustomForm;
use Voltage\Core\VOLTPlayer;
use Voltage\Pvp\Provider;
use Voltage\Pvp\PVPPlayer;

class SeeStatsCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('seestats', 'To see the player economy stats','/seestats');
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

            if ($sender instanceof PVPPlayer) {

                if (isset($args[0])) {

                    $name = Provider::getPlayer($args[0]);

                    if (!$name) {

                        $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PLAYER_NOT_FOUND"));
                        return true;
                    }

                    $ui = new CustomForm(function (VOLTPlayer $player, $data) {});
                    $ui->setTitle($sender->messageToTranslate("PVP_STATS_TITLE", array($name)));
                    $ui->addLabel($sender->messageToTranslate("PVP_STATS_PVP"));
                    $KD = round((Provider::getKills($name) > 0 ? Provider::getKills($name) : 1) / (Provider::getDeaths($name) ? Provider::getDeaths($name) : 1), 2);
                    $ui->addLabel(
                        $sender->messageToTranslate("PVP_STATS", array("Elos", Provider::getElos($name))) . "\n" .
                        //$ui->addLabel($sender->messageToTranslate("PVP_STATS", array("Rank", $sender->getElos())));
                        $sender->messageToTranslate("PVP_STATS", array("Streak", Provider::getStreak($name))) . "\n" .
                        $sender->messageToTranslate("PVP_STATS", array("K/D", $KD)) . "\n" .
                        $sender->messageToTranslate("PVP_STATS", array("Kills", Provider::getKills($name))) . "\n" .
                        $sender->messageToTranslate("PVP_STATS", array("Deaths", Provider::getDeaths($name)))
                    );
                    $ui->addLabel("");
                    $ui->sendToPlayer($sender);
                    return true;

                }

                $sender->sendMessage($sender->messageToTranslate("PVP_LIST"));
            }

        }
        return true;

    }

}