<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 13/07/2019
 * Time: 22:01
 */

namespace Voltage\Game\task;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\scheduler\Task;
use Voltage\Api\Game;

class Start extends Task
{
    /**
     * @var int
     */
    public $time;

    /**
     * Start constructor.
     */
    public function __construct()
    {
        Game::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20);
        $this->time = 5;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick)
    {
        foreach (Game::getInstance()->getPlaying() as $player) {

            if ($this->time === 5) {

                $blindness = new EffectInstance(Effect::getEffect(Effect::BLINDNESS),6*20,5,false);
                $player->addEffect($blindness);
                $mining = new EffectInstance(Effect::getEffect(Effect::MINING_FATIGUE),6*20,2,false);
                $player->addEffect($mining);
                $player->addTitle($player->messageToTranslate("GAME_START_IN"), "§a§l" . $this->time);
                $player->setImmobile(true);

            } else if ($this->time <= 4 and $this->time > 0) {

                $player->addTitle($player->messageToTranslate("GAME_START_IN"), "§a§l" . $this->time);

                $pk = new LevelEventPacket();
                $pk->position = $player;
                $pk->evid = LevelEventPacket::EVENT_SOUND_ORB;
                $pk->data = 1;
                $player->sendDataPacket($pk);

            } else if ($this->time < 0){

                $player->setImmobile(false);
                $player->addTitle($player->messageToTranslate("GAME_GO"));
                $pk = new LevelEventPacket();
                $pk->position = $player;
                $pk->evid = LevelEventPacket::EVENT_SOUND_ANVIL_BREAK;
                $pk->data = 1;
                $player->sendDataPacket($pk);
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 999999*20, 1, false));

                Game::getInstance()->getScheduler()->cancelTask($this->getTaskId());

            }

        }
        $this->time--;

    }

}