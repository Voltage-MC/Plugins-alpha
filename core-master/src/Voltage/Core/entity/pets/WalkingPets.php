<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 31/05/2019
 * Time: 03:00
 */

namespace Voltage\Core\entity\pets;

use pocketmine\block\Block;
use pocketmine\math\Vector3;

abstract class WalkingPets extends Pets
{

    /**
     * @var float
     */
    public $gravity = 0.08;

    const NOREALBLOCKS =
        [
            Block::STONE_SLAB,
            Block::WOODEN_SLAB,
            Block::STONE_SLAB2,
            Block::OAK_STAIRS,
            Block::COBBLESTONE_STAIRS,
            Block::BRICK_STAIRS,
            Block::STONE_BRICK_STAIRS,
            Block::NETHER_BRICK_STAIRS,
            Block::SPRUCE_STAIRS,
            Block::BIRCH_STAIRS,
            Block::JUNGLE_STAIRS,
            Block::QUARTZ_STAIRS,
            Block::ACACIA_STAIRS,
            Block::DARK_OAK_STAIRS,
            Block::RED_SANDSTONE_STAIRS,
            Block::PURPUR_STAIRS,
            53,
        ];

    public function moveY()
    {
        $direction = $this->getDirectionVector();

        $positon = new Vector3($this->x + $direction->x,$this->y, $this->z + $direction->z);
        $block = $this->getLevel()->getBlock($positon);

        if ($block->isSolid() or $this->isCollidedHorizontally or $this->isUnderwater()) {

            $block2 = $this->getLevel()->getBlock($positon->add(0,1,0));

            if ($block2->canBeFlowedInto()) {

                if (in_array($block->getId(), self::NOREALBLOCKS)) {

                    if ($this->y - round($this->y) === 0) {

                        $this->motion->y = 0;

                    } else {

                        $this->motion->y = 0.25;

                    }

                } else {

                    $this->motion->y = 0.25;

                }

            } else {

                $this->motion->y = 0;

            }

        } else {

            $block = $this->getLevel()->getBlock($positon->add(0,-1,0));

            if (!$block->isSolid()) {

                $this->motion->y = -$this->gravity * 4;

            } else {

                $this->motion->y = 0;

            }

        }

    }

}