<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 26/05/2019
 * Time: 12:43
 */

namespace Voltage\Core\entity\pets;

use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;
use pocketmine\entity\Creature;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;

abstract class Pets extends Creature
{
    /**
     * @var null
     */
    protected $owner = null;

    public $oldPositon;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
        $this->setHealth(20);
    }

    /**
     * @param VOLTPlayer $player
     */
    public function setOwner(VOLTPlayer $player)
    {
        $this->owner = $player;
    }

    public function getOwner() : VOLTPlayer
    {
        return $this->owner;
    }

    /**
     * @param Player $player
     */
    public function spawnTo(Player $player) : void
    {
        if (!$this->closed && $player->isOnline()) {

            if (!isset($this->hasSpawned[$player->getId()])) {

                $pk = new AddActorPacket();
                $pk->entityRuntimeId = $this->getID();
                $pk->type = static::NETWORK_ID;
                $pk->position = $this->asVector3();
                $pk->yaw = $this->yaw;
                $pk->pitch = $this->pitch;
                Core::getInstance()->getServer()->broadcastPacket(Core::getInstance()->getServer()->getOnlinePlayers(),$pk);
                $this->hasSpawned[$player->getId()] = $player;
                $this->oldPositon = $this->asVector3();

            }

        }

    }

    /**
     * @return int
     */
    public function getSpace() : int
    {
        return 5;
    }

    /**
     * @return int
     */
    public function getSpeed() : int
    {
        return 1;
    }

    /**
     * Updates the pet
     *
     * @param  integer $currentTick The current tick
     * @return boolean              Whether or not the update was true
     */
    public function onUpdate(int $currentTick) : bool
    {
        parent::onUpdate($currentTick);

        if(!($this->owner instanceof Player) || !$this->getOwner()->isOnline()) {

            $this->flagForDespawn();
            return false;

        }

        if($this->closed){

            return false;

        }

        if ($this->distance($this->getOwner()) >= 15) {

            $this->returnToOwner();
            return true;

        }

        $player = $this->getOwner();

        $this->addMovement($player);
        return true;
    }

    /**
     * @param VOLTPlayer $player
     * @return bool
     */
    public function addMovement(VOLTPlayer $player) : bool
    {
        $x = $player->x - $this->x;
        $y = $player->y - $this->y;
        $z = $player->z - $this->z;

        $diff = abs($x) + abs($z);
        $d = $x ** 2 + $z ** 2;

        if ($d < $this->getSpace()) {

            $this->motion->x = 0;
            $this->motion->y = 0;
            $this->motion->z = 0;
            return true;

        } else if ($diff > 0) {

            $this->moveY();

            $this->motion->x = $this->getSpeed() * 0.15 * ($x / $diff);
            $this->motion->z = $this->getSpeed() * 0.15 * ($z / $diff);
            $this->yaw = -atan2($x / $diff, $z / $diff) * 180 / M_PI;

            $this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x ** 2 + $z ** 2)));

        }


        $this->oldPositon = new Vector3($this->x, 0, $this->z);
        return true;
    }

    public function moveY()
    {
    }

    public function returnToOwner() : void
    {
        $len = rand(2, 6);
        $x = (-sin(deg2rad( $this->getOwner()->yaw))) * $len  +  $this->getOwner()->getX();
        $z = cos(deg2rad( $this->getOwner()->yaw)) * $len  +  $this->getOwner()->getZ();

        $this->setPosition(new Vector3($x,$this->getOwner()->getY() + 2,$z));
    }

    /**
     * Get teh time interval
     *
     * @param  string $started The started time
     * @return integer         The time value
     */
    public static function getTimeInterval(string $started)
    {
        return round((strtotime(date('Y-m-d H:i:s')) - strtotime($started)) /60);
    }

    public function close(): void
    {
        parent::close();
        $pk = RemoveEntityPacket::create($this->getId());
        Core::getInstance()->getServer()->broadcastPacket(Core::getInstance()->getServer()->getOnlinePlayers(),$pk);
    }

}