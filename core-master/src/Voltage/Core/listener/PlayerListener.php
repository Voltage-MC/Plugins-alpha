<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 27/04/2019
 * Time: 14:18
 */

namespace Voltage\Core\listener;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Voltage\Core\base\Ban;
use Voltage\Core\base\Economy;
use Voltage\Core\base\Friends;
use Voltage\Core\base\Gambler;
use Voltage\Core\base\PlayerInfo;
use Voltage\Core\base\Server;
use Voltage\Core\manager\PetsManager;
use Voltage\Core\utils\API;
use Voltage\Core\utils\Network;
use pocketmine\event\Listener;
use pocketmine\event\player\cheat\PlayerCheatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\QueryRegenerateEvent;

class PlayerListener implements Listener
{
    private $plugin;
    private $time = [];

    /**
     * PlayerListener constructor.
     */
    public function __construct()
    {
        $this->plugin = Core::getInstance();
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this,$this->getPlugin());
    }

    /**
     * @return Core
     */
    private function getPlugin() : Core
    {
        return $this->plugin;
    }

    /**
     * @param PlayerCreationEvent $event
     */
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(VOLTPlayer::class);
    }

    /**
     * @param PlayerPreLoginEvent $event
     */
    public function onPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof VOLTPlayer) {

            Gambler::setConnect($player->getName(), Network::getServer());
            Gambler::setDefaultData($player);
            Friends::setDefaultData($player);
            Economy::setDefaultData($player);
            PlayerInfo::setDefaultData($player);

            $ban = array(false);

            if (Ban::existsName($player->getName())) {

                $ban = array(true, strtolower($player->getName()), "name");

            } else if (Ban::existsCID($player->getClientId())) {

                $ban = array(true, $player->getClientId(), "cid");

            } else if (Ban::existsIP($player->getAddress())) {

                $ban = array(true, $player->getAddress(), "ip");

            } else if (Ban::existsUUID($player->getUniqueId())) {

                $ban = array(true, $player->getUniqueId(), "uuid");

            }

            if ($ban[0]) {

                $data = Ban::get($ban[1], $ban[2]);

                if ($data[6] !== "indefinite") {

                    if (strtotime("now") >= (int)$data[6]) {

                        Ban::delByID($data[0]);
                        return;

                    } else {

                        $time = API::getTimeFormat((int)$data[6] - strtotime("now"));

                    }

                } else {

                    $time = "indefinite";

                }


                $player->close("",$player->messageToTranslate("BAN_KICK", array($data[2], $data[7], $time)));



            }

        }

    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();

        if ($player instanceof VOLTPlayer) {

            $event->setJoinMessage(null);
            $this->time[$name] = time();
            $player->sendMessage($player->messageToTranslate("JOIN_MESSAGE", array(Network::getServer())));
            Gambler::addPermission($player);

        }

    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();

        Gambler::setConnect($name, "offline");
        $event->setQuitMessage(null);

        if ($player instanceof VOLTPlayer) {

            if (PetsManager::findPet($player)) {

                PetsManager::removePet($player);

            }

        }

        if (isset($this->time[$name])) {

            Gambler::addPlayTime($name,time() - $this->time[$name]);
            unset($this->time[$name]);

        }

    }

    /**
     * @param QueryRegenerateEvent $event
     */
    public function onQuery(QueryRegenerateEvent $event)
    {
        $event->setMaxPlayerCount(640);
        $event->setPlayerCount(Server::getNetworkCount());
        $event->setServerName(TextFormat::BOLD . TextFormat::LIGHT_PURPLE . "Voltage " . Core::getPrefix() . TextFormat::RESET . TextFormat::GRAY .  " Network");
        $event->setPlayerList($this->getPlayerList());
    }

    /**
     * @return \Voltage\Core\fake\Player[]
     */
    public function getPlayerList() : array
    {
        $players = [];
        $names = [];
        foreach (Server::getAllNetwork() as $name) {

            if (!in_array($name, $names)) {

                $players[] = new \Voltage\Core\fake\Player($name);
                $names[] = $name;

            }

        }

        return $players;
    }

    public function onCheat(PlayerCheatEvent $event)
    {
        $event->setCancelled();
    }

    public function onDataReceive(DataPacketReceiveEvent $event)
    {
        $pk = $event->getPacket();
        $player = $event->getPlayer();

        if ($player instanceof Player) {

            if ($pk instanceof InventoryTransactionPacket) {

                if ($pk->transactionType === InventoryTransactionPacket::TYPE_USE_ITEM) {

                    if ($pk->trData->actionType === InventoryTransactionPacket::USE_ITEM_ACTION_CLICK_BLOCK or $pk->trData->actionType === InventoryTransactionPacket::USE_ITEM_ACTION_CLICK_AIR) {

                        if ($player->isSpectator()) {

                            $ev = new PlayerInteractEvent($player, $player->getInventory()->getItemInHand(), null, null, 0);
                            $ev->call();

                        }

                    }

                } else if ($pk->transactionType === InventoryTransactionPacket::TYPE_RELEASE_ITEM) {

                    if ($pk->trData->actionType === InventoryTransactionPacket::RELEASE_ITEM_ACTION_CONSUME) {

                        if ($player->isSpectator()) {

                            $item = $player->getInventory()->getItemInHand();

                            $ev = new PlayerItemConsumeEvent($player, $item);
                            $ev->call();

                        }

                    }

                }

            }

        }

    }

    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof VOLTPlayer) {

            switch ($event->getReason()) {

                case "Server is white-listed":

                    if (!$player->isStaff()) {

                        $event->setCancelled();
                        $player->close("Whitelist",$player->messageToTranslate("KICK_WHITELIST"));

                    }
                    break;

            }

        }

    }

}