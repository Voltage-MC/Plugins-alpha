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

class GhastPet extends FlyPets
{
    /**
     * The ID of the pet
     */
    const NETWORK_ID = Entity::GHAST;

    /**
     * Pet width
     *
     * @type float
     */
    public $width = 1;

    /**
     * Pet height
     *
     * @type float
     */
    public $height = 1;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
        $this->setScale("0.13");
    }

    public function getSpace(): int
    {
        return 7;
    }

    /**
     * The name of the pet
     *
     * @return string The pet name
     */
    public function getName() : string
    {
        return PetsManager::Ghast;
    }

    /**
     * The speed of the pet
     *
     * @return integer The speed of the pet
     */
    public function getSpeed() : int
    {
        return 2.5;
    }

}