<?php

namespace Nlc\hacks;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\AdventureSettingsPacket;

class FlyHacks extends hacks
{
    public $observer;
    public $count = 0;

    public function __construct(Observer $observer)
    {
        $this->observer = $observer;
    }

    public function getObserver() : Observer
    {
        return $this->observer;
    }

    public function onRun($event) : bool
    {
        if (parent::onRun($event)) {

            $player = $this->getObserver()->getPlayer();

            if ($player->isCreative() or $player->isSpectator() or $player->getAllowFlight()) {

                return false;

            }

            if ($event instanceof PlayerMoveEvent) {

                $to = $event->getTo();
                $from = $event->getFrom();

                if (!$player->isOnGround()) {

                    $this->count++;

                    if ($this->count > 100 * 7) {

                        if ($to->getY() < $from->getY()) {

                            $event->setCancelled();
                            $player->setAllowFlight(false);
                            $player->setFlying(false);
                            $this->setCheating(3);
                            $player->sendTip(Main::PREFIX . " You've been in the air too long.");
                            return true;

                        }

                    }

                } else {

                    $this->count = 0;

                }

            }

            if ($event instanceof DataPacketReceiveEvent) {

                $pk = $event->getPacket();

                if ($pk instanceof AdventureSettingsPacket) {

                    $isFlying = $pk->getFlag(AdventureSettingsPacket::FLYING);

                    if($isFlying and !$player->getAllowFlight()){

                        $event->setCancelled();
                        $player->setAllowFlight(false);
                        $player->setFlying(false);
                        $this->setCheating(3);
                        $player->sendTip(Main::PREFIX . " You have activated the fly.");
                        return true;

                    }

                }

            }

            if ($event instanceof PlayerKickEvent) {

                $event->setCancelled();
                $player->setAllowFlight(false);
                $player->setFlying(false);
                $this->setCheating(3);
                $player->sendTip(Main::PREFIX . " You have activated the fly.");
                return true;

            }

        }

        return false;
    }

}