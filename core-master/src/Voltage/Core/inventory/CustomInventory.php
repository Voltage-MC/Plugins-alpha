<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 15:23
 */

namespace Voltage\Core\inventory;

use Voltage\Core\VOLTPlayer;

class CustomInventory
{
    private static $inventoryMenuVar = [];

    const TEMP_IM_INSTANCE = 0;
    const TEMP_FMINV_INSTANCE = 1;
    const TEMP_INV_CONTENTS = 2;

    public static function createInventory(int $type = InventoryMenu:: INVENTORY_TYPE_CHEST) : InventoryMenu
    {
        return new InventoryMenu($type);
    }

    /**
     * @param VOLTPlayer $player
     * @return bool
     */
    public static function isOpeningInventoryMenu(VOLTPlayer $player) : bool{
        return array_key_exists($player->getName(), self::$inventoryMenuVar);
    }

    /**
     * @param VOLTPlayer $player
     */
    public static function unsetData(VOLTPlayer $player){
        unset(self::$inventoryMenuVar[$player->getName()]);
    }

    /**
     * @param VOLTPlayer $player
     * @return array
     */
    public static function getData(VOLTPlayer $player) : array
    {
        return self::$inventoryMenuVar[$player->getName()] ?? [];
    }

    /**
     * @param VOLTPlayer $player
     * @param InventoryMenu $menu
     * @param FakeInventory $im
     * @param array $inv
     */
    public static function setData(VOLTPlayer $player, InventoryMenu $menu, FakeInventory $im, array $inv){
        self::$inventoryMenuVar[$player->getName()] = [$menu, $im, $inv];
    }

}