<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 15:23
 */

namespace Voltage\Api\event;

use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class GameDamageByEntityEvent extends PluginEvent implements Cancellable
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * @var GAPlayer
     */
    private $player2;

    /**
     * @var float
     */
    private $damage;

    /**
     * GameDamageByEntityEvent constructor.
     * @param GAPlayer $player
     * @param GAPlayer $player2
     * @param float $damage
     */
    public function __construct(GAPlayer $player, GAPlayer $player2, float $damage){
        parent::__construct(Game::getInstance());
        $this->player = $player;
        $this->player2 = $player2;
        $this->damage = $damage;
    }

    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

    public function getDamager() : GAPlayer
    {
        return $this->player2;
    }

    public function getDamage() : float
    {
        return $this->damage;
    }

}