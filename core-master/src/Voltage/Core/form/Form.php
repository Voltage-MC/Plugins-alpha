<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 03/05/2019
 * Time: 18:20
 */

namespace Voltage\Core\form;

use pocketmine\form\Form as IForm;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;

abstract class Form implements IForm
{
    /** @var array */
    protected $data = [];

    /** @var callable|null */
    private $callable;

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @deprecated
     * @see Player::sendForm()
     *
     * @param Player $player
     */
    public function sendToPlayer(Player $player) : void
    {
        $pk = new LevelEventPacket();
        $pk->position = $player;
        $pk->evid = LevelEventPacket::EVENT_SOUND_POP;
        $pk->data = 1;
        $player->sendDataPacket($pk);

        $player->sendForm($this);
    }

    /**
     * @return callable|null
     */
    public function getCallable() : ?callable
    {
        return $this->callable;
    }

    /**
     * @param callable|null $callable
     */
    public function setCallable(?callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @param mixed $data
     */
    public function handleResponse(Player $player, $data) : void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    /**
     * @param $data
     */
    public function processData(&$data) : void
    {
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

}