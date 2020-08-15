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
use pocketmine\item\Item;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class GameDropEvent extends PluginEvent implements Cancellable
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * @var Item
     */
    private $item;

    /**
     * GameDropEvent constructor.
     * @param GAPlayer $player
     * @param Item $item
     */
    public function __construct(GAPlayer $player, Item $item){
        parent::__construct(Game::getInstance());
        $this->player = $player;
        $this->item = $item;
    }

    /**
     * @return GAPlayer
     */
    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

    /**
     * @return Item
     */
    public function getItem() : Item
    {
        return $this->item;
    }

}