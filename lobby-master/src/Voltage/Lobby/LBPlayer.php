<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 03/05/2019
 * Time: 19:09
 */

namespace Voltage\Lobby;

use pocketmine\network\mcpe\protocol\ActorEventPacket;
use Voltage\Core\VOLTPlayer;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\SourceInterface;
use pocketmine\utils\TextFormat as TE;

class LBPlayer extends VOLTPlayer
{
    public $hide = false;
    
    public function __construct(SourceInterface $interface, string $ip, int $port)
    {
        parent::__construct($interface, $ip, $port);
    }

    /**
     * @return bool
     */
    public function getHide() : bool
    {
        return $this->hide;
    }

    public function setHide()
    {
        if ($this->getHide()) {

            foreach ($this->getServer()->getOnlinePlayers() as $p) {

                $this->hidePlayer($p);

            }

            $this->hide = false;
            $this->sendMessage($this->messageToTranslate("HIDE_MESSAGE_HIDE"));

        } else {

            foreach ($this->getServer()->getOnlinePlayers() as $p) {

                $this->showPlayer($p);

            }

            $this->hide = true;
            $this->sendMessage($this->messageToTranslate("HIDE_MESSAGE_SHOW"));

        }

    }

    public function getViews() : array
    {
        $players = $this->getViewers();

        foreach ($players as $player) {

            if ($player instanceof self) {

                if ($player->getHide()) {

                    unset($players[array_search($player,$players)]);

                }

            }


        }

        return $players;
    }

    public function giveItemLobby()
    {
        $this->removeAllEffects();
        $this->getArmorInventory()->clearAll();

        $gameselection = Item::get(Item::COMPASS,0,1)->setCustomName(TE::LIGHT_PURPLE . "Game selection\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(0, $gameselection);

        $inventory = Item::get(Item::CHEST_MINECART,0,1)->setCustomName(TE::AQUA . "Inventory\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(6, $inventory);

        $inventory = Item::get(Item::CAKE,0,1)->setCustomName(TE::RED . "Party\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(7, $inventory);

        $inventory = Item::get(Item::NAME_TAG,0,1)->setCustomName(TE::YELLOW . "Friends\n" . TE::GRAY . "(Tap)");
        $this->getInventory()->setItem(8, $inventory);
    }

    public function addTotemJoin()
    {
        $item = $this->getInventory()->getItemInHand();
        $title = TE::BOLD . TE::WHITE . " » " . TE::LIGHT_PURPLE . "VOLTAGE" . TE::WHITE . " « ";
        $subtitle = $this->messageToTranslate("JOIN_SUBTITLE");
        $this->addTitle($title, $subtitle);
        $this->getInventory()->setItemInHand(Item::get(Item::TOTEM));

        $pk = new LevelEventPacket();
        $pk->position = $this;
        $pk->evid = LevelEventPacket::EVENT_SOUND_ORB;
        $pk->data = 1;
        $this->sendDataPacket($pk);

        $pk = new ActorEventPacket();
        $pk->event = ActorEventPacket::CONSUME_TOTEM;
        $pk->entityRuntimeId = $this->getId();
        $pk->data = 0;
        $this->sendDataPacket($pk);

        $this->getInventory()->setItemInHand($item);
    }

    public function newNameTag(): string
    {
        return "§7[§5" . $this->getLevelWithXP() . "§7] " . $this->getPrefix($this->getRank()) . "§r§a " . $this->getDisplayName();
    }

}