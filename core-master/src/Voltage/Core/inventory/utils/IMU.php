<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 15:26
 */

namespace Voltage\Core\inventory\utils;

use Voltage\Core\inventory\InventoryMenu;
use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

class IMU
{
    /**
     * @param VOLTPlayer $player
     * @param CompoundTag $tag
     * @param Vector3 $pos
     */
    public static function sendTagData(VOLTPlayer $player, CompoundTag $tag, Vector3 $pos)
    {
        $writer = new NetworkLittleEndianNBTStream();
        $pk = new BlockActorDataPacket();
        $pk->x = $pos->x;
        $pk->y = $pos->y;
        $pk->z = $pos->z;
        $pk->namedtag = $writer->write($tag);
        $player->dataPacket($pk);
    }

    /**
     * @param VOLTPlayer $player
     * @param Vector3 $pos
     * @param int $type
     */
    public static function sendPairData(VOLTPlayer $player, Vector3 $pos, int $type)
    {
        self::sendFakeBlock($player, $pos->add(1), self::getInventoryBlockId($type));
        $tag = new CompoundTag();
        $tag->setInt('pairx', $pos->x);
        $tag->setInt('pairz', $pos->z);
        self::sendTagData($player, $tag, $pos->add(1));
    }

    /**
     * @param VOLTPlayer $player
     * @param Vector3 $pos
     * @param int $id
     */
    public static function sendFakeBlock(VOLTPlayer $player, Vector3 $pos, int $id)
    {
        $pk = new UpdateBlockPacket();
        $pk->x = (int) $pos->x;
        $pk->y = (int) $pos->y;
        $pk->z = (int) $pos->z;
        $pk->flags = UpdateBlockPacket::FLAG_ALL;
        $pk->blockRuntimeId = BlockFactory::toStaticRuntimeId($id);
        $player->dataPacket($pk);
    }

    /**
     * @param int $type
     * @return int
     */
    public static function getMaxInventorySize(int $type) : int
    {
        switch($type){

            case InventoryMenu::INVENTORY_TYPE_DISPENSER:
            case InventoryMenu::INVENTORY_TYPE_DROPPER:
                return 9;

            case InventoryMenu::INVENTORY_TYPE_BEACON:
                return 1;

            default:
            case InventoryMenu::INVENTORY_TYPE_CHEST:
            case InventoryMenu::INVENTORY_TYPE_ANVIL:
                return 27;

            case InventoryMenu::INVENTORY_TYPE_ENCHANTING_TABLE:
            case InventoryMenu::INVENTORY_TYPE_BREWING_STAND:
            case InventoryMenu::INVENTORY_TYPE_HOPPER:
                return 5;

            case InventoryMenu::INVENTORY_TYPE_DOUBLE_CHEST:
                return 54;

        }

    }

    /**
     * @param int $type
     * @return int
     */
    public static function getInventoryBlockId(int $type) : int
    {
        switch($type){

            case InventoryMenu::INVENTORY_TYPE_DISPENSER:
                return Block::DISPENSER;

            case InventoryMenu::INVENTORY_TYPE_DROPPER:
                return Block::DROPPER;

            case InventoryMenu::INVENTORY_TYPE_BEACON:
                return Block::BEACON;

            default:
            case InventoryMenu::INVENTORY_TYPE_CHEST:
            case InventoryMenu::INVENTORY_TYPE_DOUBLE_CHEST:
                return Block::CHEST;

            case InventoryMenu::INVENTORY_TYPE_ANVIL:
                return Block::ANVIL;

            case InventoryMenu::INVENTORY_TYPE_ENCHANTING_TABLE:
                return Block::ENCHANTING_TABLE;

            case InventoryMenu::INVENTORY_TYPE_BREWING_STAND:
                return Block::BREWING_STAND_BLOCK;

            case InventoryMenu::INVENTORY_TYPE_HOPPER:
                return Block::HOPPER_BLOCK;

        }

    }

    /**
     * @param int $type
     * @return int
     */
    public static function getInventoryWindowTypes(int $type) : int
    {
        switch($type){

            case InventoryMenu::INVENTORY_TYPE_DISPENSER:
                return WindowTypes::DISPENSER;

            case InventoryMenu::INVENTORY_TYPE_DROPPER:
                return WindowTypes::DROPPER;

            case InventoryMenu::INVENTORY_TYPE_BEACON:
                return WindowTypes::BEACON;

            case InventoryMenu::INVENTORY_TYPE_CHEST:
            case InventoryMenu::INVENTORY_TYPE_DOUBLE_CHEST:
                return WindowTypes::CONTAINER;

            case InventoryMenu::INVENTORY_TYPE_ANVIL:
                return WindowTypes::ANVIL;

            case InventoryMenu::INVENTORY_TYPE_ENCHANTING_TABLE:
                return WindowTypes::ENCHANTMENT;

            case InventoryMenu::INVENTORY_TYPE_BREWING_STAND:
                return WindowTypes::BREWING_STAND;

            case InventoryMenu::INVENTORY_TYPE_HOPPER:
                return WindowTypes::HOPPER;

        }
    }

    /**
     * @param int $type
     * @return string
     */
    public static function getDefaultInventoryName(int $type) : string
    {
        return Block::get(self::getInventoryBlockId($type))->getName();
    }

}