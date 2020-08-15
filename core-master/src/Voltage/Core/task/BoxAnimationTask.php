<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 09/06/2019
 * Time: 18:20
 */

namespace Voltage\Core\task;

use Voltage\Core\Core;
use Voltage\Core\entity\Chest;
use Voltage\Core\VOLTPlayer;
use pocketmine\item\Item;
use pocketmine\level\particle\LavaParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddItemEntityPacket;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\scheduler\Task;

class BoxAnimationTask extends Task
{
    public $entity;
    public $player;
    public $time = 50;

    public $y = 0;

    public function __construct(Chest $entity, VOLTPlayer $player)
    {
        $this->entity = $entity;
        $this->player = $player;
        Core::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20 / 4);
    }

    public function onRun(int $currentTick)
    {
        $time = $this->time;
        $player = $this->player;
        $entity = $this->entity;

        if ($this->y >= 0 and $this->y < 2) {

            $this->y += 0.1;

        }

        if ($time > 0) {

            if ($this->time == 30) {

                for ($i = 0; $i < 361; $i += 1.1) {

                    $getX = $entity->getX() + (2 * cos($i));
                    $getZ = $entity->getZ() + (2 * sin($i));
                    $v3 = new Vector3($getX, $entity->getY() + $this->y , $getZ);
                    $entity->getLevel()->addParticle(new LavaParticle($v3));

                }

            } else if ($this->time == 25) {

                $item = [Item::GOLD_INGOT, Item::DIAMOND, Item::IRON_INGOT, Item::BRICK];

                for ($i = 1; $i <= 15; $i++) {

                    $pk = new AddItemEntityPacket();
                    $pk->entityRuntimeId = 9000 + $i;
                    $pk->position = new Vector3($entity->getX(), $entity->getY() + 1, $entity->getZ());
                    $pk->item = Item::get($item[array_rand($item)]);

                    $pitch = mt_rand(-20, 0);
                    $yaw = mt_rand(0, 360);
                    $pk->motion = new Vector3(-sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI), -sin($pitch / 180 * M_PI) * 0.9 + 1, cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI));
                    Core::getInstance()->getServer()->broadcastPacket($entity->getViewers(),$pk);

                }

            }

            if ($this->time < 25) {

                if ($this->y >= 0) {

                    $this->y = 0;

                }

            }

        } else {

            for ($i = 1; $i <= 15; $i++) {

                $pk = new RemoveEntityPacket();
                $pk->entityUniqueId = 9000 + $i;
                Core::getInstance()->getServer()->broadcastPacket(Core::getInstance()->getServer()->getOnlinePlayers(),$pk);

            }

            unset(Chest::$animation[array_search($entity->getId(),Chest::$animation)]);
            Core::getInstance()->getScheduler()->cancelTask($this->getTaskId());

        }

        $this->time--;

        $pk = new MovePlayerPacket();
        $pk->entityRuntimeId = $entity->getId();
        $pk->position = $entity->asVector3()->add(0,2 + $this->y ,0);
        $pk->yaw = 0;
        $pk->headYaw = 0;
        $pk->pitch = -40;
        Core::getInstance()->getServer()->broadcastPacket($entity->getViewers(),$pk);

    }

}