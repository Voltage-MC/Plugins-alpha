<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity\floating;

use Voltage\Core\Core;
use Voltage\Core\resources\LoadResources;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Skin;

class CoinsFloating extends Floating
{

    public function getName(): string
    {
        return "CoinsFloating";
    }

    public function getCustomSkin() : ?Skin
    {
        return new Skin("CoinsFloating",LoadResources::PNGtoBYTES("coins"),"","geometry.coins",LoadResources::getGeometry());
    }

    public function onClick(VOLTPlayer $player) : void
    {
        $win = rand(1,20);
        $player->addMoney($win);
        $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("COINS_WIN",array($win)));
        $this->close();
    }

}