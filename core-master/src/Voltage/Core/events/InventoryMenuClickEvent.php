<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 20:06
 */

namespace Voltage\Core\events;


use Voltage\Core\Core;
use Voltage\Core\inventory\FakeInventory;
use Voltage\Core\inventory\InventoryMenu;
use Voltage\Core\VOLTPlayer;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

class InventoryMenuClickEvent extends PluginEvent
{
    protected $who;
    protected $item;
    protected $inventory;
    protected $transaction;

    public function __construct(VOLTPlayer $who, Item $item, InventoryTransactionPacket $transaction, FakeInventory $inventory)
    {
        $this->who = $who;
        $this->item = $item;
        $this->inventory = $inventory;
        $this->transaction = $transaction;
        parent::__construct(Core::getInstance());
    }

    /**
     * @return VOLTPlayer
     */
    public function getPlayer() : VOLTPlayer
    {
        return $this->who;
    }

    /**
     * @return Item
     */
    public function getItem() : Item
    {
        return $this->item;
    }

    /**
     * @return FakeInventory
     */
    public function getInventory() : FakeInventory
    {
        return $this->inventory;
    }

    /**
     * @return array
     */
    public function getActions() : array
    {
        return $this->transaction->actions;
    }

    /**
     * @return int
     */
    public function getTransactionType() : int
    {
        return $this->transaction->transactionType;
    }

}