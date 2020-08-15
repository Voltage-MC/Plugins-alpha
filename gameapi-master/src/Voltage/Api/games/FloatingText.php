<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 01/05/2019
 * Time: 21:39
 */

namespace Voltage\Api\games;


use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddPlayerPacket;
use pocketmine\network\mcpe\protocol\PlayerSkinPacket;
use pocketmine\utils\UUID;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class FloatingText
{
    public function add(GAPlayer $player, string $message, array $array = array())
    {
        $pk = new AddPlayerPacket();
        $pk->uuid = $uuid = UUID::fromRandom();
        $pk->username = "";
        $pk->entityRuntimeId = 15;
        $pk->position = new Vector3(Game::getInstance()->getData()["Floating-spawn"][0], Game::getInstance()->getData()["Floating-spawn"][1], Game::getInstance()->getData()["Floating-spawn"][2]);
        $pk->item = ItemFactory::get(ItemIds::AIR, 0, 0);
        $flags = (
            (1 << Entity::DATA_FLAG_CAN_SHOW_NAMETAG) |
            (1 << Entity::DATA_FLAG_ALWAYS_SHOW_NAMETAG) |
            (1 << Entity::DATA_FLAG_IMMOBILE)
        );
        $pk->metadata = [
            Entity::DATA_FLAGS =>   [Entity::DATA_TYPE_LONG,   $flags],
            Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $player->messageToTranslate($message, $array)],
            Entity::DATA_SCALE =>   [Entity::DATA_TYPE_FLOAT,  0.01],
            Entity::DATA_BOUNDING_BOX_HEIGHT => [Entity::DATA_TYPE_FLOAT, 0.3], Entity::DATA_BOUNDING_BOX_WIDTH => [Entity::DATA_TYPE_FLOAT, 0.3]
        ];

        $skinPk = new PlayerSkinPacket();
        $skinPk->uuid = $uuid;
        $skinPk->skin = new Skin("Standard_Custom", str_repeat("\x00", 8192));
    }

}