<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 03/05/2019
 * Time: 21:06
 */

namespace Voltage\Pvp\items;

use Voltage\Core\form\SimpleForm;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\item\Compass;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TE;
use Voltage\Pvp\PVPPlayer;

class GameSelector extends Compass
{

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($player instanceof PVPPlayer) {

            $this->addUI($player);

        }
        return true;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {
        if ($player instanceof PVPPlayer) {

            $this->addUI($player);

        }
        return true;
    }

    private function addUI(PVPPlayer $player)
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
                                $player->transfer(Network::IP, Network::NAME["Lobby"]);
                                break;

                        }

                    }

                }

            );
            $ui->setTitle($player->messageToTranslate("GAME_SELECTOR_TITLE"));
            $ui->setContent($player->messageToTranslate("GAME_SELECTOR_CONTENT"));
            $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TE::GOLD . "LOBBY")), SimpleForm::IMAGE_TYPE_PATH, "textures/items/bed_purple");
            $ui->sendToPlayer($player);

        }

    }

}