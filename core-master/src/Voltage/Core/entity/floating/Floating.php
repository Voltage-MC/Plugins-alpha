<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity\floating;

use Voltage\Core\Core;
use Voltage\Core\resources\LoadResources;
use Voltage\Core\utils\API;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;

abstract class Floating extends Human
{
    /**
     * @var int
     */
    public $yawMove = 0;

    /**
     * @var int
     */
    public $yMove = 0;

    /**
     * @var int
     */
    public $less = false;

    /**
     * @var int
     */
    public $moveUpdate = 0;

    /**
     * @var int
     */
    public $particleUpdate = 0;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);

        if (!is_null($this->getCustomSkin())) {

            $this->setSkin($this->getCustomSkin());

        }

        $this->setNameTag($this->getCustomNameTag());
        $this->setNameTagVisible(true);
        $this->spawnToAll();
    }

    public function getCustomSkin() : ?Skin
    {
        return null;
    }

    abstract public function onClick(VOLTPlayer $player) : void;

    public function getCustomNameTag() : string
    {
        return "";
    }

    public static function createNBT(Vector3 $pos) : CompoundTag
    {
        $nbt = self::createBaseNBT($pos->floor()->add(0.5,0,0.5), null, 0,0);
        $nbt->setTag(clone LoadResources::getSkinTag());
        return $nbt;
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function attack(EntityDamageEvent $event) : void
    {
        if ($event instanceof EntityDamageByEntityEvent) {

            $player = $event->getDamager();

            if ($player instanceof VOLTPlayer) {

                if ($player->getInventory()->getItemInHand()->getId() === Item::BONE and $player->hasPermission("owner")) {

                    $this->close();

                } else {

                    $this->onClick($player);

                }

            }

        }

    }

    /**
     * @param int $currentTick
     * @return bool
     */
    public function onUpdate(int $currentTick) : bool
    {
        parent::onUpdate($currentTick);

        if (API::getMicroTime() >= $this->moveUpdate) {

            $this->moveUpdate = API::getMicroTime() + 100;

            $this->yawMove += 5;

            if ($this->yawMove > 360) {

                $this->yawMove = 0;

            }

            if ($this->less) {

                $this->yMove -= 0.02;

            } else {

                $this->yMove += 0.02;

            }

            if ($this->yMove <= - 0.3) {

                $this->less = false;

            }

            if ($this->yMove >= 0.3) {

                $this->less = true;

            }

            $pk = new MovePlayerPacket();
            $pk->entityRuntimeId = $this->id;
            $pk->position = $this->asVector3()->add(0, 2 + $this->yMove ,0);
            $pk->yaw = $this->yawMove;
            $pk->pitch = $this->pitch;
            $pk->headYaw = $pk->yaw;
            Core::getInstance()->getServer()->broadcastPacket($this->getViewers(), $pk);


        } else if (API::getMicroTime() >= $this->particleUpdate) {

            $this->particleUpdate = API::getMicroTime() + 500;

            for ($i = 1; $i <= 5; $i++) {

                $vector = new Vector3($this->x + (rand(-10,+ 10) / 10), $this->y + (rand(-10,+ 10) / 10), $this->z + (rand(-10,+ 10) / 10));

                $this->getLevel()->addParticle(new DustParticle($vector,255,255,0));

            }

        }

        return true;
    }

}