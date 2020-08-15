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

class CreeperPet extends WalkingPets
{
    /**
     * The ID of the pet
     */
    const NETWORK_ID = Entity::CREEPER;

    /**
     * Pet width
     *
     * @type float
     */
    public $width = 0.6;

    /**
     * Pet height
     *
     * @type float
     */
    public $height = 1.7;

    /**
     * The name of the pet
     *
     * @return string The pet name
     */
    public function getName() : string
    {
        return PetsManager::Creeper;
    }

    /**
     * The speed of the pet
     *
     * @return integer The speed of the pet
     */
    public function getSpeed() : int
    {
        return 2;
    }

}