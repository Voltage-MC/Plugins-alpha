<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 15:23
 */

namespace Voltage\Api\event;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class GamePlaceEvent extends PluginEvent implements Cancellable
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * @var Block
     */
    private $block;

    /**
     * GamePlaceEvent constructor.
     * @param GAPlayer $player
     * @param Block $block
     */
    public function __construct(GAPlayer $player, Block $block){
        parent::__construct(Game::getInstance());
        $this->player = $player;
        $this->block = $block;
    }

    /**
     * @return GAPlayer
     */
    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

    /**
     * @return Block
     */
    public function getBlock() : Block
    {
        return $this->block;
    }

}