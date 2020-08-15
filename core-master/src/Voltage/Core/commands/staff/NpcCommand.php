<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\staff;

use Voltage\Core\entity\Chest;
use Voltage\Core\entity\floating\Floating;
use Voltage\Core\entity\npc\NPCTransfer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Entity;

class NpcCommand extends Command
{

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('npc', 'Spawn the npc','/npc [name]');
        $this->setPermission("owner");

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

                if (
                    $sender->isOp()
                    or
                    $sender->hasPermission("owner")
                ) {

                    if (!empty($args[0])) {

                        switch ($args[0]) {

                            case "lobby":
                                $nbt = NPCTransfer::createNBT($sender);
                                Entity::createEntity("LobbyNpc",$sender->getLevel(),$nbt);
                                break;

                            case "faction":
                                 $nbt = NPCTransfer::createNBT($sender);
                                 Entity::createEntity("FactionNpc",$sender->getLevel(),$nbt);
                                 break;

                            case "pvp":
                                $nbt = NPCTransfer::createNBT($sender);
                                Entity::createEntity("PvpNpc",$sender->getLevel(),$nbt);
                                break;

                            case "hika":
                                $nbt = NPCTransfer::createNBT($sender);
                                Entity::createEntity("HikaNpc",$sender->getLevel(),$nbt);
                                break;

                            case "volt":
                                $nbt = Floating::createNBT($sender);
                                Entity::createEntity("VoltFloating",$sender->getLevel(),$nbt);
                                break;

                            case "box":
                                $nbt = Chest::createNBT($sender);
                                Entity::createEntity("Chest",$sender->getLevel(),$nbt);
                                break;

                        }

                    }

                }

            }

        }
        return true;

    }

}