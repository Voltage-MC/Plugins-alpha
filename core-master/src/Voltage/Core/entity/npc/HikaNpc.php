<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity\npc;

use pocketmine\utils\TextFormat;
use Voltage\Core\base\Server;
use Voltage\Core\form\ModalForm;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\resources\LoadResources;
use Voltage\Core\utils\MySQL;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Skin;

class HikaNpc extends NPCTransfer
{

    public function getName(): string
    {
        return "HikaNpc";
    }

    public function getCustomSkin() : ?Skin
    {
        return new Skin("Hika",LoadResources::PNGtoBYTES("concrete_sword"),"","geometry.hika",LoadResources::getGeometry());
    }

    public function onClick(VOLTPlayer $player) : void
    {
        self::getUI($player);
    }

    public function getNameTagUpdate() : string
    {
        $status = "§c§lOffline";
        $i = 0;

        foreach (Network::getPortHikabrains() as $port => $type) {

            $i += Server::getCount($port);

            $statu = Server::getOnlineServer($port);

            if ($statu === "§a§lOnline" and $status !== "§a§lOnline") {

                $status = "§a§lOnline";

            }

        }

        return "§5§lHikaBrain§r §r§7- §r" .  $status . "§r\n" . "§f[§7" . $i . "§f] §7Player Online";
    }

    public static function getUI(VOLTPlayer $player)
    {
        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    $port = array_keys(Network::getPortHikabrains())[$data];
                    $player->transfer(Network::IP, $port);

                }

            }

        );
        $ui->setTitle(TextFormat::DARK_RED . "Select a game:");

        $i = 1;
        foreach (Network::getPortHikabrains() as $port => $type) {

            $maxcounts = explode("vs", $type);
            $maxcount = 0;

            foreach ($maxcounts as $count) {
                $maxcount += $count;
            }

            $ui->addButton("HB" . $i . " : " . $type . " (" . Server::getCount($port) . " / " . $maxcount . ") " . Server::getOnlineServer($port));
            $i++;

        }

        $ui->sendToPlayer($player);
    }

}