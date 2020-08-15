<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 00:28
 */

namespace Voltage\Api;

use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TE;
use Voltage\Api\games\Bed;
use Voltage\Api\listener\GameListener;
use Voltage\Api\task\WaitingGameTask;
use Voltage\Api\games\Solo;
use Voltage\Api\games\Teams;
use Voltage\Api\games\OneSpawn;
use Voltage\Api\games\Map;
use Voltage\Api\games\Chest;
use Voltage\Core\resources\LoadResources;
use Voltage\Core\utils\Network;

class Game extends PluginBase
{
    /**
     * @var self
     */
    public static $instance;

    /**
     * @var Teams
     */
    public static $teams;

    /**
     * @var Solo
     */
    public static $solo;

    /**
     * @var OneSpawn
     */
    public static $onespawn;

    /**
     * @var Map
     */
    public static $map;

    /**
     * @var Bed
     */
    public static $bed;

    /**
     * @var Chest
     */
    public static $chest;

    /**
     * @var string[]
     */
    public $data = [];

    /**
     * @var int
     */
    public $mode = GAPlayer::MODE_WAITING;

    /**
     * @var array
     */
    public $blocks = [];

    /**
     * @var bool
     */
    public $finish = false;

    /**
     * @return Game
     */
    public static function getInstance() : self
    {
        return self::$instance;
    }

    /**
     * @return Teams
     */
    public static function getTeams() : Teams
    {
    	return self::$teams;
    }

    /**
     * @return Solo
     */
    public static function getSolo() : Solo
    {
    	return self::$solo;
    }

    /**
     * @return OneSpawn
     */
    public static function getOneSpawn() : OneSpawn
    {
    	return self::$onespawn;
    }

    /**
     * @return Map
     */
    public static function getMap() : Map
    {
    	return self::$map;
    }

    /**
     * @return Bed
     */
    public static function getBed() : Bed
    {
        return self::$bed;
    }
    
    /**
     * @return Chest
     */
    public static function getChest() : Chest
    {
        return self::$chest;
    }

    /**
     * @return array
     */
    public function getData() : array
    {
    	return $this->data;
    }

    /**
     * @return int
     */
    public function getMode() : int
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode(int $mode)
    {
        $this->mode = $mode;
    }

    public function onLoad()
    {
        self::$instance = $this;
        self::$teams = new Teams();
        self::$solo = new Solo();
        self::$onespawn = new Onespawn();
        self::$map = new Map();
        self::$bed = new Bed();
        self::$chest = new Chest();
    }

    public function onEnable()
    {
        $gameapi =
            "\n" . TE::RED . "   _____          __  __ ______    " . TE::YELLOW . "    __      ______  _   _______       _____ ______ ".
            "\n" . TE::RED . "  / ____|   /\   |  \/  |  ____|   " . TE::YELLOW . "    \ \    / / __ \| | |__   __|/\   / ____|  ____|".
            "\n" . TE::RED . " | |  __   /  \  | \  / | |__      " . TE::YELLOW . "     \ \  / / |  | | |    | |  /  \ | |  __| |__   ".
            "\n" . TE::RED . " | | |_ | / /\ \ | |\/| |  __|     " . TE::YELLOW . "      \ \/ /| |  | | |    | | / /\ \| | |_ |  __|  ".
            "\n" . TE::RED . " | |__| |/ ____ \| |  | | |____    " . TE::YELLOW . "       \  / | |__| | |____| |/ ____ \ |__| | |____ ".
            "\n" . TE::RED . "  \_____/_/    \_\_|  |_|______|   " . TE::YELLOW . "        \/   \____/|______|_/_/    \_\_____|______|".
            "\n" ;

        $this->getLogger()->info($gameapi);

        @mkdir($this->getDataFolder());

        if(!file_exists($this->getDataFolder()."arena.yml")){

            $this->saveResource('arena.yml');

        }

        new GameListener();
        new WaitingGameTask();
        $this->addData();
        $this->checkConfig();
    }

