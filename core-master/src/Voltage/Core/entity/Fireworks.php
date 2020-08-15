<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 21:20
 */

namespace Voltage\Core\entity;

use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use Voltage\Core\items\Fireworks as EntityFireworks;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class Fireworks extends Entity
{

    /* ADD FIREWORK ENTITY
            $fireworks = ItemFactory::get(Item::FIREWORKS);
            $fireworks->addExplosion(Fireworks::TYPE_STAR, Fireworks::COLOR_RED);
            $nbt = Entity::createBaseNBT(new Vector3($x, $y, $z), null, lcg_value() * 360, 90);
            $rocket = Entity::createEntity("Fireworks", $player->getLevel(), $nbt, $fireworks);
            $rocket->spawnToAll();
     */

    public const NETWORK_ID = self::FIREWORKS_ROCKET;
    public $width = 0.25;
    public $height = 0.25;
    /** @var int */
    protected $lifeTime = 0;

    /**
     * Fireworks constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     * @param EntityFireworks|null $fireworks
     */
    public function __construct(Level $level, CompoundTag $nbt, ?EntityFireworks $fireworks = null)
    {
        parent::__construct($level, $nbt);

        if($fireworks !== null && $fireworks->getNamedTagEntry("Fireworks") instanceof CompoundTag) {

            $this->propertyManager->setItem(self::DATA_MINECART_DISPLAY_BLOCK, $fireworks);
            $this->setLifeTime($fireworks->getRandomizedFlightDuration());

        }

        $level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LAUNCH);
    }

    protected function tryChangeMovement() : void
    {
        $this->motion->x *= 1.15;
        $this->motion->y += 0.04;
        $this->motion->z *= 1.15;
    }

    /**
     * @param int $tickDiff
     * @return bool
     */
    public function entityBaseTick(int $tickDiff = 1) : bool
    {
        if($this->closed) {

            return false;
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);

        if($this->doLifeTimeTick()) {

            $hasUpdate = true;

        }

        return $hasUpdate;
    }

    /**
     * @param int $life
     */
    public function setLifeTime(int $life) : void
    {
        $this->lifeTime = $life;
    }

    /**
     * @return bool
     */
    protected function doLifeTimeTick() : bool
    {
        if(!$this->isFlaggedForDespawn() and --$this->lifeTime < 0) {

            $this->doExplosionAnimation();
            $this->flagForDespawn();

            return true;
        }

        return false;
    }

    protected function doExplosionAnimation() : void
    {
        $fireworks = $this->propertyManager->getItem(self::DATA_MINECART_DISPLAY_BLOCK);

        if($fireworks === null){

            return;

        }

        $fireworks_nbt = $fireworks->getNamedTag()->getCompoundTag("Fireworks");

        if($fireworks_nbt === null){

            return;
        }

        $explosions = $fireworks_nbt->getListTag("Explosions");

        if($explosions === null){

            return;
        }

        /** @var CompoundTag $explosion */
        foreach($explosions->getAllValues() as $explosion) {

            switch($explosion->getByte("FireworkType")){

                case EntityFireworks::TYPE_SMALL_SPHERE:

                    $this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_BLAST);
                    break;

                case EntityFireworks::TYPE_HUGE_SPHERE:

                    $this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LARGE_BLAST);
                    break;

                case EntityFireworks::TYPE_STAR:

                case EntityFireworks::TYPE_BURST:

                case EntityFireworks::TYPE_CREEPER_HEAD:

                    $this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_TWINKLE);
                    break;

            }

        }

        $this->broadcastEntityEvent(ActorEventPacket::FIREWORK_PARTICLES);
    }

}