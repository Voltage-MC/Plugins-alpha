<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 24/04/2019
 * Time: 00:40
 */

namespace Voltage\Api\games;


use pocketmine\block\Block;
use pocketmine\math\Vector3;
use Voltage\Api\Game;

class Map
{
    /**
     * @param bool $bool
     */
    public function setSave(bool $bool = false)
    {
        Game::getInstance()->getServer()->getDefaultLevel()->setAutoSave($bool);
    }

    /**
     * @param Block $block
     * @return bool
     */
    public function isBlockSet(Block $block) : bool
    {
        foreach(Game::getInstance()->blocks as $pos){

            if ($pos["x"] == $block->getX() && $pos["y"] == $block->getY() && $pos["z"] == $block->getZ()) {

                return false;

            }

        }

        return true;
    }

    /**
     * @param Block $block
     */
    public function addBlock(Block $block)
    {
        Game::getInstance()->blocks[] = array("x" => $block->getX(), "y" => $block->getY(), "z" => $block->getZ());
    }

    /**
     * @param Block $block
     */
    public function delBlock(Block $block)
    {
        $array = array_search(array("x" => $block->getX(), "y" => $block->getY(), "z" => $block->getZ()),Game::getInstance()->blocks);
        unset(Game::getInstance()->blocks[$array]);
    }

    public function resetBlocks() : void
    {
        if (!isset(Game::getInstance()->blocks)) return;

        foreach (Game::getInstance()->blocks as $pos) {

            Game::getInstance()->getServer()->getDefaultLevel()->setBlock(new Vector3($pos["x"], $pos["y"], $pos["z"]), Block::get(0), false, false);
            $array = array_search(array("x" => $pos["x"], "y" => $pos["y"], "z" => $pos["z"]),Game::getInstance()->blocks);
            unset(Game::getInstance()->blocks[$array]);

        }

    }

}