<?php

namespace Voltage\Lobby;

use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TE;
use Voltage\Core\entity\floating\CoinsFloating;
use Voltage\Core\entity\floating\Floating;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use Voltage\Core\task\FloatingTextTask;
use Voltage\Lobby\items\Friend;
use Voltage\Lobby\items\GameSelector;
use Voltage\Lobby\items\Inventory;
use Voltage\Lobby\items\Party;
use Voltage\Lobby\listener\InventoryItemListener;
use Voltage\Lobby\listener\LobbyListener;

class Lobby extends PluginBase
{
    /**
     * @var self
     */
    public static $instance;

    /**
     * @return Lobby
     */
    public static function getInstance() : self
    {
        return self::$instance;
    }
    
    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        $this->loadListeners();
        $this->loadTasks();
        $this->loadCommands();
        $this->loadItems();
        $this->loadEntities();
        $this->loadFloatingText();

        $level = $this->getServer()->getDefaultLevel();
        $level->setTime(37400);
        $level->stopTime();
    }

    public function onDisable()
    {
        $this->unloadEntities();
    }

    private function loadListeners()
    {
        new LobbyListener();
        new InventoryItemListener();
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

    private function loadItems()
    {
        ItemFactory::registerItem(new GameSelector(),true);
        ItemFactory::registerItem(new Friend(),true);
        ItemFactory::registerItem(new Party(),true);
        ItemFactory::registerItem(new Inventory(),true);
    }

    private function loadFloatingText()
    {
        FloatingTextTask::add("FLOATING_TEXT_WELCOME", "world", new Vector3(-11, 105, -34),TE::BOLD . "Welcome to " . TE::LIGHT_PURPLE . "Voltage");
        //FloatingTextTask::add("FLOATING_TEXT_TOP", "world", new Vector3(-16.5, 105, -53.5),TE::BOLD . "LeaderBoard " . TE::LIGHT_PURPLE . "Vote");
        FloatingTextTask::add("FLOATING_TEXT_TOP_HIKABRAIN", "world", new Vector3(20.5, 98, -20.5),TE::BOLD . TE::LIGHT_PURPLE . "Hikabrain\n" . TE::RESET . TE::GRAY .  "LeaderBoard Top Wins");
        new FloatingTextTask();
    }

    private function loadEntities()
    {
        $level = $this->getServer()->getDefaultLevel();
        $spawn = new Vector3(-11, 107, -20);

        for ($i = 1; $i <= 10; $i++) {

            $x = $spawn->x + mt_rand(-100,100);
            $z = $spawn->z +mt_rand(-100,100);
            $y = $level->getHighestBlockAt($x, $z);

            if ($y < 107 and $y > 80) {

                $position = new Vector3($x, $y + 2, $z);
                $nbt = Floating::createNBT($position);
                Entity::createEntity("CoinsFloating",$level,$nbt);

            }

        }

    }

    private function unloadEntities()
    {
        foreach ($this->getServer()->getLevels() as $level) {

            foreach ($level->getEntities() as $entity) {

                if ($entity instanceof CoinsFloating) {

                    $entity->close();

                }

            }

        }

    }

}