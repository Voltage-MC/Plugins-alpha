<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 18:29
 */

namespace Voltage\Core\task;

use Voltage\Core\base\Request;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;
use pocketmine\scheduler\Task;

class RequestUploadTask extends Task
{

    public function __construct()
    {
        Core::getInstance()->getScheduler()->scheduleRepeatingTask($this, 20 * 5);
    }

    public function onRun(int $currentTick)
    {
        foreach (Request::getAll() as $id => $array) {

            $this->sendRequest($id,$array);

        }

    }

    public function sendRequest(int $id, array $array)
    {
        $type = $array[0];
        $info = $array[1];
        $temp = $array[2];
        $time = $array[3];

        if ($time < time()) {

            Request::del($id);
            return;

        }

        switch ($type) {

            case "FRIEND_REQUEST_ADD":

                if ($temp >= 1) {

                    Request::del($id);
                    return;

                }

                $player2 = Core::getInstance()->getServer()->getPlayer($info[1]);

                if ($player2 instanceof VOLTPlayer) {

                    Request::addTemp($id);
                    $player2->addFriendRequest($info[0]);
                    $player2->sendMessage($player2->messageToTranslate("FRIEND_ADD_RECEIVED",array($info[0])));

                }
                break;

            case "FRIEND_REMOVE_PLAYER":

                if ($temp >= 1) {

                    Request::del($id);
                    return;

                }

                $player2 = Core::getInstance()->getServer()->getPlayer($info[1]);

                if ($player2 instanceof VOLTPlayer) {

                    Request::addTemp($id);
                    $player2->sendMessage($player2->messageToTranslate("FRIEND_REMOVE_RECEIVED",array($info[0])));

                }

                break;

            case "FRIEND_REQUEST_ACCEPT":

                if ($temp >= 1) {

                    Request::del($id);
                    return;

                }

                $player2 = Core::getInstance()->getServer()->getPlayer($info[1]);

                if ($player2 instanceof VOLTPlayer) {

                    Request::addTemp($id);
                    $player2->sendMessage($player2->messageToTranslate("FRIEND_REQUEST_ACCEPTED_RECEIVED",array($info[0])));

                }

                break;

            case "FRIEND_REQUEST_DECLINE":

                if ($temp >= 1) {

                    Request::del($id);
                    return;

                }

                $player2 = Core::getInstance()->getServer()->getPlayer($info[1]);

                if ($player2 instanceof VOLTPlayer) {

                    Request::addTemp($id);
                    $player2->sendMessage($player2->messageToTranslate("FRIEND_REQUEST_DECLINE_RECEIVED",array($info[0])));

                }

                break;

            case "BROADCAST_MESSAGE":

                foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

                    if ($player instanceof VOLTPlayer) {

                        $player->sendMessage(Core::getPrefix() . $player->messageToTranslate($info[0], array($info[1], $info[2])));

                    }

                }

                break;

            case "BROADCAST_TIP":

                foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

                    if ($player instanceof VOLTPlayer) {

                        $player->sendTip(Core::getPrefix() . $player->messageToTranslate($info[0], array($info[1], $info[2])));

                    }

                }

                break;

            case "BAN_REQUEST_ADD":

                if ($temp >= 1) {

                    Request::del($id);
                    return;

                }

                $player2 = Core::getInstance()->getServer()->getPlayer($info[1]);

                if ($player2 instanceof VOLTPlayer) {

                    Request::addTemp($id);
                    $player2->close("",$player2->messageToTranslate("BAN_KICK", array($info[0], $info[2], $info[3])));

                }

                break;

            case "KICK_REQUEST_ADD":

                if ($temp >= 1) {

                    Request::del($id);
                    return;

                }

                $player2 = Core::getInstance()->getServer()->getPlayer($info[1]);

                if ($player2 instanceof VOLTPlayer) {

                    Request::addTemp($id);

                    if (is_null($info[2])) {

                        $player2->close("",$player2->messageToTranslate("KICK_KICK", array($info[0], "indefinite")));

                    } else {

                        $player2->close("",$player2->messageToTranslate("KICK_KICK", array($info[0], $info[2])));

                    }

                }

                break;

        }

    }

}