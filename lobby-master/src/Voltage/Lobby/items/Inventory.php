<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 01:01
 */

namespace Voltage\Lobby\items;

use Voltage\Core\inventory\InventoryMenu;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Voltage\Lobby\LBPlayer;

class Inventory extends Item
{
    public function __construct(int $meta = 0)
    {
        parent::__construct(self::CHEST_MINECART, $meta, "Chest Minecraft");
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($player instanceof LBPlayer) {

            if ($player->interact()) {

                self::addUI($player);

            }

        }
        return true;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {
        if ($player instanceof LBPlayer) {

            if ($player->interact()) {

                self::addUI($player);

            }

        }
        return true;
    }

    public static function addUI(LBPlayer $player)
    {
        $inv = new InventoryMenu(InventoryMenu::INVENTORY_TYPE_HOPPER);
        $inv->setName("§bInventory");
        $items =
            [
                0 => Item::get(Item::CHEST,0,1)->setCustomName("§r§e§lMarket"),
                1 => Item::get(Item::BLAZE_POWDER,0,1)->setCustomName("§r§c§lCosmetics"),
                2 => Item::get(Item::BOOK,0,1)->setCustomName("§r§3§lElement unlocked"),
                3 => Item::get(Item::BONE,0,1)->setCustomName("§r§9§lStats"),
                4 => Item::get(Item::SHIELD,0,1)->setCustomName("§r§c§lSettings"),
            ];

        $inv->setItem($items);
        $inv->send($player);
    }

}