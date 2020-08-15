<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 11/06/2019
 * Time: 07:05
 */
namespace Voltage\Pvp;

use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use Voltage\Pvp\commands\SeeStatsCommand;
use Voltage\Pvp\commands\StatsCommand;
use Voltage\Pvp\game\Ffa;
use Voltage\Pvp\items\GameSelector;
use Voltage\Pvp\listener\PvpListener;

class Pvp extends PluginBase
{
    /**
     * @var self
     */
    public static $instance;

    public static $ffa;

    public $gapple = [];

    /**
     * @return Pvp
     */
    public static function getInstance() : self
    {
        return self::$instance;
    }

    /**
     * @return Ffa
     */
    public static function getFfa() : Ffa
    {
        return self::$ffa;
    }

    public function onLoad()
    {
        self::$instance = $this;
        self::$ffa = new Ffa();
    }

    public function onEnable()
    {
        $this->loadItems();
        $this->loadListeners();
        $this->loadCommands();
        $this->loadLevel();
        Provider::create();
    }

    private function loadItems()
    {
        ItemFactory::registerItem(new GameSelector(), true);
    }

    private function loadListeners()
    {
        new PvpListener();
    }

    private function loadCommands()
    {
        $this->getServer()->getCommandMap()->registerAll("Voltage",
            [
                new StatsCommand(),
                new SeeStatsCommand(),
            ]
        );
    }

    public function loadLevel()
    {
        $this->getServer()->loadLevel("Gapple");
    }

}