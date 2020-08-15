<?php

namespace Nlc\hacks;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class AutoclickHacks extends hacks
{
    public $observer;
    public $count = [];

    public function __construct(Observer $observer)
    {
        $this->observer = $observer;
    }

    public function getObserver() : Observer
    {
        return $this->observer;
    }

    public function onRun(Cancellable $event) : bool
    {
        if (parent::onRun($event)) {

            if ($event instanceof DataPacketReceiveEvent) {

                $pk = $event->getPacket();

                if ($pk::NETWORK_ID === InventoryTransactionPacket::NETWORK_ID) {

                    if ($pk->transactionType === InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY) {

                        return $this->check($event);

                    }

                } else if ($pk::NETWORK_ID === LevelSoundEventPacket::NETWORK_ID) {

                    if ($pk->sound === LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE) {

                        return $this->check($event);

                    }

                }

            }

            if ($event instanceof PlayerInteractEvent) {

                $action = $event->getAction();

                switch ($action) {
                    case 0:
                    case 1:
                    case 3:
                    case 4:
                        return false;
                }

                return $this->check($event);

            }

        }

        return false;
    }

    private function check(Cancellable $event) : bool
    {
        $player = $event->getPlayer();
        $amount = 20;

        $time = microtime(true);

        array_unshift($this->count, $time);

        $cps = count(array_filter($this->count, static function (float $t) use ($time) : bool {
            return ($time - $t) <= 1;
        }));

        if ($cps >= $amount) {

            $event->setCancelled();
            $this->setCheating(1);
            $player->sendTip(Main::PREFIX . " You have a too high keystroke (maximum 20cps).");
            return true;

        }

        return false;

    }

}