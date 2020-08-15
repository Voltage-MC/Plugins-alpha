<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 14:39
 */

namespace Voltage\Api;


use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\PlayStatusPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\network\SourceInterface;
use Voltage\Api\event\GameLostEvent;
use Voltage\Api\event\GameWinEvent;
use Voltage\Api\task\DimensionTeleportTask;
use Voltage\Core\VOLTPlayer;

class GAPlayer extends VOLTPlayer
{
    const MODE_WAITING = 0;
    const MODE_START = 1;
    const MODE_GAME = 2;
    const MODE_FINISH = 3;

    const MODE_PLAYER = 0;
    const MODE_SPECTATOR = 1;
    const MODE_NULL = 2;

    public $mode = self::MODE_NULL;
    public $win = false;
    public $lost = false;

    public $team = "null";

    public $kill = null;

    public $join = false;

    /**
     * @param SourceInterface $interface
     * @param string $ip
     * @param int $port
     */
    public function __construct(SourceInterface $interface, $ip, $port)
    {
        parent::__construct($interface, $ip, $port);
    }

    public function closePlayer()
    {
        $this->getInventory()->clearAll();
        $this->getArmorInventory()->clearAll();
        $this->setNameTag($this->getName());
        $this->setImmobile(false);
        $this->setMode(self::MODE_SPECTATOR);
        $this->team = "null";
        $this->getKitSpectator();
        $this->TeleportToLobby();
        $this->setGamemode(3);
    }

    public function TeleportToLobby()
    {
        $this->teleport(new Vector3(Game::getInstance()->getData()["lobby"][0],Game::getInstance()->getData()["lobby"][1],Game::getInstance()->getData()["lobby"][2]),Game::getInstance()->getData()["lobby"][3]);
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

    /**
     * @return bool
     */
    public function isJoin() : bool
    {
        return $this->join;
    }

    public function setJoin()
    {
        $this->join = true;
    }

    /**
     * @return bool
     */
    public function getWin() : bool
    {
        return $this->win;
    }

    public function setWin()
    {
        $this->win = true;
        $event = new GameWinEvent($this);
        $event->call();
    }

    /**
     * @return bool
     */
    public function getLost() : bool
    {
        return $this->lost;
    }

    public function setLost()
    {
        $this->lost = true;
        $event = new GameLostEvent($this);
        $event->call();
    }

    private function getKitSpectator()
    {
        $this->getInventory()->setItem(0, Item::get(Item::CHORUS_FRUIT)->setCustomName("§l§cPlay Again\n§r§7(Eat)"));
        $this->getInventory()->setItem(4, Item::get(Item::CLOCK)->setCustomName("§l§cTeleportation\n§r§7(Tap)"));
        $this->getInventory()->setItem(8, Item::get(Item::END_PORTAL_FRAME)->setCustomName("§l§cBack To Lobby\n§r§7(Tap)"));
        $this->getInventory()->setHeldItemIndex(4,true);
        $this->getInventory()->sendHeldItem($this);
    }

    public function addPlayer()
    {
        $this->teleportToLobby();
        $this->setGamemode(2);
        $this->setHealth(20);
        $this->setFood(20);
        $this->getInventory()->clearAll();
        $this->getArmorInventory()->clearAll();
        $this->getInventory()->setItem(8, Item::get(Item::END_PORTAL_FRAME)->setCustomName("§l§cBack To Lobby\n§r§7(Tap)"));
    }

    /**
     * @return string
     */
    public function getTeam() : string
    {
        return $this->team;
    }

    /**
     * @return string
     */
    public function getTeamColor()
    {
        $team = $this->getTeam();

        return self::getColorRank($team);
    }

    /**
     * @return string|null
     */
    public function getKill() : ?string
    {
        return $this->kill;
    }

    /**
     * @param string|null $player
     */
    public function setDamager(?string $player)
    {
        $this->kill = $player;
    }

    /**
     * @return string
     */
    public function getDamager() : ?string
    {
        return $this->kill;
    }

    public function changeDimension(Vector3 $pos, int $id = DimensionIds::THE_END)
    {
        $pk = new ChangeDimensionPacket();
        $pk->dimension = $id;
        $pk->position = $pos;
        $this->dataPacket($pk);
        $this->sendPlayStatus(PlayStatusPacket::PLAYER_SPAWN);
        $this->teleport($pos);
        new DimensionTeleportTask($this,$pos);
    }

    public static function getColorRank(string $team)
    {
        switch ($team) {

            case "Blue": return "§9§lBlue§r";
            case "Red": return "§c§lRed§r";

        }

        return "§7§lSpectator§r";
    }

}