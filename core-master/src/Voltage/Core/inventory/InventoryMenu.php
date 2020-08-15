<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 15:25
 */

namespace Voltage\Core\inventory;

use Voltage\Core\Core;
use Voltage\Core\inventory\utils\IMU;
use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

class InventoryMenu
{
    const INVENTORY_TYPE_CHEST = 1;
    const INVENTORY_TYPE_DOUBLE_CHEST = 2;
    const INVENTORY_TYPE_ENCHANTING_TABLE = 3;
    const INVENTORY_TYPE_HOPPER = 4;
    const INVENTORY_TYPE_BREWING_STAND = 5;
    const INVENTORY_TYPE_ANVIL = 6;
    const INVENTORY_TYPE_DISPENSER = 7;
    const INVENTORY_TYPE_DROPPER = 8;
    const INVENTORY_TYPE_BEACON = 9;
    private $title;
    private $type;
    private $item = [];
    private $position;
    private $readonly = true;

    public function __construct(int $type = self::INVENTORY_TYPE_CHEST)
    {
        $this->type = $type;
        $this->title = IMU::getDefaultInventoryName($type);
    }

    /**
     * @param array $items
     * @return InventoryMenu
     */
    public function setItem(array $items): InventoryMenu
    {
        foreach ($items as $index => $item) {

            $this->item[$index] = $item;

        }
        return $this;
    }

    /**
     * @param array $items
     * @return InventoryMenu
     */
    public function setContents(array $items): InventoryMenu
    {
        $this->item = $items;
        return $this;
    }

    /**
     * @param string $title
     * @return InventoryMenu
     */
    public function setName(string $title): InventoryMenu
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param bool $value
     * @return InventoryMenu
     */
    public function setReadonly(bool $value): InventoryMenu
    {
        $this->readonly = $value;
        return $this;
    }

    /**
     * @param int $int
     * @return Item|null
     */
    public function getItem(int $int): ?Item
    {
        return $this->item[$int] ?? null;
    }

    /**
     * @return bool
     */
    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    /**
     * @return Item[]
     */
    public function getContents()
    {
        return $this->item;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return Vector3
     */
    public function getPos(): Vector3
    {
        return $this->position;
    }


    /**
     * @param VOLTPlayer $player
     */
    public function send(VOLTPlayer $player)
    {
        $this->position = clone $player->floor()->add(0, 4);
        $inv = new FakeInventory($this->getPos(), IMU::getInventoryWindowTypes($this->getType()), IMU::getMaxInventorySize($this->getType()), $this->getName());
        $inv->setContents($this->item);
        new sendInventory($player, clone $this, clone $inv);
    }

    /**
     * @param VOLTPlayer $player
     */
    public function close(VOLTPlayer $player)
    {
        if (!CustomInventory::isOpeningInventoryMenu($player)) return;
        $data = CustomInventory::getData($player);
        $player->removeWindow($data[CustomInventory::TEMP_FMINV_INSTANCE]);
        $this->removeBlock($player);
        CustomInventory::unsetData($player);
    }

    /**
     * @param VOLTPlayer $player
     */
    public function removeBlock(VOLTPlayer $player)
    {
        IMU::sendFakeBlock($player, $this->getPos(), Block::AIR);
        if ($this->getType() === self::INVENTORY_TYPE_DOUBLE_CHEST) IMU::sendFakeBlock($player, $this->getPos()->add(1), Block::AIR);
    }

    /**
     * @param VOLTPlayer $player
     */
    public function sendFakeBlock(VOLTPlayer $player)
    {
        $pos = $this->getPos();
        IMU::sendFakeBlock($player, $pos, IMU::getInventoryBlockId($this->getType()));
        if ($this->getType() === self::INVENTORY_TYPE_DOUBLE_CHEST) IMU::sendPairData($player, $pos, $this->getType());
        $tag = new CompoundTag();
        $tag->setString('CustomName', $this->getName());
        IMU::sendTagData($player, $tag, $pos);
    }

    /**
     * @param VOLTPlayer $player
     * @param FakeInventory $inv
     */
    public function setData(VOLTPlayer $player, FakeInventory $inv)
    {
        CustomInventory::setData($player, $this, $inv, $player->getInventory()->getContents());
    }

}