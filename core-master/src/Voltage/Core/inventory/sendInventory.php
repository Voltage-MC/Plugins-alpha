<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 15:47
 */

namespace Voltage\Core\inventory;

use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;
use pocketmine\scheduler\Task;

class sendInventory extends Task
{
    private $player;
    private $menu;
    private $inventory;

    public function __construct(VOLTPlayer $player, InventoryMenu $menu, FakeInventory $inventory)
    {
        $this->player = $player;
        $this->menu = $menu;
        $this->inventory = $inventory;

        $menu->sendFakeBlock($player);
        Core::getInstance()->getScheduler()->scheduleDelayedTask($this, 10);
    }

    public function onRun(int $tick): void
    {
        $this->menu->setData($this->player, $this->inventory);
        $this->player->addWindow($this->inventory);
    }

}