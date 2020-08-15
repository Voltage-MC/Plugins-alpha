<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 31/05/2019
 * Time: 03:07
 */

namespace Voltage\Core\entity\pets;

use pocketmine\math\Vector3;

abstract class FlyPets extends Pets
{
    /**
     * @var float
     */
    public $gravity = 0.0045;

    public function moveY()
    {
        $direction = $this->getDirectionVector();

        $positon = new Vector3($this->x + $direction->x,$this->y - 2, $this->z + $direction->z);
        $this->motion->y = 0;

        $block = $this->getLevel()->getBlock($positon);

        if (!$block->isSolid()) {

            $this->motion->y += $this->motion->y = -$this->gravity * 10;

        }

        $block2 = $this->getLevel()->getBlock($positon->add(0,1,0));

        if ($block2->isSolid()) {

            $this->motion->y += 1;

        }

        $block3 = $this->getLevel()->getBlock($positon->add(0,2,0));

        if ($block3->isSolid()) {

            $this->motion->y += 1;

        }

    }

}