<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity;

use Voltage\Core\Core;
use Voltage\Core\resources\LoadResources;
use Voltage\Core\task\BoxAnimationTask;
use Voltage\Core\utils\API;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;

class Chest extends Human
{
    /**
     * @var int
     */
    public $moveUpdate = 0;

    /**
     * @var int
     */
    public $particleUpdate = 0;

    public static $animation = [];

    public $width = 1;
    public $height = 1;

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
        return new Skin("Chest",LoadResources::PNGtoBYTES("chest"),"","geometry.chest",LoadResources::getGeometry());
    }

    public function onClick(VOLTPlayer $player) : void
    {
        /*if (!$this->isActivate()) {

            new BoxAnimationTask($this, $player);
            self::$animation[] = $this->getId();

        } else {

            //UNE ANIME

        }*/

    }

    public function getCustomNameTag() : string
    {
        return "§l§9Box";
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

        if (API::getMicroTime() >= $this->moveUpdate and !$this->isActivate()) {

            $this->moveUpdate = API::getMicroTime() + 100;

            foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

                $distance = $player->distance($this);

                $pk = new MovePlayerPacket();
                $pk->entityRuntimeId = $this->id;
                $pk->position = $this->asVector3()->add(0, $this->height + 1, 0);
                $pk->yaw = 0;
                $pk->headYaw = 0;

                if ($distance <= 4) {

                    $pk->pitch = -40;

                } else {

                    $pk->pitch = 0;

                }

                $player->sendDataPacket($pk);

            }

        }

        if (API::getMicroTime() >= $this->particleUpdate) {

            $this->particleUpdate = API::getMicroTime() + 500;

            for ($i = 1; $i <= 10; $i++) {

                $vector = new Vector3($this->x + (rand(-10,+ 10) / 10), $this->y + (rand(-10,+ 10) / 10), $this->z + (rand(-10,+ 10) / 10));

                $this->getLevel()->addParticle(new FlameParticle($vector));

            }

        }

        return true;
    }

    public function isActivate() : bool
    {
        return in_array($this->getId(), self::$animation);
    }

}