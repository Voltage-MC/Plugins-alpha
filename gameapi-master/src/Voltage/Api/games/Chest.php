<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 26/04/2019
 * Time: 12:28
 */

namespace Voltage\Api\games;

use pocketmine\inventory\ChestInventory;
use pocketmine\item\Armor;
use pocketmine\item\Bow;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\Pickaxe;
use pocketmine\item\Sword;
use pocketmine\math\Vector3;
use Voltage\Api\Game;

use pocketmine\tile\Chest as TileChest;

class Chest
{
    /**
     * @var array
     */
    private $enchantements = [];

    public const RARITY_COMMON = 8;
    public const RARITY_UNCOMMON = 5;
    public const RARITY_RARE = 2;
    public const RARITY_MYTHIC = 1;


    //$enchantement = ["epee" => [[id , min-niv, max_niv, rare],[id , min-niv, max_niv, rare]], ...];
    public function addEnchants(array $enchantements)
    {
        $this->enchantements = $enchantements;
    }

    public function getEnchants() : array
    {
        return $this->enchantements;
    }

    public function refillNormalChests()
    {
        $items = Game::getInstance()->getData()["Normal-chestitems"];
        $pos = Game::getInstance()->getData()["Normal-chestspawns"];

        foreach($pos as $vector) {

            $chest = Game::getInstance()->getServer()->getDefaultLevel()->getTile(new Vector3($vector[0],$vector[1],$vector[2]));

            if($chest instanceof TileChest) {

                $chest->getInventory()->clearAll();

                if($chest->getInventory() instanceof ChestInventory) {

                    for($i = 0; $i <= 26; $i++) {

                        $rand = mt_rand(1,5);

                        if($rand === 1) {

                            $r = array_rand($items);
                            $item = $items[$r];

                            $chest->getInventory()->setItem($i, Item::get($item[0],$item[1],$item[2]));

                        }

                    }

                }

            }

        }

    }

    /**
     * #Normal

     * Armure : P3
     * Epee : T3
     * Fire : F1
     * Arc : Puissance 2

     * #Insane

     * Armure : P4
     * Epee : T5
     * Fire : F2
     * Arc : Puissance 4

     * @param array $enchantement
     */
    public function refillOpChests()
    {
        $enchantement = $this->getEnchants();

        $sword = $enchantement["sword"];
        $pickaxe = $enchantement["pickaxe"];
        $armor = $enchantement["armor"];
        $bow = $enchantement["bow"];

        $items = Game::getInstance()->getData()["Op-chestitems"];
        $pos = Game::getInstance()->getData()["Op-chestspawns"];

        foreach($pos as $vector) {

            $chest = Game::getInstance()->getServer()->getDefaultLevel()->getTile(new Vector3($vector[0],$vector[1],$vector[2]));

            if($chest instanceof TileChest) {

                $chest->getInventory()->clearAll();

                if($chest->getInventory() instanceof ChestInventory) {

                    for($i = 0; $i <= 26; $i++) {

                        $rand = mt_rand(1,3);

                        if($rand === 1) {

                            $r = array_rand($items);
                            $data = $items[$r];
                            $item = Item::get($data[0],$data[1],$data[2]);

                            $type = null;

                            if ($item instanceof Sword) {

                                $type = $sword;

                            } else if ($item instanceof Pickaxe) {

                                $type = $pickaxe;

                            } else if ($item instanceof Armor) {

                                $type = $armor;

                            } else if ($item instanceof Bow) {

                                $type = $bow;

                            }

                            if (!is_null($type) and is_array($type)) {

                                foreach ($type as $enchant) {

                                    $rarity = $enchant[3];
                                    $rand = mt_rand(0, 10);

                                    if ($rand <= $rarity) {

                                        $id = $enchant[0];
                                        $niv = mt_rand($enchant[1],$enchant[2]);

                                        $enchant = new EnchantmentInstance(Enchantment::getEnchantment($id),$niv);

                                        $item->addEnchantment($enchant);

                                    }

                                }

                            }

                            $chest->getInventory()->setItem($i, $item);

                        }

                    }

                }

            }

        }

    }

}