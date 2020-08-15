<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 26/05/2019
 * Time: 12:26
 */

namespace Voltage\Core\manager;

use Voltage\Core\entity\pets\CreeperPet;
use Voltage\Core\entity\pets\VexPet;
use Voltage\Core\entity\pets\GhastPet;
use Voltage\Core\entity\pets\OcelotPet;
use Voltage\Core\entity\pets\Pets;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Entity;
use pocketmine\level\Position;

class PetsManager
{
    public static $players = [];
    public static $pets = [];

    const ENTITY =
        [
            self::Creeper,
            self::Ghast,
            self::Ocelot,
            self::Vex
        ];

    const Creeper = "CreeperPet";
    const Ghast = "GhastPet";
    const Ocelot = "OcelotPet";
    const Vex = "VexPet";

    public static function create($type, Position $source, VOLTPlayer $player) : Pets
    {

        $nbt = Entity::createBaseNBT($source);
        $entity = Entity::createEntity($type, $source->getLevel(), $nbt);

        if ($entity instanceof Pets) {

            return $entity;

        }

    }

    public static function createPet(VOLTPlayer $player, $type = "") : ?Pets
    {
        $len = rand(8, 12);
        $x = (-sin(deg2rad($player->yaw))) * $len  + $player->getX();
        $z = cos(deg2rad($player->yaw)) * $len  + $player->getZ();
        $y = $player->getLevel()->getHighestBlockAt($x, $z);

        if (!in_array($type, self::ENTITY)) {

            $type = self::ENTITY[array_rand(self::ENTITY)];

        }

        $source = new Position($x , $y + 2, $z, $player->getLevel());

        $pet = self::create($type, $source, $player);

        if ($pet instanceof Pets) {

            $pet->setOwner($player);
            $pet->spawnToAll();

        }

        return $pet;
    }

    public static function givePet(VOLTPlayer $player, string $pet = "") : bool
    {
        if(!isset(self::$pets[strtolower($player->getName())])) {

            self::$pets[strtolower($player->getName())] = PetsManager::createPet($player, $pet);
            self::$pets[strtolower($player->getName())]->returnToOwner();
            self::$players[strtolower($player->getName())] = self::$pets[strtolower($player->getName())]->getName();
            return true;

        }

        return false;
    }

    public static function removePet(VOLTPlayer $player, bool $unset = false) : bool
    {
        if(isset(self::$pets[strtolower($player->getName())])) {

            self::$pets[strtolower($player->getName())]->close();
            unset(self::$pets[strtolower($player->getName())]);

            if($unset) {

                unset(self::$players[strtolower($player->getName())]);

            }

            return true;
        }
        return false;
    }

    public static function findPet(VOLTPlayer $player) : bool
    {
        if(isset(self::$pets[strtolower($player->getName())])) {

            self::$pets[strtolower($player->getName())]->returnToOwner();
            return true;

        }

        return false;
    }

    public static function registerPets()
    {
        Entity::registerEntity(GhastPet::class, true);
        Entity::registerEntity(OcelotPet::class, true);
        Entity::registerEntity(VexPet::class, true);
        Entity::registerEntity(CreeperPet::class, true);
    }

}