<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:51
 */

namespace Voltage\Core\entity\npc;

use Voltage\Core\Core;
use Voltage\Core\resources\LoadResources;
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
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;

abstract class NPCTransfer extends Human
{
    /**
     * @var int
     */
    public $nameUpdate = 0;

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

        $this->setNameTagVisible(true);
        $this->spawnToAll();
    }

    public function getCustomSkin() : ?Skin
    {
        return null;
    }

    abstract public function onClick(VOLTPlayer $player) : void;

    public function getNameTagUpdate() : string
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

        if (time() >= $this->nameUpdate) {

            if ($this->getNameTag() !== $this->getNameTagUpdate()) {

                $this->setNameTag($this->getNameTagUpdate());
                $this->nameUpdate = (int) time() + (int) 20;

            }

        }

        if (API::getMicroTime() >= $this->moveUpdate) {

            $this->moveUpdate = API::getMicroTime() + 100;

            foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

                $distance = $player->distance($this);

                if ($distance <= 20) {

                    $pk = new MovePlayerPacket();
                    $pk->entityRuntimeId = $this->id;
                    $pk->position = $this->asVector3()->add(0,$this->height ,0);

                    $xdiff = $player->x - $this->x;
                    $zdiff = $player->z - $this->z;

                    $angle = atan2($zdiff, $xdiff);
                    $pk->yaw = (($angle * 180) / M_PI) - 90;

                    $pk->pitch = 5;
                    $pk->headYaw = $pk->yaw;

                    $player->sendDataPacket($pk);

                }

            }

        }

        if (API::getMicroTime() >= $this->particleUpdate) {

            $this->particleUpdate = API::getMicroTime() + 500;

            for ($i = 1; $i <= 10; $i++) {

                $vector = new Vector3($this->x + (rand(-10,+ 10) / 10), $this->y + (rand(-10,+ 10) / 10), $this->z + (rand(-10,+ 10) / 10));

                $this->getLevel()->addParticle(new DustParticle($vector,255,255,0));

            }

        }

        return true;
    }

}