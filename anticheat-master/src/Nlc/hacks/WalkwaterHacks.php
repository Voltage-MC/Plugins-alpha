<?php

namespace Nlc\hacks;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\block\Block;
use pocketmine\block\StillWater;
use pocketmine\block\Water;
use pocketmine\block\WaterLily;
use pocketmine\event\player\PlayerMoveEvent;

class WalkwaterHacks extends hacks
{
    public $observer;

    public $count = 0;

    public $oldY = 0;

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

                $block_below = $player->getLevel()->getBlock($player->subtract(0, 1, 0));

                if ($block_below instanceof StillWater or $block_below instanceof Water or $block_below instanceof WaterLily) {

                    $block_above = $player->getLevel()->getBlock($player->add(0, 1, 0));

                    if ($block_above->getId() === Block::AIR) {

                        for ($x = $block_below->getX() - 1; $x <= $block_below->getX() + 1; $x++) {

                            for ($z = $block_below->getZ() - 1; $z <= $block_below->getZ() + 1; $z++) {

                                $block = $block_below->add($x, 0, $z);

                                if (!$block instanceof StillWater and !$block instanceof Water and !$block instanceof WaterLily) {

                                    return false;

                                }

                            }

                        }

                        $to = $event->getTo()->getY();
                        $from = $event->getFrom()->getY();
                        $distance = $from - $to;

                        if ($distance < 0.3 and $distance > - 0.3) {

                            if ($this->oldY == round($distance, 3)) {

                                $event->setCancelled();
                                $this->setCheating(1);
                                $player->sendTip(Main::PREFIX . " You're on the water but you're not swimming.");
                                return true;

                            }

                            $this->oldY = round($distance, 3);

                        }

                    }

                }

            }

        }

        return false;
    }

}