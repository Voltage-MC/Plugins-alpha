<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity\npc;


use Voltage\Core\base\Server;
use Voltage\Core\form\ModalForm;
use Voltage\Core\resources\LoadResources;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Skin;

class FactionNpc extends NPCTransfer
{

    public function getName(): string
    {
        return "FactionNpc";
    }

    public function getCustomSkin() : ?Skin
    {
        return new Skin("Faction",LoadResources::PNGtoBYTES("faction"),"","geometry.faction",LoadResources::getGeometry());
    }

    public function onClick(VOLTPlayer $player) : void
    {
        /*$ui = new ModalForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch($data){

                        case true:
                            break;
                        case false:
                            break;

                    }

                }

            }

        );
        $ui->setTitle($player->messageToTranslate(""));
        $ui->sendToPlayer($player);*/
    }

    public function getNameTagUpdate() : string
    {
        $port = Network::NAME["Faction"];

        return "§5§lFaction §r§7- §r" . Server::getOnlineServer($port) . "§r\n" . "§f[§7" . Server::getCount($port) . "§f] §7Player Online";
    }

}