<?php
/**
 * Created by PhpStorm.
 * User: Walid
 * Date: 11/29/2018
 * Time: 3:03 PM
 */

namespace Voltage\Core\commands\settings;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use Voltage\Core\VOLTPlayer;

class XyzCommand extends Command
{
    /**
     * XyzCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('xyz', 'look the coordonate');
        $this->setPermission("admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof VOLTPlayer) {

            if (
                $sender->isOp()
                or
                $sender->hasPermission("admin")
            ) {

                if (isset($args[0])) {

                    switch ($args[0]) {

                        case "round":
                            $pos = $sender->floor()->add(0.5,0,0.5);
                            $sender->sendMessage(TextFormat::GREEN . "X: " . $pos->getX() . " Y: " . $pos->getY() . " Z: " . $pos->getZ()  . " Yaw: " . round($sender->getYaw()));
                            break;

                        case "real":
                            $pos = $sender->asVector3();
                            $sender->sendMessage(TextFormat::GREEN . "X: " . $pos->getX() . " Y: " . $pos->getY() . " Z: " . $pos->getZ());
                            break;

                    }

                }

            }

        }

    }

}