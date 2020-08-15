<?php

namespace Voltage\Core;

use Voltage\Core\base\Server;
use Voltage\Core\commands\player\CoinsCommand;
use Voltage\Core\commands\player\HubCommand;
use Voltage\Core\commands\player\LobbyCommand;
use Voltage\Core\commands\settings\CheckBlockCommand;
use Voltage\Core\commands\settings\FriendCommand;
use Voltage\Core\commands\settings\PetCommand;
use Voltage\Core\commands\settings\TpwCommand;
use Voltage\Core\commands\settings\XyzCommand;
use Voltage\Core\commands\staff\NpcCommand;
use Voltage\Core\commands\staff\RankCommand;
use Voltage\Core\commands\staff\utils\ban\BanCommand;
use Voltage\Core\commands\staff\utils\ban\PardonCommand;
use Voltage\Core\commands\staff\utils\kick\KickCommand;
use Voltage\Core\entity\Chest;
use Voltage\Core\entity\floating\CoinsFloating;
use Voltage\Core\entity\floating\VoltFloating;
use Voltage\Core\entity\npc\FactionNpc;
use Voltage\Core\entity\npc\HikaNpc;
use Voltage\Core\entity\npc\LobbyNpc;
use Voltage\Core\entity\npc\PvpNpc;
use Voltage\Core\listener\InventoryListener;
use Voltage\Core\manager\PetsManager;
use Voltage\Core\task\UploadServerTask;
use pocketmine\entity\Entity;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TE;
use Voltage\Core\commands\settings\LangCommand;
use Voltage\Core\entity\Fireworks as FireworksEntity;
use Voltage\Core\items\Fireworks as FireworksItem;
use Voltage\Core\listener\PlayerListener;
use Voltage\Core\utils\MySQL;
use Voltage\Core\task\RequestUploadTask;

class Core extends PluginBase
{
    /**
     * @var self
     */
    public static $instance;

    public static $path = "plugins/core/src/Voltage/Core/resources/";

    const PREFIX = TE::BOLD . TE::WHITE . " » " . TE::RESET;

    private $restart = false;

    /**
     * @return Core
     */
    public static function getInstance() : self
    {
        return self::$instance;
    }

    /**
     * @return array|\SplFileInfo[]|string
     */
    public static function getResourcesPath()
    {
        return self::$path;
    }

    public static function getPrefix() : string
    {
        return self::PREFIX;
    }

    public function setRestart()
    {
        $this->restart = true;
    }
    
    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        //Font Big "http://patorjk.com/software/taag/#p=display&f=Big&t=VOLTAGE"
        $gameapi =
            "\n" . TE::RED . "  _____ ____  _____  ______    " . TE::YELLOW . "    __      ______  _   _______       _____ ______ ".
            "\n" . TE::RED . " / ____/ __ \|  __ \|  ____|   " . TE::YELLOW . "    \ \    / / __ \| | |__   __|/\   / ____|  ____|".
            "\n" . TE::RED . "| |   | |  | | |__) | |__      " . TE::YELLOW . "     \ \  / / |  | | |    | |  /  \ | |  __| |__   ".
            "\n" . TE::RED . "| |   | |  | |  _  /|  __|     " . TE::YELLOW . "      \ \/ /| |  | | |    | | / /\ \| | |_ |  __|  ".
            "\n" . TE::RED . "| |___| |__| | | \ \| |____    " . TE::YELLOW . "       \  / | |__| | |____| |/ ____ \ |__| | |____ ".
            "\n" . TE::RED . " \_____\____/|_|  \_\______|   " . TE::YELLOW . "        \/   \____/|______|_/_/    \_\_____|______|".
            "\n" ;

        $this->getLogger()->info($gameapi);

        $this->loadListeners();
        $this->loadTasks();
        $this->unloadCommands();
        $this->loadCommands();
        $this->loadItems();
        $this->loadEntities();
        MySQL::addTables();
        Server::startServer(Core::getInstance()->getServer()->getPort());
        $this->getServer()->getNetwork()->setName(TE::BOLD . TE::LIGHT_PURPLE . "Voltage" . Core::getPrefix() . TE::RESET . TE::GRAY .  "Network");
    }

    public function onDisable()
    {
        if ($this->restart) {

            Server::restartServer(Core::getInstance()->getServer()->getPort());

        } else {

            Server::stopServer(Core::getInstance()->getServer()->getPort());

        }
    }

    private function loadListeners()
    {
        new PlayerListener();
        new InventoryListener();
    }

    private function loadTasks()
    {
        new RequestUploadTask();
        new UploadServerTask();
    }

    private function loadCommands()
    {
        $this->getServer()->getCommandMap()->registerAll("Voltage",
            [
                new CoinsCommand(),
                new HubCommand(),
                new LobbyCommand(),

                new CheckBlockCommand(),
                new FriendCommand(),
                new LangCommand(),
                new PetCommand(),
                new TpwCommand(),
                new XyzCommand(),

                new NpcCommand(),
                new RankCommand(),

                new KickCommand(),
                new PardonCommand(),
                new BanCommand(),
            ]
        );
    }

    private function loadItems()
    {
        ItemFactory::registerItem(new FireworksItem(),true);
    }

    private function loadEntities()
    {
        Entity::registerEntity(FireworksEntity::class,true);
        Entity::registerEntity(FactionNpc::class, true);
        Entity::registerEntity(VoltFloating::class, true);
        Entity::registerEntity(CoinsFloating::class, true);
        Entity::registerEntity(PvpNpc::class, true);
        Entity::registerEntity(LobbyNpc::class, true);
        Entity::registerEntity(HikaNpc::class, true);
        Entity::registerEntity(Chest::class, true);

        PetsManager::registerPets();
    }

    private function unloadCommands()
    {
        $commands = [
            //"gamemode",
            "mixer",
            "gc",
            "title",
            //"tell",
            //"w",
            //"msg",
            "give",
            "ban",
            "ban-ip",
            "pardon",
            "pardon-ip",
            //"tp",
            //"teleport",
            "kill",
            "enchant",
            "xp",
            "kick",
            "me",
            "say",
            "list",
            "banlist",
            "spawnpoint",
            "extractplugin",
            "makeplugin",
            "genplugin",
            "pl",
            "ver",
            "version",
            "about",
            "plugins",
            "transferserver",
            "makeserver",
            "particle",
            "effect",
            "difficulty",
            "checkperm",
            "defaultgamemode",
            //"op",
            "deop",
            "mixer",
            "reload",
            "seed"
        ];

        $map = Core::getInstance()->getServer()->getCommandMap();

        foreach ($commands as $cmd) {

            $command = $map->getCommand($cmd);

            if ($command !== null) {

                $command->setLabel("old_" . $cmd);
                $map->unregister($command);

            }

        }

    }

}