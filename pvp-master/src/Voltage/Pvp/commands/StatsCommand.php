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
use Voltage\Pvp\PVPPlayer;

class StatsCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('stats', 'To see all these economy stats','/stats');
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

            if ($sender instanceof PVPPlayer) {

                $ui = new CustomForm(function (VOLTPlayer $player, $data) {});
                $ui->setTitle($sender->messageToTranslate("PVP_STATS_TITLE", array($sender->getDisplayName())));
                $ui->addLabel($sender->messageToTranslate("PVP_STATS_NETWORK"));
                $ui->addLabel(
                    $sender->messageToTranslate("PVP_STATS", array("Coins", $sender->getMoney())) . "\n" .
                    $sender->messageToTranslate("PVP_STATS", array("Xp", $sender->getXP()))  . "\n" .
                    $sender->messageToTranslate("PVP_STATS", array("Level", $sender->getLevelWithXP()))
                );
                $ui->addLabel($sender->messageToTranslate("PVP_STATS_PVP"));
                $KD = round(($sender->getKills() > 0 ? $sender->getKills() : 1) / ($sender->getDeaths() > 0 ? $sender->getDeaths() : 1), 2);
                    $ui->addLabel(
                    $sender->messageToTranslate("PVP_STATS", array("Elos", $sender->getElos())) . "\n" .
                    //$ui->addLabel($sender->messageToTranslate("PVP_STATS", array("Rank", $sender->getElos())));
                    $sender->messageToTranslate("PVP_STATS", array("Streak", $sender->getStreak())) . "\n" .
                    $sender->messageToTranslate("PVP_STATS", array("K/D", $KD)) . "\n" .
                    $sender->messageToTranslate("PVP_STATS", array("Kills", $sender->getKills())) . "\n" .
                    $sender->messageToTranslate("PVP_STATS", array("Deaths", $sender->getDeaths()))
                );
                $ui->addLabel("");
                $ui->sendToPlayer($sender);

            }

        }
        return true;

    }

}