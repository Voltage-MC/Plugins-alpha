<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 15:17
 */

namespace Voltage\Game;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\utils\TextFormat;
use Voltage\Api\event\GameChatEvent;
use Voltage\Api\event\GameDropEvent;
use Voltage\Api\event\GameLostEvent;
use Voltage\Core\base\GameData;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\items\Fireworks;
use Voltage\Core\utils\API;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\StopSoundPacket;
use pocketmine\plugin\PluginBase;
use Voltage\Api\event\GameBreakEvent;
use Voltage\Api\event\GameDamageByEntityEvent;
use Voltage\Api\event\GameDamageEvent;
use Voltage\Api\event\GameEvent;
use Voltage\Api\event\GameFinishEvent;
use Voltage\Api\event\GameHungerEvent;
use Voltage\Api\event\GameJoinEvent;
use Voltage\Api\event\GamePlaceEvent;
use Voltage\Api\event\GameStartEvent;
use Voltage\Api\event\GameWaitingEvent;
use Voltage\Api\event\GameWinEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;
use Voltage\Core\Core;
use Voltage\Core\utils\BroadCast;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;
use Voltage\Game\block\FixBed;
use Voltage\Game\task\Start;

class HikaBrain extends PluginBase implements Listener
{
    public $points = [];

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        BlockFactory::registerBlock(new FixBed() , true);
    }

    /**
     * @param GameJoinEvent $event
     */
    public function onJoin(GameJoinEvent $event)
    {
        $player = $event->getPlayer();
        $cause = $event->getType();

        $player->setFood(20);
        $player->setHealth(20);
        $player->removeAllEffects();
        $player->getArmorInventory()->clearAll();

        $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("GAME_PREJOIN", array("HikaBrain")));
        $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("GAME_JOIN_INFO", array(Game::getInstance()->getMapName(),Game::getInstance()->getAuthor())));

        if ($cause === GameJoinEvent::NORMAL) {

            $pk = new LevelSoundEventPacket();
            $pk->position = $player;
            $pk->sound = LevelSoundEventPacket::SOUND_RECORD_WAIT;
            $pk->extraData = 1;
            $pk->disableRelativeVolume = true;
            $player->sendDataPacket($pk);

            BroadCast::sendMessageServer("GAME_JOIN_BROADCAST", array($player->getName(),count(Game::getInstance()->getAllPlayerIsJoin()),Game::getInstance()->getMaxSlots()));

        }

        if ($cause === GameJoinEvent::FULL) {

            $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("GAME_IS_FULL"));

        }

        if ($cause === GameJoinEvent::START) {

            $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("GAME_IS_INGAME"));

        }

        GameData::setDefaultData($player,"hb");

    }

    /**
     * @param GameWaitingEvent $event
     */
    public function onWait(GameWaitingEvent $event)
    {
        foreach (Game::getInstance()->getAllPlayerIsJoin() as $player) {

            $player->sendTip($player->messageToTranslate("GAME_PREPARATION",array(Game::getInstance()->getMinSlots() - count(Game::getInstance()->getAllPlayerIsJoin()))));

        }

    }

    /**
     * @param GameFinishEvent $event
     */
    public function onFinish(GameFinishEvent $event)
    {
        foreach (Game::getInstance()->getAllPlayerIsJoin() as $player) {

            $player->sendTip($player->messageToTranslate("GAME_FINISH", array($event->getTime())));
            $player->removeScoreBoard();

        }

        $fireworks = ItemFactory::get(Item::FIREWORKS);
        $fireworks->addExplosion(Fireworks::TYPE_HUGE_SPHERE, Fireworks::COLOR_YELLOW);
        $nbt = Entity::createBaseNBT(Game::getInstance()->getLobby(), null, lcg_value() * 360, 90);
        $rocket = Entity::createEntity("Fireworks", Game::getInstance()->getServer()->getDefaultLevel(), $nbt, $fireworks);
        $rocket->spawnToAll();
    }

    public function onWin(GameWinEvent $event)
    {
        $player = $event->getPlayer();
        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch($data){

                        case 0:
                            $player->transfer(Network::IP, Network::NAME["Lobby"]);
                            break;

                    }

                }

            }

        );

        $xp = mt_rand(5,10);
        $moneyparticipation = mt_rand(5,10);
        $moneywin = mt_rand(10,20);
        $key = mt_rand(1,50) === 1 ? 1 : 0;
        $credits = mt_rand(1,25) === 1 ? 5 : 0;
        $ui->setTitle($player->messageToTranslate("GAME_YOU_HAVE_WON"));
        $ui->setContent($player->messageToTranslate("GAME_UI_WIN", array($xp, $moneyparticipation, $moneywin, $key, $credits)));
        $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TextFormat::GOLD . "LOBBY")), SimpleForm::IMAGE_TYPE_PATH, "textures/items/bed_purple");
        $ui->sendToPlayer($player);

        $player->addXPWithLevel($xp);
        $player->addMoney($moneyparticipation + $moneywin);
        $player->addKeys($key);
        $player->addCredits($credits);
        GameData::addWin($player->getName(), "hb");
    }

    public function onLost(GameLostEvent $event)
    {
        $player = $event->getPlayer();
        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch($data){

                        case 0:
                            $player->transfer(Network::IP, Network::NAME["Lobby"]);
                            break;

                    }

                }

            }

        );

        $xp = mt_rand(0,5);
        $moneyparticipation = mt_rand(5,10);
        $moneywin = 0;
        $key = mt_rand(1,100) === 1 ? 1 : 0;
        $credits = mt_rand(1,100) === 1 ? 5 : 0;
        $ui->setTitle($player->messageToTranslate("GAME_YOU_HAVE_LOST"));
        $ui->setContent($player->messageToTranslate("GAME_UI_WIN", array($xp, $moneyparticipation, $moneywin, $key, $credits)));
        $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TextFormat::GOLD . "LOBBY")), SimpleForm::IMAGE_TYPE_PATH, "textures/items/bed_purple");
        $ui->sendToPlayer($player);
        $player->addXPWithLevel($xp);
        $player->addMoney($moneyparticipation + $moneywin);
        $player->addKeys($key);
        $player->addCredits($credits);
        GameData::addLost($player->getName(), "hb");
    }

    /**
     * @param GameStartEvent $event
     */
    public function onStart(GameStartEvent $event)
    {
        foreach (Game::getInstance()->getAllPlayerIsJoin() as $player) {

            if ($player->getMode() !== GAPlayer::MODE_SPECTATOR) {

                $player->sendTip($player->messageToTranslate("GAME_BEGGING", array($event->getTime())));
                $pk = new LevelEventPacket();
                $pk->position = $player;
                $pk->evid = LevelEventPacket::EVENT_SOUND_ORB;
                $pk->data = 1;
                $player->sendDataPacket($pk);

            }

        }

    }

    /**
     * @param GameBreakEvent $event
     */
    public function onBreak(GameBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($block->getY() <= 11) {

            if (Game::getMap()->isBlockSet($event->getBlock())) {

                $pk = new LevelEventPacket();
                $pk->position = $player;
                $pk->evid = LevelEventPacket::EVENT_SOUND_PORTAL;
                $pk->data = 1;
                $player->sendDataPacket($pk);
                $event->setCancelled(true);

            } else {

                $event->setCancelled(false);
                $event->setDrops([Item::get(Item::AIR)]);
                Game::getMap()->delBlock($event->getBlock());

            }

        } else {

            $pk = new LevelEventPacket();
            $pk->position = $player;
            $pk->evid = LevelEventPacket::EVENT_SOUND_PORTAL;
            $pk->data = 1;
            $player->sendDataPacket($pk);
            $event->setCancelled(true);

        }

    }

    /**
     * @param GamePlaceEvent $event
     */
    public function onPlace(GamePlaceEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $y = $block->getY();

        if ($y <= 11) {

            if (!$player->isImmobile()) {

                foreach (Game::getInstance()->getBed()->getBeds() as $bed) {

                    $result = API::getSurroundingArea($block, $bed->add(0, 1, 0), 2);

                    if ($result) {

                        $pk = new LevelEventPacket();
                        $pk->position = $player;
                        $pk->evid = LevelEventPacket::EVENT_SOUND_PORTAL;
                        $pk->data = 1;
                        $player->sendDataPacket($pk);
                        $event->setCancelled(true);
                        return;

                    }

                }

                Game::getMap()->addBlock($block);

            } else {

                $pk = new LevelEventPacket();
                $pk->position = $player;
                $pk->evid = LevelEventPacket::EVENT_SOUND_PORTAL;
                $pk->data = 1;
                $player->sendDataPacket($pk);
                $event->setCancelled(true);
            }

        } else {

            $pk = new LevelEventPacket();
            $pk->position = $player;
            $pk->evid = LevelEventPacket::EVENT_SOUND_PORTAL;
            $pk->data = 1;
            $player->sendDataPacket($pk);
            $event->setCancelled(true);

        }

    }

    /**
     * @param GameEvent $event
     */
    public function onGame(GameEvent $event)
    {
        $time = $event->getTime();

        if ($time === Game::getInstance()->getMaxTime()) {

            Game::getTeams()->setTeams();
            Game::getMap()->setSave(false);

            foreach (Game::getInstance()->getPlaying() as $player) {

                $player->setFood(20);
                $player->setHealth(20);
                $player->getInventory()->clearAll();
                $player->removeAllEffects();
                $player->getArmorInventory()->clearAll();
                $this->giveKit($player);
                $player->sendMessage(Core::getPrefix() . $player->messageToTranslate("GAME_GET_TEAM", array($player->getTeamColor())));
                $player->setNameTag($player->getPrefix($player->getRank()) . " §r" . $player->getTeamColor()." ".$player->getName());
                $player->setGamemode(0);
                $spawn = Game::getTeams()->TeleportToSpawninVector($player);
                $player->changeDimension($spawn[0]);
                $player->yaw = $spawn[1];

                $pk = new LevelEventPacket();
                $pk->position = $player;
                $pk->evid = LevelEventPacket::EVENT_SOUND_PORTAL;
                $pk->data = 1;
                $player->sendDataPacket($pk);

                new Start();

                $pk = new StopSoundPacket();
                $pk->soundName = "record.wait";
                $pk->stopAll = true;
                $player->sendDataPacket($pk);

            }

        } else {

            $teams = [];

            $base = [
                " ",
                TextFormat::GRAY . "objective 5 points"
            ];

            foreach (Game::getTeams()->getTeams() as $team) {

                $color = GAPlayer::getColorRank($team);

                $text = $color . TextFormat::RESET . TextFormat::GRAY . ": ";

                if (isset($this->points[$team])) {

                    $teams[$team] = $text . $this->points[$team];

                } else {

                    $teams[$team] = $text . 0;

                }

            }

            $score = array_merge([""],$teams,$base);

            foreach (Game::getInstance()->getPlaying() as $player) {

                $color = $player->getTeam() === "Red" ? 14 : 11;

                $blocks = Item::get(Item::CONCRETE, $color, 1);
                $blocks->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Blocks");

                $goldenapple = Item::get(322, 0, 1);
                $goldenapple->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Golden Apple");

                if ($player->getInventory()->canAddItem($goldenapple)) $player->getInventory()->addItem($goldenapple);
                if ($player->getInventory()->canAddItem($blocks)) $player->getInventory()->addItem($blocks);
                $player->addScoreBoard($score);

            }

            BroadCast::sendTipServer("GAME_HIKABRAIN_INGAME", array(Game::getInstance()->getTime($event->getTime()),count(Game::getInstance()->getPlaying()),count(Game::getTeams()->getTeamsNotDead())));

        }

    }

    /**
     * @param GameDamageByEntityEvent $event
     */
    public function onDamageByEntity(GameDamageByEntityEvent $event)
    {
        $player = $event->getPlayer();
        $damager = $event->getDamager();
        $damage = $event->getDamage();

        $player->setDamager($damager->getDisplayName());

        if ($player->getTeam() === $damager->getTeam()) {

            $event->setCancelled();
            return;

        } else if ($damage >= $player->getHealth()) {

            Game::getTeams()->TeleportToSpawn($player);
            $player->setFood(20);
            $player->setHealth(20);
            $player->setGamemode(0);
            $player->getInventory()->clearAll();
            $player->removeAllEffects();
            $player->getArmorInventory()->clearAll();
            $event->setCancelled();
            $this->giveKit($player);
            $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 999999*20, 1, false));

            BroadCast::sendMessageServer("GAME_KILL_BY_ENTITY", array($player->getDisplayName(),$player->getDamager()));
            $player->setDamager(null);

            $pk = new LevelEventPacket();
            $pk->position = $damager;
            $pk->evid = LevelEventPacket::EVENT_SOUND_ORB;
            $pk->data = 1;
            $damager->sendDataPacket($pk);

        }

    }

    /**
     * @param PlayerMoveEvent $event
     */
    public function onMove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();

        if(Game::getInstance()->isStarted()) {

            if ($player instanceof GAPlayer) {

                if ($player->getMode() === GAPlayer::MODE_PLAYER) {

                    if ($player->getFloorY() <= 0) {

                        $player->getInventory()->clearAll();
                        $player->removeAllEffects();
                        $player->getArmorInventory()->clearAll();
                        Game::getTeams()->TeleportToSpawn($player);
                        $this->giveKit($player);
                        $player->setFood(20);
                        $player->setHealth(20);
                        $player->setGamemode(0);

                        $pk = new LevelEventPacket();
                        $pk->position = $player;
                        $pk->evid = LevelEventPacket::EVENT_SOUND_ANVIL_FALL;
                        $pk->data = 1;
                        $player->sendDataPacket($pk);

                        if (!is_null($player->getDamager())) {

                            BroadCast::sendMessageServer("GAME_HIKABRAIN_DIED_IN_VOID_BY_ENTITY",array($player->getDisplayName(), $player->getDamager()));
                            $player->setDamager(null);

                        } else {

                            BroadCast::sendMessageServer("GAME_HIKABRAIN_DIED_IN_VOID",array($player->getDisplayName()));
                            $player->setDamager(null);

                        }

                        $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 999999*20, 1, false));
                        return;
                    }

                    foreach (Game::getInstance()->getBed()->getBeds() as $bed) {

                        if
                        (
                            Game::getInstance()->getBed()->getBed($player->getTeam())->getX() !== $bed->getX()
                            or
                            Game::getInstance()->getBed()->getBed($player->getTeam())->getY() !== $bed->getY()
                            or
                            Game::getInstance()->getBed()->getBed($player->getTeam())->getZ() !== $bed->getZ()
                        ) {

                            if ($player->distance($bed) <= 0.7) {

                                $winners = $player->getTeamColor();

                                if (isset($this->points[$player->getTeam()])) {

                                    $this->points[$player->getTeam()]++;

                                    if ($this->points[$player->getTeam()] === 5) {

                                        Game::getMap()->resetBlocks();

                                        foreach (Game::getInstance()->getPlaying() as $player2) {

                                            if ($player->getTeam() == $player2->getTeam()) {

                                                $msg = $player->messageToTranslate("GAME_YOU_HAVE_WON");
                                                $player2->setWin();

                                            } else {

                                                $msg = $player->messageToTranslate("GAME_YOU_HAVE_LOST");
                                                $player2->setLost();

                                            }

                                            $player2->addTitle($msg, $player->messageToTranslate("GAME_WIN_BROADCAST",array($winners)));
                                            $player2->closePlayer();

                                        }
                                        BroadCast::sendMessageServer("GAME_WIN_BROADCAST",array($winners));
                                        Game::getInstance()->setFinish();
                                        return;

                                    }

                                } else {

                                    $this->points[$player->getTeam()] = 1;

                                }

                                Game::getMap()->resetBlocks();
                                new Start();
                                BroadCast::sendTipServer("GAME+1_TIP",array($winners));
                                BroadCast::sendMessageServer("GAME+1_BROADCAST",array($player->getNameTag(), $winners));
                                BroadCast::sendMessageServer("GAME_POINT_BROADCAST",array($winners,$this->points[$player->getTeam()]));

                                foreach (Game::getInstance()->getPlaying() as $player) {

                                    $player->getInventory()->clearAll();
                                    $player->getArmorInventory()->clearAll();
                                    $player->removeAllEffects();
                                    Game::getTeams()->TeleportToSpawn($player);
                                    $this->giveKit($player);
                                    $player->setFood(20);
                                    $player->setHealth(20);
                                    $player->setGamemode(0);

                                    $pk = new LevelSoundEventPacket();
                                    $pk->position = $player;
                                    $pk->sound = LevelSoundEventPacket::SOUND_BLAST;
                                    $pk->extraData = 1;
                                    $pk->disableRelativeVolume = true;
                                    $player->sendDataPacket($pk);

                                }
                                return;

                            }

                        }

                    }

                }

            }

        }

    }

    /**
     * @param GameDamageEvent $event
     */
    public function onDamage(GameDamageEvent $event)
    {
        $cause = $event->getCause();
        $player = $event->getPlayer();
        $damage = $event->getDamage();

        if ($player->getMode() === GAPlayer::MODE_PLAYER) {

            if ($cause === EntityDamageEvent::CAUSE_FALL) {

                $event->setCancelled();
                return;

            } else {

                if ($damage >= $player->getHealth()) {

                    Game::getTeams()->TeleportToSpawn($player);
                    $player->setFood(20);
                    $player->setHealth(20);
                    $player->setGamemode(0);
                    $player->getInventory()->clearAll();
                    $player->removeAllEffects();
                    $player->getArmorInventory()->clearAll();
                    $event->setCancelled();
                    $this->giveKit($player);

                    if (!is_null($player->getDamager())) {

                        BroadCast::sendMessageServer("GAME_DIED_WHIT_CAUSE_BY_ENTITY", array($player->getDisplayName(), $event->getDamageName(), $player->getDamager()));
                        $player->setDamager(null);

                    } else {

                        BroadCast::sendMessageServer("GAME_DIED_WHIT_CAUSE", array($player->getDisplayName(), $event->getDamageName()));
                        $player->setDamager(null);

                    }

                    $pk = new LevelEventPacket();
                    $pk->position = $player;
                    $pk->evid = LevelEventPacket::EVENT_SOUND_ORB;
                    $pk->data = 1;
                    $player->sendDataPacket($pk);

                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 999999*20, 1, false));
                }

            }

        }

    }

    public function onChat(GameChatEvent $event)
    {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        if ($message[0] === "@" or count(Game::getInstance()->getPlaying()) <= 2) {

            $message = str_replace("@", "", $message);

            $event->setFormat("§a[Global] " . "§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getNameTag() . " §l» §r§7" . $message);

        } else {

            $event->setCancelled();

            foreach (Game::getInstance()->getPlaying() as $player2) {

                if ($player->getTeam() == $player2->getTeam()) {

                    $player2->sendMessage("§7[§5" . $player->getLevelWithXP() . "§7] " . $player->getNameTag() . " §l» §r§7" . $message);

                }

            }

        }
    }

    /**
     * @param GameHungerEvent $event
     */
    public function onHunger(GameHungerEvent $event)
    {
        $event->setCancelled();
    }

    /**
     * @param GameDropEvent $event
     */
    public function onDrop(GameDropEvent $event)
    {
        $event->setCancelled();
    }

    /**
     * @param GAPlayer $player
     */
    public function giveKit(GAPlayer $player)
    {
        $color = $player->getTeam() === "Red" ? array(0x00ff0000,14) : array(0x002400ff,11);

        $sword = Item::get(267, 0, 1);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),10));
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::KNOCKBACK),1));
        $sword->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Sword");

        $pickaxe = Item::get(278, 0, 1);
        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),10));
        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY),2));
        $pickaxe->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Pickaxe");

        $goldenapple = Item::get(322, 0, 64);
        $goldenapple->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Golden Apple");

        $blocks = Item::get(Item::CONCRETE, $color[1], 2112);
        $blocks->setCustomName(TextFormat::RESET . TextFormat::GREEN . TextFormat::BOLD . "Blocks");

        $player->getInventory()->setItem(0,$sword);
        $player->getInventory()->setItem(1,$pickaxe);
        $player->getInventory()->setItem(2,$goldenapple);
        $player->getInventory()->addItem($blocks);

        $helmet = Item::get(Item::LEATHER_HELMET);
        $chestplate = Item::get(Item::LEATHER_CHESTPLATE);
        $leggings = Item::get(Item::LEATHER_LEGGINGS);
        $boots = Item::get(Item::LEATHER_BOOTS);

        $tempTag = new CompoundTag("", []);
        $tempTag->setInt("customColor", $color[0]);

        $helmet->setCompoundTag($tempTag);
        $player->getArmorInventory()->setHelmet($helmet);

        $chestplate->setCompoundTag($tempTag);
        $player->getArmorInventory()->setChestplate($chestplate);

        $leggings->setCompoundTag($tempTag);
        $player->getArmorInventory()->setLeggings($leggings);

        $boots->setCompoundTag($tempTag);
        $player->getArmorInventory()->setBoots($boots);

        $player->getArmorInventory()->sendContents($player);
    }

}
