<?php

namespace Nlc\hacks;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;

class JetpackHacks extends hacks
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

                if ($player->getMotion()->getY() !== 0) {

                    $this->setVerif(5);

                }

                if (!$this->isVerif()) {

                    //NO TESTED
                    $allowed = 1.3 * ($player->getPing() / 150) < 1.3 ? 1.3 : 1.3 * ($player->getPing() / 150);

                    $to = $event->getTo();
                    $from = $event->getFrom();

                    $fromY = $from->y;
                    $toY = $to->y;

                    if (!$player->isOnGround()) {

                        if (($toY - $fromY) > $allowed) {

                            $event->setCancelled();
                            $player->setAllowFlight(false);
                            $player->setFlying(false);
                            $player->setMotion(new Vector3(0,-5,0));
                            $this->setCheating(3);
                            $player->sendTip(Main::PREFIX . " Your movements Y are too big and too fast.");
                            return false;

                        }

                    }

                }

            }

        }

        return false;
    }

}