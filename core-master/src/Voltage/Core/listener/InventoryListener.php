<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 16:18
 */

namespace Voltage\Core\listener;

use Voltage\Core\Core;
use Voltage\Core\events\InventoryMenuClickEvent;
use Voltage\Core\events\InventoryMenuCloseEvent;
use Voltage\Core\inventory\CustomInventory;
use Voltage\Core\VOLTPlayer;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

class InventoryListener implements Listener
{
    private $plugin;

    /**
     * InventoryListener constructor.
     */
    public function __construct()
    {
        $this->plugin = Core::getInstance();
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this,$this->getPlugin());
    }

    /**
     * @return Core
     */
    private function getPlugin() : Core
    {
        return $this->plugin;
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @throws \ReflectionException
     */
    public function onReceive(DataPacketReceiveEvent $event)
    {
        $pk = $event->getPacket();
        $player = $event->getPlayer();

        if ($player instanceof VOLTPlayer) {

            if ($pk instanceof ContainerClosePacket && CustomInventory::isOpeningInventoryMenu($player)) {

                $data = CustomInventory::getData($player);

                $ev = new InventoryMenuCloseEvent($player, $data[CustomInventory::TEMP_FMINV_INSTANCE], $pk->windowId);
                $ev->call();

                if($ev->isCancelled()){

                    $data[CustomInventory::TEMP_IM_INSTANCE]->removeBlock($player);
                    $data[CustomInventory::TEMP_IM_INSTANCE]->send($player);

                } else {

                    $data[CustomInventory::TEMP_IM_INSTANCE]->close($player);

                }


            } else if ($pk instanceof InventoryTransactionPacket) {

                if (CustomInventory::isOpeningInventoryMenu($player) && array_key_exists(0,$pk->actions)) {

                    $data = CustomInventory::getData($player);
                    $action = $pk->actions[0];

                    if($data[CustomInventory::TEMP_IM_INSTANCE]->isReadonly()){

                        $data[CustomInventory::TEMP_IM_INSTANCE]->close($player);
                        $player->getInventory()->setContents($data[CustomInventory::TEMP_INV_CONTENTS]);
                        $event->setCancelled();

                    }

                    $ev = new InventoryMenuClickEvent($player, $action->oldItem->getId() === Item::AIR ? $action->newItem : $action->oldItem, $pk, $data[CustomInventory::TEMP_FMINV_INSTANCE]);
                    $ev->call();

                }

            }

        }

    }

}