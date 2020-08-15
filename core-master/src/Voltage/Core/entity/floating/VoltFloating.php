<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity\floating;

use Voltage\Core\resources\LoadResources;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Skin;

class VoltFloating extends Floating
{

    public function getName(): string
    {
        return "VoltFloating";
    }

    public function getCustomSkin() : ?Skin
    {
        return new Skin("VoltFloating",LoadResources::PNGtoBYTES("volt"),"","geometry.volt",LoadResources::getGeometry());
    }

    public function onClick(VOLTPlayer $player) : void
    {
        //UI
    }

    public function getCustomNameTag(): string
    {
        return "§eVOLT +§c§l Rank";
    }

}