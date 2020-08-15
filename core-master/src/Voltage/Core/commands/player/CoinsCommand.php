<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;

class CoinsCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('coins', 'To see all these money coins','/coins', ["money"]);
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

                $sender->sendMessage(Core::PREFIX . $sender->messageToTranslate("COINS_GET", array($sender->getMoney())));

            }

        }
        return true;

    }

}