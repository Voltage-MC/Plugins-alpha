<?php

namespace Nlc\hacks;

use Nlc\Main;
use Nlc\object\Observer;
use pocketmine\entity\Effect;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Cancellable;

class FastbreakHacks extends hacks
{
    public $observer;

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

            if ($event instanceof BlockBreakEvent) {

                $player = $event->getPlayer();

                if ($player->getGamemode() === 1) {

                    return false;

                }

                if (!$event->getInstaBreak()) {

                    do {

                        if ($this->getObserver()->breakTimes === null){

                            $event->setCancelled();
                            $this->setCheating(5);
                            $player->sendTip(Main::PREFIX . " You have have broke without impact.");
                            return true;
                            break;

                        }

                        $target = $event->getBlock();
                        $item = $event->getItem();

                        $expectedTime = ceil($target->getBreakTime($item) * 20);

                        if ($player->hasEffect(Effect::HASTE)) {

                            $expectedTime *= 1 - (0.2 * $player->getEffect(Effect::HASTE)->getEffectLevel());

                        }

                        if ($player->hasEffect(Effect::MINING_FATIGUE)) {

                            $expectedTime *= 1 + (0.3 * $player->getEffect(Effect::MINING_FATIGUE)->getEffectLevel());

                        }

                        $expectedTime -= 1; //1 tick compensation

                        $actualTime = ceil(microtime(true) * 20) - $this->getObserver()->breakTimes;

                        if ($actualTime < $expectedTime * 0.80) {

                            $event->setCancelled();
                            $this->setCheating(5);
                            $player->sendTip(Main::PREFIX . " You broke up way too fast.");
                            return true;
                            break;

                        }

                    }while(false);
                }

            }

        }

        return false;
    }

}