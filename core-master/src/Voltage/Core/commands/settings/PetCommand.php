<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\settings;

use Voltage\Core\manager\PetsManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;

class PetCommand extends Command
{
    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'pet',
            'Get pet',
            '/pet <>',
        );
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

                    switch ($args[0]) {

                        case "del":
                        case "delete":
                        case "remove":

                            if (PetsManager::findPet($sender)) {

                                PetsManager::removePet($sender);
                                //$sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PET_REMOVE"));
                                return true;

                            } else {

                                //$sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PET_NOT_REMOVE"));
                                return false;

                            }
                            break;

                        default:

                            if (!in_array($args[0], PetsManager::ENTITY) or $args[0] === "random") {

                                if (PetsManager::findPet($sender)) {

                                    PetsManager::removePet($sender);

                                }

                                $pet = ucfirst($args[0]);

                                if (PetsManager::givePet($sender, $pet . "Pet")) {

                                    //$sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PET_ADD", $pet));

                                } else {

                                    //$sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PET_NOT_ADD", $pet));

                                }

                            } else {

                                //$sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PET_NOT_EXIST", $pet));

                            }
                            break;

                    }

                }

                //$sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PET_LIST"));
                return false;

            }

            $sender->sendMessage(TextFormat::RED . "please use this command in-game");
        }
        return true;

    }

}