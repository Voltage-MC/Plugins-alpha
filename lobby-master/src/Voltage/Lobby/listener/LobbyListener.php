<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 03/05/2019
 * Time: 19:12
 */

namespace Voltage\Lobby\listener;

use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use Voltage\Core\Core;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use Voltage\Core\task\FloatingTextTask;
use Voltage\Lobby\LBPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use Voltage\Lobby\Lobby;

class LobbyListener implements Listener
{
    private $plugin;

    /**
     * PlayerListener constructor.
     */
    public function __construct()
    {
        $this->plugin = Lobby::getInstance();
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this,$this->getPlugin());
    }

    /**
     * @return Lobby
     */
    private function getPlugin() : Lobby
    {
        return $this->plugin;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof LBPlayer) {

            $player->setGamemode(2);
            $player->getInventory()->setHeldItemIndex(0,true);
            $player->getInventory()->sendHeldItem($player);

            $player->addTotemJoin();

            $player->setNameTag($player->newNameTag());
            $player->giveItemLobby();

            $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED)->setValue("0.2");

        }

    }

    /**
     * @param PlayerCreationEvent $event
     */
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(LBPlayer::class);
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof LBPlayer) {

            if (!$player->hasPermission("admin")) {

                $event->setCancelled();

            }

        }

    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof LBPlayer) {

            if (!$player->hasPermission("admin")) {

                $event->setCancelled();

            }

        }

    }

    /**
     * @param PlayerDropItemEvent $event
     */
    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof LBPlayer) {

            if (!$player->hasPermission("admin")) {

                $event->setCancelled();

            }

        }

    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($player instanceof LBPlayer) {

            if (!$player->hasPermission("admin")) {

                if (!in_array($item->getId(),array(Item::DYE,Item::CHEST_MINECART,Item::COMPASS,Item::CAKE,Item::NAME_TAG))) {

                    $event->setCancelled();

                }

            }

        }

    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $event->setCancelled();
    }

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onExaust(PlayerExhaustEvent $event)
    {
        $event->setCancelled();
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof LBPlayer) {

            $event->setFormat("§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getPrefix($player->getRank()) . "§r§a " . $player->getDisplayName() . " §l» §r§7" . $event->getMessage());

        }

    }

    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof LBPlayer) {

            if ($player->getY() <= 80) {

                $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("LOBBY_TELEPORT"));

                $level = Core::getInstance()->getServer()->getDefaultLevel();
                $pos = $level->getSafeSpawn();

                $player->teleport(new Vector3($pos->getX(), $pos->getY(), $pos->getZ()), 0, 0);

            }

        }

    }

}