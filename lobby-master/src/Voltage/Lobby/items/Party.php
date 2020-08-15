<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 09/05/2019
 * Time: 23:00
 */

namespace Voltage\Lobby\items;

use Voltage\Core\form\SimpleForm;
use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Voltage\Lobby\LBPlayer;

class Party extends Item
{

    public function __construct(int $meta = 0)
    {
        parent::__construct(self::CAKE, $meta, "Cake");
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($player instanceof LBPlayer) {

            if ($player->interact()) {

                //$this->addUI($player);

            }

        }
        return true;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {
        if ($player instanceof LBPlayer) {

            if ($player->interact()) {

                //$this->addUI($player);

            }

        }
        return true;
    }

    private function addUI(LBPlayer $player)
    {
        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch($data){

                        case 0:

                            break;
                        case 1:

                            break;

                    }

                }

            }

        );
        $ui->setTitle($player->messageToTranslate("PARTY_TITLE"));
        $ui->addButton($player->messageToTranslate("PARTY_CREATE_UI"), 0);
        $ui->addButton($player->messageToTranslate("PARTY_INVITE_UI"), 0);
        $ui->addButton($player->messageToTranslate("PARTY_MANAGE_UI"), 0);
        $ui->sendToPlayer($player);
    }

}