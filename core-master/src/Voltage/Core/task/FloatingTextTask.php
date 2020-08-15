<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 18:29
 */

namespace Voltage\Core\task;

use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use Voltage\Api\provider\Provider;
use Voltage\Core\base\Gambler;
use Voltage\Core\base\GameData;
use Voltage\Core\base\Server;
use Voltage\Core\Core;
use pocketmine\scheduler\Task;
use Voltage\Core\VOLTPlayer;

class FloatingTextTask extends Task
{
    public static $texts = [];

    public function __construct()
    {
        Core::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20 * 10);
    }

    public function onRun(int $currentTick)
    {
        foreach (self::$texts as $floatingtext) {

            $type = $floatingtext[0];
            $level = $floatingtext[1];
            $floatingtext = $floatingtext[2];

            foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

                if ($player instanceof VOLTPlayer) {

                    if ($player->isOnline()) {

                        if ($floatingtext instanceof FloatingTextParticle) {

                            if ($player->getLevel()->getFolderName() === $level) {

                                if ($player->distance($floatingtext->asVector3()) <= 5 * 16) {

                                    if ($floatingtext->isInvisible()) {

                                        $floatingtext->setInvisible(false);

                                    }

                                    $text = $this->translateMessage($type, $player);

                                    if ($floatingtext->getText() != $text) {

                                        $floatingtext->setText($text);

                                    }

                                    $player->getLevel()->addParticle($floatingtext, [$player]);

                                } else {

                                    if (!$floatingtext->isInvisible()) {

                                        $floatingtext->setInvisible();

                                    }

                                    $player->getLevel()->addParticle($floatingtext, [$player]);

                                }

                            } else {

                                if (!$floatingtext->isInvisible()) {

                                    $floatingtext->setInvisible();

                                }

                                $player->getLevel()->addParticle($floatingtext, [$player]);

                            }

                        }

                    }

                }

            }

        }

    }

    private function translateMessage(string $type, VOLTPlayer $player)
    {
        $array = array();

        switch ($type) {

            case "FLOATING_TEXT_WELCOME":
                $array = array(Server::getNetworkCount(), $player->getDisplayName());
                break;

            case "FLOATING_TEXT_TOP_HIKABRAIN":
                $type = "FLOATING_TEXT_TOP";
                $array = array(
                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[0])],
                    GameData::getAll("hb")[0],
                    GameData::getWin(GameData::getAll("hb")[0],"hb"),
                    GameData::getLost(GameData::getAll("hb")[0],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[1])],
                    GameData::getAll("hb")[1],
                    GameData::getWin(GameData::getAll("hb")[1],"hb"),
                    GameData::getLost(GameData::getAll("hb")[1],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[2])],
                    GameData::getAll("hb")[2],
                    GameData::getWin(GameData::getAll("hb")[2],"hb"),
                    GameData::getLost(GameData::getAll("hb")[2],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[3])],
                    GameData::getAll("hb")[3],
                    GameData::getWin(GameData::getAll("hb")[3],"hb"),
                    GameData::getLost(GameData::getAll("hb")[3],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[4])],
                    GameData::getAll("hb")[4],
                    GameData::getWin(GameData::getAll("hb")[4],"hb"),
                    GameData::getLost(GameData::getAll("hb")[4],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[5])],
                    GameData::getAll("hb")[5],
                    GameData::getWin(GameData::getAll("hb")[5],"hb"),
                    GameData::getLost(GameData::getAll("hb")[5],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[6])],
                    GameData::getAll("hb")[6],
                    GameData::getWin(GameData::getAll("hb")[6],"hb"),
                    GameData::getLost(GameData::getAll("hb")[6],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[7])],
                    GameData::getAll("hb")[7],
                    GameData::getWin(GameData::getAll("hb")[7],"hb"),
                    GameData::getLost(GameData::getAll("hb")[7],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[8])],
                    GameData::getAll("hb")[8],
                    GameData::getWin(GameData::getAll("hb")[8],"hb"),
                    GameData::getLost(GameData::getAll("hb")[8],"hb"),

                    VOLTPlayer::RANK[Gambler::getRank(GameData::getAll("hb")[9])],
                    GameData::getAll("hb")[9],
                    GameData::getWin(GameData::getAll("hb")[9],"hb"),
                    GameData::getLost(GameData::getAll("hb")[9],"hb"),
                    );
                break;

        }

        return $player->messageToTranslate($type, $array);
    }

    public static function add(string $type, string $level, Vector3 $pos, string $title)
    {
        self::$texts[] = [$type, $level, new FloatingTextParticle($pos->add(0,2), "", $title)];
    }

    public static function get() : array
    {
        return self::$texts;
    }

}