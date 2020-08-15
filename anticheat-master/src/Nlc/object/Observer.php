<?php

namespace Nlc\object;

use Nlc\hacks\AutoclickHacks;
use Nlc\hacks\FastbreakHacks;
use Nlc\hacks\FlyHacks;
use Nlc\hacks\hacks;
use Nlc\hacks\JetpackHacks;
use Nlc\hacks\SpeedHacks;
use Nlc\hacks\WalkwaterHacks;
use Nlc\Main;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;

class Observer
{
    /**
     * @var Player
     */
    public $player;

    /**
     * @var int
     */
    public $breakTimes = null;

    //HACKS

    /**
     * @var SpeedHacks
     */
    private $speedHack;
    /**
     * @var AutoclickHacks
     */
    private $autoclickHack;
    /**
     * @var FastbreakHacks
     */
    private $fastbreakHack;
    /**
     * @var FlyHacks
     */
    private $flyHack;
    /**
     * @var WalkwaterHacks
     */
    private $walkwaterHack;
    /**
     * @var WalkwaterHacks
     */
    private $jetpackHack;

    public function __construct(Player $player)
    {
        $this->player = $player;

        //HACKS
        $this->init();
        $this->loadHacks();
    }

    public function init()
    {

    }

    public function loadHacks()
    {
        $this->speedHack = new SpeedHacks($this);
        $this->autoclickHack = new AutoclickHacks($this);
        $this->fastbreakHack = new FastbreakHacks($this);
        $this->flyHack = new FlyHacks($this);
        $this->walkwaterHack = new WalkwaterHacks($this);
        $this->jetpackHack = new JetpackHacks($this);
    }

    public function getPlayer() : Player
    {
        return $this->player;
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $this->speedHack->onRun($event);
        $this->flyHack->onRun($event);
        $this->walkwaterHack->onRun($event);
        $this->jetpackHack->onRun($event);
    }

    public function onPacketReceive(DataPacketReceiveEvent $event)
    {
        $this->autoclickHack->onRun($event);
        $this->flyHack->onRun($event);
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        if ($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK) {

            $this->breakTimes = floor(microtime(true) * 20);

        }

        $this->autoclickHack->onRun($event);
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $this->fastbreakHack->onRun($event);
    }

    public function onDamaged(EntityDamageEvent $event)
    {
    }

    public function OnMotion(EntityMotionEvent $event) : void
    {
    }

    public function onRegainHealth(EntityRegainHealthEvent $event)
    {
    }

    public function onEntityDamagedEntity(EntityDamageByEntityEvent$event) : void
    {
    }

    public function onShootBow(EntityShootBowEvent $event)
    {

    }

    public function onDeath(PlayerDeathEvent $event) : void
    {

    }

    public function onRespawn(PlayerRespawnEvent $event) : void
    {

    }
    
    public function onTeleport(EntityTeleportEvent $event) : void
    {

    }

    public function onKick(PlayerKickEvent $event) : void
    {
        $this->flyHack->onRun($event);
    }

    public function reportStaff(string $type)
    {
        $player = $this->getPlayer();

        //SEND ALLSTAFF
    }

}