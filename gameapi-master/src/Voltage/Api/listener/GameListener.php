<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 17:35
 */

namespace Voltage\Api\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\cheat\PlayerCheatEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use Voltage\Api\event\GameBreakEvent;
use Voltage\Api\event\GameChatEvent;
use Voltage\Api\event\GameDamageByEntityEvent;
use Voltage\Api\event\GameDamageEvent;
use Voltage\Api\event\GameDropEvent;
use Voltage\Api\event\GameHungerEvent;
use Voltage\Api\event\GameJoinEvent;
use Voltage\Api\event\GamePlaceEvent;
use Voltage\Api\event\GameQuitEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;
use Voltage\Core\base\Server;
use Voltage\Core\Core;
use Voltage\Core\entity\npc\LobbyNpc;
use Voltage\Core\form\ModalForm;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class GameListener implements Listener
{
    private $plugin;

    /**
     * GameListener constructor.
     */
    public function __construct()
    {
        $this->plugin = Game::getInstance();
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this,$this->getPlugin());
    }

    /**
     * @return Game
     */
    private function getPlugin() : Game
    {
        return $this->plugin;
    }

    /**
     * @param PlayerCreationEvent $event
     */
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(GAPlayer::class);
    }

    /**
     * @param PlayerJoinEvent $event
     * @throws \ReflectionException
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $event->setJoinMessage(null);

        if ($player instanceof GAPlayer ) {

            $player->setJoin();

            if ($this->getPlugin()->isFull()){

                $player->closePlayer();
                $event = new GameJoinEvent($player,GameJoinEvent::FULL);
                $event->call();

            } else if ($this->getPlugin()->isStarted()) {

                $player->closePlayer();
                $event = new GameJoinEvent($player,GameJoinEvent::START);
                $event->call();

            } else {

                $player->addPlayer();
                $event = new GameJoinEvent($player,GameJoinEvent::NORMAL);
                $event->call();

            }

        }

    }

    /**
     * @param PlayerQuitEvent $event
     * @throws \ReflectionException
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $event->setQuitMessage(null);

        if ($player instanceof GAPlayer) {

            Game::getInstance()->removeHead($player->getName());
            $event = new GameQuitEvent($player);
            $event->call();

        }

    }

    /**
     * @param EntityDamageEvent $event
     * @throws \ReflectionException
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();

        if($player instanceof GAPlayer){

            if ($this->getPlugin()->isStarted()) {

                if(!$event instanceof EntityDamageByEntityEvent) {

                    $eventdamage = new GameDamageEvent($player, $event->getCause(),$event->getFinalDamage());
                    $eventdamage->call();

                    if ($eventdamage->isCancelled()) {

                        $event->setCancelled(true);

                    }

                }

            } else {

                $event->setCancelled();

                if($event->getFinalDamage() >= $player->getHealth()) {

                    $player->setHealth(20);
                    $player->TeleportToLobby();

                }

            }

        }

    }

    /**
     * @param EntityDamageByEntityEvent $event
     * @throws \ReflectionException
     */
    public function onDamageByEntity(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $damager = $event->getDamager();

        if ($damager instanceof GAPlayer and $player instanceof GAPlayer) {

            $player->setDamager($damager->getDisplayName());
            $eventdamage = new GameDamageByEntityEvent($player, $damager,$event->getFinalDamage());
            $eventdamage->call();

            if ($eventdamage->isCancelled()) {

                $event->setCancelled(true);

            }

        } else {

            $event->setCancelled(true);

        }

    }

    /**
     * @param BlockBreakEvent $event
     * @throws \ReflectionException
     */
    public function onBreakBlock(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($player instanceof GAPlayer) {

            if($this->getPlugin()->isStarted()){

                $eventbreak = new GameBreakEvent($player,$block);
                $eventbreak->call();

                if (count($eventbreak->getDrops()) > 0) {

                    $event->setDrops($eventbreak->getDrops());

                }

                if ($eventbreak->isCancelled()) {

                    $event->setCancelled(true);

                }

            } else {

                $event->setCancelled();

            }

        }

    }

    /**
     * @param BlockPlaceEvent $event
     * @throws \ReflectionException
     */
    public function onPlaceBlock(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($player instanceof GAPlayer) {

            if($this->getPlugin()->isStarted()){

                $eventplace = new GamePlaceEvent($player,$block);
                $eventplace->call();

                if ($eventplace->isCancelled()) {

                    $event->setCancelled(true);

                }

            } else {

                $event->setCancelled(true);

            }

        }

    }

    /**
     * @param PlayerItemConsumeEvent $event
     */
    public function onConsume(PlayerItemConsumeEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof GAPlayer) {

            switch ($event->getItem()->getId()) {

                case  Item::CHORUS_FRUIT:

                    if (Network::NAME["HB1"] < Game::getInstance()->getServer()->getPort() and Network::NAME["HB10"] > Game::getInstance()->getServer()->getPort()) {

                        if (!Network::joinHikaBrain($player)) {

                            $player->sendMessage($player->messageToTranslate("GAME_NO_FOUND"));

                        }

                    }

                    break;
            }

        }

    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof GAPlayer) {

            if(!$this->getPlugin()->isStarted()){

                $event->setCancelled();

            }

            switch ($event->getItem()->getCustomName()) {

                case "§l§cBack To Lobby\n§r§7(Tap)":

                    if ($player->interact()) {

                        LobbyNpc::getUi($player);

                    }
                    break;

                case "§l§cTeleportation\n§r§7(Tap)":

                    if ($player->interact()) {

                        if (Game::getInstance()->finish) {

                            $player->TeleportToLobby();

                        } else {

                            $ui = new SimpleForm
                            (
                                function (VOLTPlayer $player, $data)
                                {

                                    if ($data === null) {
                                    } else {

                                        if (isset(Game::getInstance()->getPlaying()[$data])) {

                                            $target = Game::getInstance()->getPlaying()[$data];

                                            if ($target->isOnline()) {

                                                $player->teleport($target);
                                                $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("GAME_TELEPORT_TO_PLAYER", array($target->getDisplayName())));

                                            }

                                        }


                                    }

                                }

                            );
                            $ui->setTitle($player->messageToTranslate("GAME_TELEPORT"));

                            foreach (Game::getInstance()->getPlaying() as $pl) {

                                $head = Game::getInstance()->getHead($pl->getName(), $pl->getSkin()->getSkinData());
                                $ui->addButton($pl->getNameTag(), SimpleForm::IMAGE_TYPE_URL, $head);

                            }
                            $ui->sendToPlayer($player);

                        }

                    }

                    break;

            }

        }
    }

    /**
     * @param PlayerChatEvent $event
     * @throws \ReflectionException
     */
    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if ($player instanceof GAPlayer) {

            if ($this->getPlugin()->isStarted()) {

                if ($player->getMode() === GAPlayer::MODE_PLAYER) {

                    $eventchat = new GameChatEvent($player, $message);
                    $eventchat->call();

                    if (!is_null($eventchat->getFormat())) {

                        $event->setFormat($eventchat->getFormat());

                    } else if ($eventchat->isCancelled()) {

                        $event->setCancelled(true);

                    }

                } else {

                    $event->setFormat("§8SPECTATOR " . "§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getPrefix($player->getRank()) . "§r§a " . $player->getDisplayName() . " §l» §r§7" . $event->getMessage());

                }

            } else {

                $event->setFormat("§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getPrefix($player->getRank()) . "§r§a " . $player->getDisplayName() . " §l» §r§7" . $event->getMessage());

            }

        }

    }

    /**
     * @param PlayerCheatEvent $event
     */
    public function onCheatIllegale(PlayerCheatEvent $event)
    {
        $event->setCancelled();
    }

    /**
     * @param PlayerExhaustEvent $event
     * @throws \ReflectionException
     */
    public function onHunger(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof GAPlayer) {

            if ($player->getMode() === GAPlayer::MODE_PLAYER) {

                if ($this->getPlugin()->isStarted()) {

                    $eventhunger = new GameHungerEvent($player);
                    $eventhunger->call();

                    if ($eventhunger->isCancelled()) {

                        $event->setCancelled(true);

                    }

                } else {

                    $event->setCancelled();

                }

            } else {

                $event->setCancelled();

            }

        }

    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($player instanceof GAPlayer) {

            if(!$this->getPlugin()->isStarted()){

                $eventplace = new GameDropEvent($player, $item);
                $eventplace->call();

                if ($eventplace->isCancelled()) {

                    $event->setCancelled(true);

                }

            } else {

                $event->setCancelled();

            }

        }

    }

}