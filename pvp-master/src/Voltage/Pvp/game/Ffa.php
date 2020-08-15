<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 11/06/2019
 * Time: 18:48
 */

namespace Voltage\Pvp\game;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat as TE;
use Voltage\Core\Core;
use Voltage\Pvp\Pvp;
use Voltage\Pvp\PVPPlayer;

class Ffa
{
    const GAPPLE = "Gapple";

    public function __construct()
    {
    }

    public function onJoinGapple(PVPPlayer $player)
    {
        $level = Core::getInstance()->getServer()->getLevelByName(self::GAPPLE);
        $spawn = $level->getSafeSpawn();

        if (!$player->getLevel()->isChunkLoaded($spawn->getX(), $spawn->getZ())) $player->getLevel()->loadChunk($spawn->getX(), $spawn->getZ());

        $player->teleport(new Position($spawn->getX(), $spawn->getY(), $spawn->getZ(), $level));
        $player->removeAllEffects();
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->setFood("20");
        $player->setHealth("20");

        $this->kitOutils($player);
    }

    public function kitOutils(PVPPlayer $player)
    {
        $chest = Item::get(Item::CHEST, 0, 1);
        $chest->setCustomName(TE::BOLD . TE::WHITE . " » " . TE::LIGHT_PURPLE . "Kit" . TE::WHITE . " « ");
        $bed = Item::get(Item::BED, 10, 1);
        $bed->setCustomName("§l§cBack To Hub\n§r§7(Tap)");

        $player->getInventory()->setItem(0, $chest);
        $player->getInventory()->setItem(8, $bed);
    }

    public function kitGapple(PVPPlayer $player)
    {
        $inventory = $player->getInventory();
        $inventoryarmor = $player->getArmorInventory();

        $inventory->clearAll();
        $inventoryarmor->clearAll();

        $sword = Item::get(Item::IRON_SWORD);
        $steak = Item::get(Item::STEAK, 0, 64);
        $gapple = Item::get(Item::GOLDEN_APPLE, 0, 10);
        $helmet = Item::get(Item::IRON_HELMET);
        $chestplate = Item::get(Item::IRON_CHESTPLATE);
        $leggings = Item::get(Item::IRON_LEGGINGS);
        $boots = Item::get(Item::IRON_BOOTS);

        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),9));
        $sword->setCustomName(TE::GREEN . TE::BOLD . "Sword");

        $steak->setCustomName(TE::GREEN . TE::BOLD . "Steak");

        $gapple->setCustomName(TE::GREEN . TE::BOLD . "Gapple");

        $inventory->addItem($sword, $steak, $gapple);

        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),9));
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION),1));
        $helmet->setCustomName(TE::GREEN . TE::BOLD . "Helmet");

        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),9));
        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION),1));
        $chestplate->setCustomName(TE::GREEN . TE::BOLD . "Chestplate");

        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),9));
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION),1));
        $leggings->setCustomName(TE::GREEN . TE::BOLD . "Leggings");

        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING),9));
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION),1));
        $boots->setCustomName(TE::GREEN . "Boots");

        $inventoryarmor->setContents([$helmet, $chestplate, $leggings, $boots]);

        Pvp::getInstance()->gapple[] = strtolower($player->getName());
        $player->sendMessage(Core::PREFIX . $player->messageToTranslate("PVP_GIVE_KIT"));
    }

    public function removeGapplePlayer(string $name)
    {
        unset(Pvp::getInstance()->gapple[array_search(strtolower($name),Pvp::getInstance()->gapple)]);
    }

}