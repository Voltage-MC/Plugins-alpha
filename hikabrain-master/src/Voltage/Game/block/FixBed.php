<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 13/07/2019
 * Time: 21:57
 */

namespace Voltage\Game\block;

use pocketmine\block\Bed;
use pocketmine\item\Item;
use Voltage\Core\fake\Player;

class FixBed extends Bed
{
    public function onActivate(Item $item, Player $player = null): bool
    {
        return false;
    }
}