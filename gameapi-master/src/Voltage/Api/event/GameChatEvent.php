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

class GameChatEvent extends PluginEvent implements Cancellable
{
    /**
     * @var GAPlayer
     */
    private $player;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $format;

    public function __construct(GAPlayer $player, string $message){
        parent::__construct(Game::getInstance());
        $this->player = $player;
        $this->message = $message;
        $this->format = null;
    }

    public function getPlayer() : GAPlayer
    {
        return $this->player;
    }

    public function setFormat(string $msg)
    {
        $this->format = $msg;
    }

    public function getFormat() : ?string
    {
        return $this->format;
    }

    public function getMessage() : string
    {
        return $this->message;
    }

}