    public function addData()
    {
        $c = new Config($this->getDataFolder() . "arena.yml", Config::YAML);

        foreach ($c->getAll() as $name => $value) {

            $this->data[$name] = $value;

        }

    }

    public function checkConfig()
    {
        $all =
            [
                "map",
                "author",
                "minslots",
                "maxslots",
                "starttime",
                "gametime",
                "finishtime",
                "lobby"
            ];

        foreach ($all as $data) {

            if (!isset($this->data[$data])) {

                $this->getLogger()->critical("The config is not well configured, the ".$data." is missing");
                $this->getServer()->Forceshutdown();
                break;

            }

        }

    }

    /**
     * @param string $name
     * @param $bytes
     * @return string
     */
    public function getHead(string $name, $bytes) : string
    {
        $name = strtolower($name);

        $dir = "/var/www/html/heads/" . strtolower($name) . ".png";

        if (!file_exists($dir)) {

            $img = LoadResources::getHeadBYTEStoIMG($bytes);
            @imagepng($img, $dir);
            @imagedestroy($img);

        }

        return "http://5.196.141.146/heads/" . strtolower($name) . ".png";

    }

    public function removeHead(string $name)
    {
        $dir = "/var/www/html/heads/";
        foreach (scandir($dir) as $key => $value) {

            if (!in_array($value,array(".",".."))) {

                if (!is_dir($dir . $value)) {

                    if ($value === strtolower($name) . ".png") unlink($dir . $value);

                }

            }

        }

    }

    /**
     * @return int
     */
    public function getMinSlots() : int
    {
        return $this->data["minslots"];
    }

    /**
     * @return int
     */
    public function getMaxSlots() : int
    {
        return $this->data["maxslots"];
    }

    /**
     * @return bool
     */
    public function isStarted() : bool
    {
        if($this->getMode() == GAPlayer::MODE_GAME){

            return true;

        }

        return false;
    }

     /**
	 * @return GAPlayer[]
	 */
    public function getAllPlayerIsJoin() : array
    {
        $join = [];

        foreach ($this->getServer()->getOnlinePlayers() as $player) {

            if ($player instanceof GAPlayer) {

                if ($player->isJoin()) $join[] = $player;

            }

        }

        return $join;
    }

    /**
	 * @return GAPlayer[]
	 */
    public function getPlaying() :array
    {
        $playing = [];

        foreach (Game::getInstance()->getServer()->getLoggedInPlayers() as $player) {

            if ($player instanceof GAPlayer) {

                if ($player->getMode() === GAPlayer::MODE_PLAYER) {

                    $playing[] = $player;

                }

            }

        }

        return $playing;
    }

    /**
     * @return bool
     */
    public function isFull() : bool
    {
        return count(Game::getInstance()->getAllPlayerIsJoin()) > $this->data["maxslots"];
    }

    /**
     * @return Vector3
     */
    public function getLobby() : Vector3
    {
        return new Vector3($this->data["lobby"][0],$this->data["lobby"][1],$this->data["lobby"][2]);
    }

    /**
     * @return string
     */
    public function getMapName() : string
    {
        return $this->data["map"];
    }

    /**
     * @return string
     */
    public function getAuthor() : string
    {
        return $this->data["author"];
    }

    /**
     * @return int
     */
    public function getMaxTime() : int
    {
        return $this->data["gametime"];
    }

    public function setFinish()
    {
        if ($this->isStarted()) {

            $this->finish = true;

        }

    }

    /**
     * @return bool
     */
    public function isFinish() : bool
    {
        if ($this->isStarted()) {

            return $this->finish;

        }

        return false;
    }

    public function setFinishAll()
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {

            $player->transfer(Network::IP,Network::NAME["Lobby"]);

        }
        $this->getServer()->getDefaultLevel()->unload(true);
        $this->getServer()->shutdown();
    }

    /**
     * @param $int
     * @return string
     */
    public function getTime($int) : string
    {
        $m = floor($int / 60);
        $s = floor($int % 60);
        return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . $s);
    }

}