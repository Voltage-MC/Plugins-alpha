<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 03/05/2019
 * Time: 19:12
 */

namespace Voltage\Pvp\listener;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TE;
use Voltage\Core\Core;
use Voltage\Core\form\CustomForm;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\VOLTPlayer;
use Voltage\Pvp\game\Ffa;
use Voltage\Pvp\Provider;
use Voltage\Pvp\Pvp;
use Voltage\Pvp\PVPPlayer;

class PvpListener implements Listener
{
    private $plugin;

    /**
     * PlayerListener constructor.
     */
    public function __construct()
    {
        $this->plugin = Pvp::getInstance();
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this,$this->getPlugin());
    }

    /**
     * @return Pvp
     */
    private function getPlugin() : Pvp
    {
        return $this->plugin;
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Pvp::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                if ($player->getY() <= 3) {

                    $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("LOBBY_TELEPORT"));

                    $level = Core::getInstance()->getServer()->getDefaultLevel();
                    $pos = $level->getSafeSpawn();

                    $player->teleport(new Vector3($pos->getX(), $pos->getY(), $pos->getZ()), 0, 0);

                }

            } else if ($player->getLevel()->getFolderName() === Ffa::GAPPLE) {

                if (!in_array(strtolower($player->getName()), Pvp::getInstance()->gapple)) {

                    if ($player->getY() < 40) {

                        Pvp::getFfa()->kitGapple($player);

                    }

                }

            }

        }
    }

    /**
     * @param PlayerCreationEvent $event
     */
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(PVPPlayer::class);
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            $player->setGamemode(2);
            $player->giveItemPvp();
            $player->addAnimationJoin();
            $player->setNameTag($player->newNameTag());
            Provider::setDefaultData($player);

        }

    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                if (!$player->hasPermission("admin")) {

                    $event->setCancelled();

                }

            } else if ($player->getLevel()->getFolderName() === Ffa::GAPPLE) {

                if (!$player->hasPermission("admin")) {

                    $event->setCancelled();

                }

            }

        }

    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                if (!$player->hasPermission("admin")) {

                    $event->setCancelled();

                }

            } else if ($player->getLevel()->getFolderName() === Ffa::GAPPLE) {

                if (!$player->hasPermission("admin")) {

                    $event->setCancelled();

                }

            }

        }

    }

    /**
     * @param PlayerDropItemEvent $event
     */
    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                if (!$player->hasPermission("admin")) {

                    $event->setCancelled();

                }

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

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                if (!in_array($item->getId(),array(Item::IRON_SWORD,Item::DIAMOND_SWORD,175))) {

                    if ($item->getId() !== Item::COMPASS) {

                        if (!$player->hasPermission("admin")) {

                            $event->setCancelled();

                        }

                    }

                } else {

                    switch ($item->getCustomName()) {

                        case TE::LIGHT_PURPLE . "Ffa selection\n" . TE::GRAY . "(Tap)":

                            if ($player->interact()) {

                                $ui = new SimpleForm
                                (
                                    function (PVPPlayer $player, $data)
                                    {

                                        if ($data === null) {
                                        } else {

                                            switch($data){

                                                case 0:
                                                    $title = TE::BOLD . TE::WHITE . " » " . TE::LIGHT_PURPLE . "Gapple" . TE::WHITE . " « ";
                                                    $subtitle = $player->messageToTranslate("JOIN_SUBTITLE");
                                                    $player->addTitle($title, $subtitle);
                                                    Pvp::getFfa()->onJoinGapple($player);
                                                    break;

                                            }

                                        }

                                    }

                                );

                                $ui->setTitle($player->messageToTranslate("PVP_FFA_TITLE_UI"));
                                $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TE::YELLOW . "Gapple")), SimpleForm::IMAGE_TYPE_PATH, "textures/items/apple");
                                $ui->sendToPlayer($player);

                            }
                            break;

                        case TE::GOLD . "Practice\n" . TE::GRAY . "(Tap)":

                            break;

                        case TE::YELLOW . "Stats\n" . TE::GRAY . "(Tap)":

                            if ($player->interact()) {

                                $ui = new SimpleForm
                                (
                                    function (PVPPlayer $player, $data)
                                    {

                                        if ($data === null) {
                                        } else {

                                            switch($data){

                                                case 0:
                                                    Pvp::getInstance()->getServer()->dispatchCommand($player, "stats");
                                                    break;
                                                case 1:
                                                    $this->seeStats($player);
                                                    break;

                                            }

                                        }

                                    }

                                );

                                $ui->setTitle($player->messageToTranslate("PVP_STATS_TITLE_UI"));
                                $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TE::RED . "My Stats")));
                                $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TE::RED . "See player stats")));
                                $ui->sendToPlayer($player);

                            }
                            break;
                    }

                }

            } else if ($player->getLevel()->getFolderName() === Ffa::GAPPLE) {

                switch ($item->getCustomName()) {

                    case TE::BOLD . TE::WHITE . " » " . TE::LIGHT_PURPLE . "Kit" . TE::WHITE . " « ":

                        if ($player->interact()) {

                            Pvp::getFfa()->kitGapple($player);
                            $event->setCancelled();

                        }
                        break;

                }

            }

            switch ($item->getCustomName()) {

                case "§l§cBack To Hub\n§r§7(Tap)":

                    if ($player->interact()) {

                        Pvp::getInstance()->getServer()->dispatchCommand($player, "hub");
                        $event->setCancelled();

                    }

                    break;

            }

        }

    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        $cause = $event->getCause();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                $event->setCancelled();

            } else {

                $causes = [EntityDamageEvent::CAUSE_FALL, EntityDamageEvent::CAUSE_SUICIDE];
                if (in_array($cause, $causes)) {

                    $event->setCancelled();

                } else {

                    if ($player->getLevel()->getFolderName() === Ffa::GAPPLE) {

                        $coord = $player->asVector3();

                        $spawn1 = new Vector3(2224,67, -442);
                        $spawn2 = new Vector3(2215, 61, -450);

                        if ($coord->getX() > $spawn2->getX() and $coord->getX() < $spawn1->getX()) {

                            if ($coord->getY() > $spawn2->getY() and $coord->getY() < $spawn1->getY()) {

                                if ($coord->getZ() > $spawn2->getZ() and $coord->getZ() < $spawn1->getZ()) {

                                    $event->setCancelled();

                                }

                            }

                        }

                        if ($event->getFinalDamage() >= $player->getHealth()) {

                            if ($event instanceof EntityDamageByEntityEvent) {

                                $damager = $event->getDamager();

                                if ($damager instanceof PVPPlayer) {

                                    $rand = mt_rand(15,20);
                                    $damager->sendMessage(Core::PREFIX . $player->messageToTranslate("PVP_WIN", array($rand)));
                                    $damager->addElos($rand);
                                    $damager->addKills(1);
                                    $damager->addStreak();
                                    $rand = mt_rand(1,3);
                                    if ($rand === 1) $damager->addXPWithLevel(mt_rand(1,5));
                                    $damager->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE),20 * 2, 1, false));

                                }

                                $rand = mt_rand(10,15);
                                $player->sendMessage(Core::PREFIX . $player->messageToTranslate("PVP_LOST", array($rand)));
                                $player->reduceElos($rand);
                                $player->addDeaths(1);
                                $player->resetStreak();

                            }

                            Pvp::getFfa()->removeGapplePlayer($player->getName());
                            Pvp::getFfa()->onJoinGapple($player);

                        }

                    }

                }

            }

        }

    }

    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if ($player instanceof PVPPlayer) {

            $event->setCancelled();

            if ($message[0] === "@") {

                $message = str_replace("@","",$message);

                foreach (Pvp::getInstance()->getServer()->getOnlinePlayers() as $pl) {

                    if ($pl instanceof PVPPlayer) {

                        $global = "";

                        if ($pl->getLevel()->getFolderName() !== Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                            $global = TE::GREEN . "[Global] ";

                        }

                        $pl->sendMessage($global . "§7[§5" . $player->getElos() . "§7] " . "§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getPrefix($player->getRank()) . "§r§a " . $player->getDisplayName() . " §l» §r§7" . $message);

                    }

                }

            } else {

                foreach ($player->getLevel()->getPlayers() as $pl) {

                    if ($pl instanceof PVPPlayer) {

                        $pl->sendMessage("§7[§5" . $player->getElos() . "§7] " . "§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getPrefix($player->getRank()) . "§r§a " . $player->getDisplayName() . " §l» §r§7" . $message);

                    }

                }

            }

            Pvp::getInstance()->getLogger()->info("[" . $player->getLevel()->getFolderName() . "]" . " §7[§5" . $player->getElos() . "§7] " . "§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getPrefix($player->getRank()) . "§r§a " . $player->getDisplayName() . " §l» §r§7" . $message);

        }

    }


    public function onHunger(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Core::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                $event->setCancelled();

            }

        }

    }

    public function onLevelChange(EntityLevelChangeEvent $event)
    {
        $player = $event->getEntity();

        if ($player instanceof PVPPlayer) {

            if ($event->getTarget()->getFolderName() === Pvp::getInstance()->getServer()->getDefaultLevel()->getFolderName()) {

                $player->getArmorInventory()->clearAll();
                $player->getInventory()->clearAll();
                $player->setGamemode(2);
                $player->giveItemPvp();
                $player->setNameTag($player->newNameTag());
                $player->setHealth("20");
                $player->setFood("20");

            }

            if ($event->getOrigin()->getFolderName() === Ffa::GAPPLE) {

                $player->resetStreak();
                Pvp::getFfa()->removeGapplePlayer($player->getName());

            }

        }

    }

    public function seeStats(PVPPlayer $player)
    {
        $ui = new CustomForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    if (isset($data[0])) {

                        Pvp::getInstance()->getServer()->dispatchCommand($player, "seestats " . $data[0]);

                    }

                }

            }

        );

        $ui->setTitle($player->messageToTranslate("PVP_STATS_TITLE_UI"));
        $ui->addInput("name");
        $ui->sendToPlayer($player);
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof PVPPlayer) {

            if ($player->getLevel()->getFolderName() === Ffa::GAPPLE) {

                $player->resetStreak();
                Pvp::getFfa()->removeGapplePlayer($player->getName());

            }

        }

    }

}