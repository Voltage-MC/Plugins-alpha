<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 12/05/2019
 * Time: 00:06
 */

namespace Voltage\Lobby\listener;

use Voltage\Core\events\InventoryMenuClickEvent;
use Voltage\Core\inventory\CustomInventory;
use Voltage\Core\inventory\InventoryMenu;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use Voltage\Lobby\items\Inventory;
use Voltage\Lobby\LBPlayer;
use Voltage\Lobby\Lobby;

class InventoryItemListener implements Listener
{
    private $plugin;

    /**
     * PlayerListener constructor.
     */
    public function __construct()
    {
        $this->plugin = Lobby::getInstance();
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this,$this->getPlugin());
    }

    /**
     * @return Lobby
     */
    private function getPlugin() : Lobby
    {
        return $this->plugin;
    }

    public function onInv(InventoryMenuClickEvent $event)
    {
        $player = $event->getPlayer();
        $name = $event->getInventory()->getName();

        if ($player instanceof LBPlayer) {

            switch ($name) {

                case "§bInventory":

                    switch ($event->getItem()->getId()) {

                        case Item::CHEST:

                            $this->market($player);

                            break;

                        case Item::SHIELD:

                            $this->settings($player);

                            break;

                    }
                    break;

                case "§eMarket":

                    switch ($event->getItem()->getId()) {

                        case -161:

                            Inventory::addUI($player);

                            break;

                        case Item::ARROW and $event->getItem()->getDamage() === 5:



                            break;
                    }
                    break;

                case "§cSettings":

                    switch ($event->getItem()->getId()) {

                        case Item::DYE:

                            $player->setHide();
                            $this->settings($player);

                            break;

                        case -161:

                            Inventory::addUI($player);
                            break;

                    }

                    break;

            }

        }

    }

    public function market(LBPlayer $player)
    {
        $inv = new InventoryMenu(InventoryMenu::INVENTORY_TYPE_DOUBLE_CHEST);
        $inv->setName("§eMarket");

        $pets = Item::get(Item::MOB_HEAD,5,1);
        $pets->setCustomName("§r§5Pets");
        $pets->setLore(["",$player->messageToTranslate("PET_DESCRIPTION_1"),$player->messageToTranslate("PET_DESCRIPTION_2"),"",$player->messageToTranslate("RIGHT_CLICK")]);

        $back = Item::get(-161,5,1);
        $back->setCustomName("§r§cBack");
        $back->setLore(["",$player->messageToTranslate("RIGHT_CLICK")]);

        $next = Item::get(Item::ARROW,2,1);
        $next->setCustomName("§r§9Next");
        $next->setLore(["",$player->messageToTranslate("RIGHT_CLICK")]);

        $items =
            [
                0 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                1 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                2 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                3 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                4 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                5 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                6 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                7 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                8 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                9 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                10 => $pets,
                17 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                18 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                26 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                27 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                35 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                36 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                37 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                38 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                39 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                40 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                41 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                42 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                43 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                44 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                45 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                46 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                47 => $back,
                48 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                49 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                50 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                51 => $next,
                52 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                53 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),

            ];

        $inv->setItem($items);
        $inv->send($player);
    }

    public function settings(LBPlayer $player)
    {
        $inv = CustomInventory::createInventory(InventoryMenu::INVENTORY_TYPE_DISPENSER);
        $inv->setName("§cSettings");

        if (!$player->getHide()) {

            $hide = Item::get(Item::DYE,8,1);
            $hide->setCustomName("§r§7Hide Player");

        } else {

            $hide = Item::get(Item::DYE,10,1);
            $hide->setCustomName("§r§aShow Player");

        }

        $hide->setLore(["",$player->messageToTranslate("HIDE_DESCRIPTION_1"),$player->messageToTranslate("HIDE_DESCRIPTION_2"),"",$player->messageToTranslate("RIGHT_CLICK")]);

        $back = Item::get(-161,5,1);
        $back->setCustomName("§r§cBack");
        $back->setLore(["",$player->messageToTranslate("RIGHT_CLICK")]);

        $next = Item::get(Item::ARROW,2,1);
        $next->setCustomName("§r§9Next");
        $next->setLore(["",$player->messageToTranslate("RIGHT_CLICK")]);

        $items =
            [
                0 => $hide,
                6 => $back,
                7 => Item::get(Item::INVISIBLE_BEDROCK,0,1)->setCustomName(""),
                8 => $next,
            ];

        $inv->setItem($items);
        $inv->send($player);
    }

}