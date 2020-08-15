<?php

namespace Nlc\object;

use Nlc\Main;

class AntiCheat
{
    private $observer = [];

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        Main::getInstance()->getLogger()->info(Main::PREFIX . " > NiqueLesCheateurs enabled");
    }

    public function getObsever() : array
    {
        return $this->observer;
    }

    public function setObserver($hash, Observer $observer)
    {
        $this->observer[$hash] = $observer;
    }

    public function delObserver($hash)
    {
        unset($this->observer[$hash]);
    }
}