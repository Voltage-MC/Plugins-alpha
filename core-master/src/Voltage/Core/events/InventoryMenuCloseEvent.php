<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 16:33
 */
namespace Voltage\Core\events;

use Voltage\Core\Core;
use Voltage\Core\inventory\FakeInventory;
use Voltage\Core\VOLTPlayer;
use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;

class InventoryMenuCloseEvent extends PluginEvent implements Cancellable
{
    protected $who;
    protected $inventory;
    protected $windowId;

    /**
     * InventoryMenuCloseEvent constructor.
     * @param VOLTPlayer $who
     * @param FakeInventory $inventory
     * @param int $windowId
     */
    public function __construct(VOLTPlayer $who, FakeInventory $inventory, int $windowId)
    {
        $this->who = $who;
        $this->inventory = $inventory;
        $this->windowId = $windowId;
        parent::__construct(Core::getInstance());
    }

    /**
     * @return VOLTPlayer
     */
    public function getPlayer(): VOLTPlayer
    {
        return $this->who;
    }

    /**
     * @return FakeInventory
     */
    public function getInventory(): FakeInventory
    {
        return $this->inventory;
    }

    /**
     * @return int
     */
    public function getWindowId(): int
    {
        return $this->windowId;
    }

}