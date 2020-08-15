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
use Voltage\Core\resources\LoadResources;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Skin;

class PvpNpc extends NPCTransfer
{

    public function getName(): string
    {
        return "PvpNpc";
    }

    public function getCustomSkin() : ?Skin
    {
        return new Skin("Apple",LoadResources::PNGtoBYTES("apple"),"","geometry.pvp",LoadResources::getGeometry());
    }

    public function onClick(VOLTPlayer $player) : void
    {
        self::getUi($player);
    }

    public function getNameTagUpdate() : string
    {
        $port = Network::NAME["Pvp"];

        return "§5§lPlayer vs Player §r§7- §r" . Server::getOnlineServer($port) . "§r\n" . "§f[§7" . Server::getCount($port) . "§f] §7Player Online";
    }

    public static function getUi(VOLTPlayer $player)
    {
        $ui = new ModalForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch($data){

                        case true:
                            $player->transfer(Network::IP, Network::NAME["Pvp"]);
                            break;
                        case false:
                            break;

                    }

                }

            }

        );
        $ui->setTitle(TextFormat::DARK_RED . "Player vs Player");
        $ui->setContent($player->messageToTranslate("TRANSFER_TELEPORT_UI", array("Player vs Player")));
        $ui->setButton1($player->messageToTranslate("YES"));
        $ui->setButton2($player->messageToTranslate("NO"));
        $ui->sendToPlayer($player);
    }

}