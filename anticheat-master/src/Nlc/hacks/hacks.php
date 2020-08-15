<?php

namespace Nlc\hacks;

use pocketmine\event\Cancellable;

abstract class hacks
{
    private $cheatTime = null;
    private $verifTime = null;

    public function onRun(Cancellable $event) : bool
    {
        if ($this->isCheating()) {

            $event->setCancelled();
            return false;

        }

        return true;
    }

    public function isCheating() : bool
    {
        if ($this->cheatTime === null) return false;
        return time() <= $this->cheatTime;
    }

    public function setCheating(int $time)
    {
        $this->cheatTime = time() + $time;
    }

    public function isVerif() : bool
    {
        if ($this->verifTime === null) return false;
        return time() <= $this->verifTime;
    }

    public function setVerif(int $time)
    {
        $this->verifTime = time() + $time;
    }

}