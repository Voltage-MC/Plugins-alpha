<?php

namespace Nlc;

use Nlc\listener\AntiCheatListener;
use Nlc\object\AntiCheat;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TE;

class Main extends PluginBase
{
    const PREFIX = TE::BOLD . TE::YELLOW . "[" . TE::AQUA . "NLC" . TE::YELLOW . "]" . TE::RESET . TE::RED;

    /**
     * @var self
     */
    public static $instance;

    /**
    * @var AntiCheat
    */
    public static $anticheat;

    /**
     * @return self
     */
    public static function getInstance() : self
    {
        return self::$instance;
    }

    /**
     * @return AntiCheat
     */
    public static function getAnticheat() : AntiCheat
    {
        return self::$anticheat;
    }
    
    public function onLoad()
    {
        self::$instance = $this;
        self::$anticheat = new AntiCheat();
    }

    public function onEnable()
    {
        $this->loadListeners();
        $this->loadTasks();
        $this->loadCommands();
    }

    private function loadListeners()
    {
        new AntiCheatListener();
    }

    private function loadTasks()
    {
    }

    private function loadCommands()
    {
        $this->getServer()->getCommandMap()->registerAll("Voltage",
            [
            ]
        );
    }

}