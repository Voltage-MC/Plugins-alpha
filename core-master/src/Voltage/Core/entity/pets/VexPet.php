<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 26/05/2019
 * Time: 13:51
 */

namespace Voltage\Core\entity\pets;

use Voltage\Core\manager\PetsManager;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class VexPet extends FlyPets
{
    /**
     * The ID of the pet
     */
    const NETWORK_ID = Entity::VEX;

    /**
     * Pet width
     *
     * @type float
     */
    public $width = 0.4;

    /**
     * Pet height
     *
     * @type float
     */
    public $height = 0.8;

    public function getSpace(): int
    {
        return 4;
    }

    /**
     * The name of the pet
     *
     * @return string The pet name
     */
    public function getName() : string
    {
        return PetsManager::Vex;
    }

    /**
     * The speed of the pet
     *
     * @return integer The speed of the pet
     */
    public function getSpeed() : int
    {
        return 3.5;
    }

}