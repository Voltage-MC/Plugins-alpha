<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 21/04/2019
 * Time: 15:23
 */

namespace Voltage\Api\event;

use pocketmine\event\Cancellable;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\plugin\PluginEvent;
use Voltage\Api\Game;
use Voltage\Api\GAPlayer;

class GameDamageEvent extends PluginEvent implements Cancellable
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * @var int
     */
    private $cause;

    /**
     * @var float
     */
    private $damage;

    /**
     * GameDamageEvent constructor.
     * @param GAPlayer $player
     * @param int $cause
     * @param float $damage
     */
    public function __construct(GAPlayer $player, int $cause, float $damage){
        parent::__construct(Game::getInstance());
        $this->player = $player;
        $this->cause = $cause;
        $this->damage = $damage;
    }

    /**
     * @return GAPlayer
     */
    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getCause() : int
    {
        return $this->cause;
    }

    /**
     * @return int
     */
    public function getDamage() : int
    {
        return $this->damage;
    }

    public function getDamageName() : string
    {
        switch ($this->getCause()) {

            case EntityDamageEvent::CAUSE_CONTACT: return "est mort de contact";
            //1
            case EntityDamageEvent::CAUSE_PROJECTILE: return "c'est pris une fleche";
            case EntityDamageEvent::CAUSE_SUFFOCATION: return "a suffoqué";
            case EntityDamageEvent::CAUSE_FALL: return "est mort de chute";
            case EntityDamageEvent::CAUSE_FIRE: return "a brulé";
            case EntityDamageEvent::CAUSE_FIRE_TICK: return "a brulé";
            case EntityDamageEvent::CAUSE_LAVA: return "est tombé dans la lave";
            case EntityDamageEvent::CAUSE_DROWNING: return "c'est noyé";
            case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION: return "a explosé";
            case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION: return "est mort a cause d'un creeper";
            case EntityDamageEvent::CAUSE_VOID: return "est mort tombé dans le vide";
            case EntityDamageEvent::CAUSE_SUICIDE: return "c'est sucidé";
            case EntityDamageEvent::CAUSE_MAGIC: return "a éte tué avec une potion";
            //14
            case EntityDamageEvent::CAUSE_STARVATION: return "est mort de faim";
        }

        return "Unknow";
    }

}