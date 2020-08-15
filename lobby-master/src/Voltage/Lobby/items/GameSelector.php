<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 03/05/2019
 * Time: 21:06
 */

namespace Voltage\Lobby\items;

use Voltage\Core\base\Server;
use Voltage\Core\entity\npc\HikaNpc;
use Voltage\Core\entity\npc\PvpNpc;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\item\Compass;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TE;
use Voltage\Lobby\LBPlayer;

class GameSelector extends Compass
{

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($player instanceof LBPlayer) {

            $this->addUI($player);

        }
        return true;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {
        if ($player instanceof LBPlayer) {

            $this->addUI($player);

        }
        return true;
    }

    private function addUI(LBPlayer $player)
    {
        if ($player->interact()) {

            $ui = new SimpleForm
            (
                function (VOLTPlayer $player, $data)
                {

                    if ($data === null) {
                    } else {

                        switch($data){

                            case 0:
                                HikaNpc::getUI($player);
                                break;

                            /*case 1:
                                PvpNpc::getUi($player);
                                break;
                            */

                        }

                    }

                }

            );
            $ui->setTitle($player->messageToTranslate("GAME_SELECTOR_TITLE"));
            $ui->setContent($player->messageToTranslate("GAME_SELECTOR_CONTENT"));

            $status = "§c§lOffline";

            foreach (Network::getPortHikabrains() as $port => $type) {

                $statu = Server::getOnlineServer($port);

                if ($statu === "§a§lOnline") {

                    $status = "§a§lOnline";
                    break;

                }

            }
            $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TE::DARK_AQUA . "Hikabrain " . $status)), SimpleForm::IMAGE_TYPE_PATH, "textures/items/clay_ball");
            //$ui->addButton($player->messageToTranslate("UI_BUTTON", array(TE::DARK_BLUE . "Player vs Player " . Server::getOnlineServer(Network::NAME["Pvp"]))), SimpleForm::IMAGE_TYPE_PATH, "textures/items/gold_sword");
            $ui->sendToPlayer($player);

        }

    }

}