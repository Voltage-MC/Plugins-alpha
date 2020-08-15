<?php


namespace Nlc\listener;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityMotionEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class AntiCheatListener implements Listener
{
    public function __construct()
    {
        Main::getInstance()->getServer()->getPluginManager()->registerEvents($this,Main::getInstance());
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);
        $oldhash = null;
        
        $observer = new Observer($player);
        Main::getAnticheat()->setObserver($hash,$observer);
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            Main::getAnticheat()->delObserver($hash);

        }

    }

    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $observer->onMove($event);

                }

            }

        }

    }

    public function onEntityMotionEvent(EntityMotionEvent $event)
    {
        $entity = $event->getEntity();
        $hash = spl_object_hash($entity);
        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    if($entity instanceof Player) {

                        $observer->onMotion($event);

                    }

                }

            }

        }

    }

    public function onEntityRegainHealthEvent(EntityRegainHealthEvent $event)
    {
        if ($event->getRegainReason() != EntityDamageEvent::CAUSE_MAGIC and $event->getRegainReason() != EntityDamageEvent::CAUSE_CUSTOM) {

            $entity = $event->getEntity();
            $hash = spl_object_hash($entity);
            if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

                if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                    $observer = Main::getAnticheat()->getObsever()[$hash];

                    if ($observer instanceof Observer) {

                        if ($entity instanceof Player) {

                            $observer->onRegainHealth($event);

                        }

                    }
                }

            }

        }

    }

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        $hash = spl_object_hash($entity);
        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if (isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    if ($entity instanceof Player) {

                        $observer->onDamaged($event);

                    }

                }

            }

        }

        if ($event instanceof EntityDamageByEntityEvent) {

            $damager = $event->getDamager();
            $hash = spl_object_hash($damager);

            if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

                if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                    $observer = Main::getAnticheat()->getObsever()[$hash];

                    if ($observer instanceof Observer) {

                        if($damager instanceof Player and $entity instanceof Player) {

                            if ($event->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {

                                $observer->onEntityDamagedEntity($event);

                            }

                        }

                    }

                }

            }

        }

    }

    public function onShootBow(EntityShootBowEvent $event)
    {
        $entity = $event->getEntity();
        $hash = spl_object_hash($event->getEntity());

        if($entity instanceof Player) {

            if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

                if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                    $observer = Main::getAnticheat()->getObsever()[$hash];

                    if ($observer instanceof Observer) {

                        $observer->onShootBow($event);

                    }

                }

            }

        }

    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $observer->onDeath($event);

                }

            }

        }

    }

    public function onRespawn(PlayerRespawnEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $observer->onRespawn($event);

                }

            }

        }

    }

    public function onEntityTeleportEvent(EntityTeleportEvent $event)
    {
        $entity = $event->getEntity();
        $hash = spl_object_hash($event->getEntity());

        if($entity instanceof Player) {

            if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

                if(isset(Main::getAnticheat()->getObsever()[$hash])) {

                    $observer = Main::getAnticheat()->getObsever()[$hash];

                    if ($observer instanceof Observer) {

                        $observer->onTeleport($event);

                    }

                }

            }

        }

    }

    public function onInteractBreak(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if ($event->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK) {

            if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

                if (isset(Main::getAnticheat()->getObsever()[$hash])) {

                    $observer = Main::getAnticheat()->getObsever()[$hash];

                    if ($observer instanceof Observer) {

                        $observer->break_time = floor(microtime(true) * 20);

                    }

                }

            }

        }

    }

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if (isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $observer->onBreak($event);

                }

            }

        }

    }

    public function onPacketReceive(DataPacketReceiveEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if (isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $observer->onPacketReceive($event);

                }

            }

        }

    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if (isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $observer->onInteract($event);

                }

            }

        }

    }

    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();
        $hash = spl_object_hash($player);

        if (array_key_exists($hash , Main::getAnticheat()->getObsever())) {

            if (isset(Main::getAnticheat()->getObsever()[$hash])) {

                $observer = Main::getAnticheat()->getObsever()[$hash];

                if ($observer instanceof Observer) {

                    $reason = $event->getReason();

                    switch ($reason) {

                        case "Flying is not enabled on this server":
                            $observer->onKick($event);
                            return;

                    }

                }

            }

        }

    }

}