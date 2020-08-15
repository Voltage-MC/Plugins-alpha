<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 21:24
 */

namespace Voltage\Core\items;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\sound\GenericSound;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;

class Fireworks extends Item
{
    /** @var float */
    public const BOOST_POWER = 1;
    public const TYPE_SMALL_SPHERE = 0;
    public const TYPE_HUGE_SPHERE = 1;
    public const TYPE_STAR = 2;
    public const TYPE_CREEPER_HEAD = 3;
    public const TYPE_BURST = 4;
    public const COLOR_BLACK = "\x00";
    public const COLOR_RED = "\x01";
    public const COLOR_DARK_GREEN = "\x02";
    public const COLOR_BROWN = "\x03";
    public const COLOR_BLUE = "\x04";
    public const COLOR_DARK_PURPLE = "\x05";
    public const COLOR_DARK_AQUA = "\x06";
    public const COLOR_GRAY = "\x07";
    public const COLOR_DARK_GRAY = "\x08";
    public const COLOR_PINK = "\x09";
    public const COLOR_GREEN = "\x0a";
    public const COLOR_YELLOW = "\x0b";
    public const COLOR_LIGHT_AQUA = "\x0c";
    public const COLOR_DARK_PINK = "\x0d";
    public const COLOR_GOLD = "\x0e";
    public const COLOR_WHITE = "\x0f";

    /**
     * Fireworks constructor.
     * @param int $meta
     */
    public function __construct(int $meta = 0)
    {
        parent::__construct(self::FIREWORKS, $meta, "Fireworks");
    }

    /**
     * @return int
     */
    public function getFlightDuration(): int
    {
        return $this->getExplosionsTag()->getByte("Flight", 1);
    }

    /**
     * @return int
     */
    public function getRandomizedFlightDuration(): int
    {
        return ($this->getFlightDuration() + 1) * 10 + mt_rand(0, 5) + mt_rand(0, 6);
    }

    /**
     * @param int $duration
     */
    public function setFlightDuration(int $duration): void
    {
        $tag = $this->getExplosionsTag();
        $tag->setByte("Flight", $duration);
        $this->setNamedTagEntry($tag);
    }

    /**
     * @return CompoundTag
     */
    protected function getExplosionsTag(): CompoundTag
    {
        return $this->getNamedTag()->getCompoundTag("Fireworks") ?? new CompoundTag("Fireworks");
    }

    /**
     * @param int $type
     * @param string $color
     * @param string $fade
     * @param int $flicker
     * @param int $trail
     */
    public function addExplosion(int $type, string $color, string $fade = "", int $flicker = 0, int $trail = 0): void
    {
        $explosion = new CompoundTag();

        $explosion->setByte("FireworkType", $type);
        $explosion->setByteArray("FireworkColor", $color);
        $explosion->setByteArray("FireworkFade", $fade);
        $explosion->setByte("FireworkFlicker", $flicker);
        $explosion->setByte("FireworkTrail", $trail);

        $tag = $this->getExplosionsTag();
        $explosions = $tag->getListTag("Explosions") ?? new ListTag("Explosions");

        $explosions->push($explosion);
        $tag->setTag($explosions);
        $this->setNamedTagEntry($tag);
    }

    /**
     * @param Player $player
     * @param Block $blockReplace
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @return bool
     */
    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        $nbt = Entity::createBaseNBT($blockReplace->add(0.5, 0, 0.5), new Vector3(0.001, 0.05, 0.001), lcg_value() * 360, 90);
        $entity = Entity::createEntity("FireworksRocket", $player->getLevel(), $nbt, $this);

        if ($entity instanceof Entity) {

            --$this->count;
            $entity->spawnToAll();
            return true;

        }

        return false;
    }

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     * @return bool
     */
    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {

        $motion = new Vector3(
            (-sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI) * self::BOOST_POWER),
            (-sin($player->pitch / 180 * M_PI) * self::BOOST_POWER),
            (cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI) * self::BOOST_POWER)
        );
        $nbt = Entity::createBaseNBT($player, $motion->subtract(0, 0.1, 0), lcg_value() * 360, 90);
        $entity = Entity::createEntity("FireworksRocket", $player->getLevel(), $nbt, $this);

        if ($entity instanceof Entity) {

            --$this->count;
            $entity->spawnToAll();
            $player->setMotion($motion);
            $player->getLevel()->addSound(new GenericSound($player, LevelEventPacket::EVENT_SOUND_BLAZE_SHOOT));

            return true;
        }

        return true;
    }

}