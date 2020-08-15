<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 11/06/2019
 * Time: 18:12
 */

namespace Voltage\Pvp;

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\SourceInterface;
use pocketmine\utils\TextFormat as TE;
use Voltage\Core\VOLTPlayer;

class PVPPlayer extends VOLTPlayer
{
    private $streak = 0;

    public function __construct(SourceInterface $interface, string $ip, int $port)
    {
        parent::__construct($interface, $ip, $port);
    }

    public function giveItemPvp()
    {
        $this->removeAllEffects();
        $this->getArmorInventory()->clearAll();

        $ffa = Item::get(Item::DIAMOND_SWORD,0,1)->setCustomName(TE::LIGHT_PURPLE . "Ffa selection\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(0, $ffa);

        $ranked = Item::get(Item::IRON_SWORD,0,1)->setCustomName(TE::GOLD . "Practice\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(1, $ranked);

        $gameselection = Item::get(Item::COMPASS,0,1)->setCustomName(TE::LIGHT_PURPLE . "Game selection\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(4, $gameselection);

        $economy = Item::get(175,0,1)->setCustomName(TE::YELLOW . "Stats\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(8, $economy);
    }

    public function addAnimationJoin()
    {
        $title = TE::BOLD . TE::WHITE . " » " . TE::LIGHT_PURPLE . "PvP" . TE::WHITE . " « ";
        $subtitle = $this->messageToTranslate("JOIN_SUBTITLE");
        $this->addTitle($title, $subtitle);

        $pk = new LevelEventPacket();
        $pk->position = $this;
        $pk->evid = LevelEventPacket::EVENT_SOUND_ENDERMAN_TELEPORT;
        $pk->data = 1;
        $this->sendDataPacket($pk);
    }

    public function getStreak() : int
    {
        return Provider::getStreak($this->getName());
    }

    public function addStreak()
    {
        $this->streak++;

        if ($this->getStreak() < $this->streak) {

            Provider::setStreak($this->getName(), $this->streak);

        }

    }

    public function resetStreak()
    {
        $this->streak = 0;
    }

    public function getDeaths() : int
    {
        return Provider::getDeaths($this->getName());
    }

    public function addDeaths(int $amount)
    {
        Provider::addDeaths($this->getName(), $amount);
    }

    public function getKills() : int
    {
        return Provider::getKills($this->getName());
    }

    public function addKills(int $amount)
    {
        Provider::addKills($this->getName(), $amount);
    }

    public function getElos() : int
    {
        return Provider::getElos($this->getName());
    }

    public function addElos(int $amount)
    {
        Provider::addElos($this->getName(), $amount);
        $this->setNameTag($this->newNameTag());
    }

    public function reduceElos(int $amount)
    {
        Provider::reduceElos($this->getName(), $amount);
        $this->setNameTag($this->newNameTag());
    }

    public function newNameTag(): string
    {
        return "§7[§5" . $this->getElos() . "§7] " . "§7[§5" . $this->getLevelWithXP() . "§7] " . $this->getPrefix($this->getRank()) . "§r§a " . $this->getDisplayName();
    }

}