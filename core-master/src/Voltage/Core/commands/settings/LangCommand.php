<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\settings;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;

class LangCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('lang', 'Changed the language','/lang <en|fr|es|de>');
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

            if ($sender instanceof VOLTPlayer) {

                if (!empty($args[0])) {

                    if (in_array($args[0],array("en","english"))) {

                        $sender->setLang("en");
                        $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("LANG_CHANGED"));
                        return true;

                    /*} else if (in_array($args[0],array("fr","francais","français"))) {

                        $sender->setLang("fr");

                        $sender->sendMessage($sender->messageToTranslate("LANG_CHANGED"));
                        return true;*/

                    }

                }


                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("LANG_LIST"));
                return false;

            }

            $sender->sendMessage(TextFormat::RED . "please use this command in-game");
        }
        return true;

    }

}