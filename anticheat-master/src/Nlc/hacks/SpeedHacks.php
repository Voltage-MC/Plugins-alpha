<?php

namespace Nlc\hacks;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;

class SpeedHacks extends hacks
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

                if ($player->getMotion()->getX() != 0 or $player->getMotion()->getX() != 0) {

                    $this->setVerif(5);

                }

                if (!$this->isVerif()) {

                    $to = $event->getTo();
                    $from = $event->getFrom();
                    $distance = abs($to->distance($from));

                    //NO TESTED
                    $allowed = 0.50 * ($player->getPing() / 150) < 0.50 ? 0.50 : 0.50 * ($player->getPing() / 150);

                    if ($player->isCreative() or $player->isSpectator() or $player->getAllowFlight()) {

                        return false;

                    }

                    if ($player->getEffect(Effect::SPEED) !== null) {

                        if ($player->getEffect(Effect::SPEED)->getEffectLevel() === 1) {

                            $allowed *= 1.2;

                        } else {

                            $allowed = $player->getEffect(Effect::SPEED)->getEffectLevel() * 1.2 * $allowed;

                        }

                    }

                    if ($distance >= $allowed) {

                        $y = [$from->y,$to->y];
                        $minY = min($y);
                        $maxY = max($y);

                        if (($maxY - $minY) > 0.5) {

                            $this->count = 0;
                            return false;

                        }

                        if ($player->getPing() >= 300) {

                            $event->setCancelled();
                            $player->sendTip(Main::PREFIX . " Your ping is " . $player->getPing());
                            return true;

                        } else {

                            if ($this->count > 10) {

                                $event->setCancelled();
                                $player->setMotion(new Vector3(0,-5,0));
                                $this->setCheating(3);
                                $player->sendTip(Main::PREFIX . " Your movements X and Z are too big and too fast.");
                                $this->count = 0;
                                return true;

                            }

                            $this->count++;

                        }

                    } else {

                        $this->count = 0;

                    }

                }

            }

        }

        return false;
    }

}