<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 28/04/2019
 * Time: 19:21
 */

namespace Voltage\Core\utils;

use Voltage\Core\base\Request;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;

class BroadCast
{

    public static function sendMessageNetwork(string $type, array $info = [])
    {
        Request::add("BROADCAST_MESSAGE", array($type,implode(",", $info)));
    }

    public static function sendTipNetwork(string $type, array $info = [])
    {
        Request::add("BROADCAST_TIP", array($type,implode(",", $info)));
    }

    public static function sendMessageServer(string $type, array $info = [])
    {
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

            if ($player instanceof VOLTPlayer) {

                $player->sendMessage(Core::getPrefix() . $player->messageToTranslate($type, $info));

            }

        }

    }

    public static function sendTipServer(string $type, array $info = [])
    {
        foreach (Core::getInstance()->getServer()->getOnlinePlayers() as $player) {

            if ($player instanceof VOLTPlayer) {

                $player->sendTip(Core::getPrefix() . $player->messageToTranslate($type, $info));

            }

        }

    }

}