<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 12/06/2019
 * Time: 00:56
 */

namespace Voltage\Core\utils;


use Voltage\Core\base\Gambler;
use Voltage\Core\base\Server;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;

class Network
{
    const IP = "5.196.141.146";
    const PORT =
        [
            19132 => "Lobby",
            19140 => "Pvp",
            19150 => "Faction",
            19160 => "HB1",
            19161 => "HB2",
            19162 => "HB3",
            19163 => "HB4",
            19164 => "HB5",
            19165 => "HB6",
            19166 => "HB7",
            19167 => "HB8",
            19168 => "HB9",
            19169 => "HB10",
        ];
    const NAME =
        [
            "Lobby" => 19132,
            "Pvp" => 19140,
            "Faction" => 19150,
            "HB1" => 19160,
            "HB2" => 19161,
            "HB3" => 19162,
            "HB4" => 19163,
            "HB5" => 19164,
            "HB6" => 19165,
            "HB7" => 19166,
            "HB8" => 19167,
            "HB9" => 19168,
            "HB10" => 19169,
        ];

    /**
     * @return string
     */
    public static function getServer() : string
    {
        $port = Core::getInstance()->getServer()->getPort();
        return self::PORT[$port];
    }

    /**
     * @param string $name
     * @return string[]|null
     */
    public static function getPlayer(string $name) : ?string
    {
        $found = null;
        $name = strtolower($name);

        $delta = PHP_INT_MAX;

        foreach(Server::getAllNetwork() as $names){

            if(stripos($names, $name) === 0){

                $curDelta = strlen($names) - strlen($name);

                if($curDelta < $delta){

                    $found = $names;
                    $delta = $curDelta;

                }

                if($curDelta === 0){

                    break;

                }

            }

        }

        return $found;
    }

    public static function getAllPlayer(string $name) : ?string
    {
        $found = null;
        $name = strtolower($name);

        $delta = PHP_INT_MAX;

        foreach(Gambler::getAllPlayers() as $names){

            if(stripos($names, $name) === 0){

                $curDelta = strlen($names) - strlen($name);

                if($curDelta < $delta){

                    $found = $names;
                    $delta = $curDelta;

                }

                if($curDelta === 0){

                    break;

                }

            }

        }

        return $found;
    }

    public static function getPortHikabrains() : array
    {
        return
            [
                self::NAME["HB1"] => "1vs1",
                self::NAME["HB2"] => "1vs1",
                self::NAME["HB3"] => "1vs1",
                self::NAME["HB4"] => "1vs1",
                self::NAME["HB5"] => "2vs2",
                self::NAME["HB6"] => "2vs2",
                self::NAME["HB7"] => "2vs2",
                self::NAME["HB8"] => "4vs4",
                self::NAME["HB9"] => "4vs4",
                self::NAME["HB10"] => "2vs2vs2vs2",
            ];
    }

    public static function joinHikaBrain(VOLTPlayer $player) : bool
    {
        foreach (Network::getPortHikabrains() as $port => $type) {

            if (Server::getOnlineServer($port) === "§a§lOnline") {

                $player->transfer(Network::IP, $port);
                return true;

            }

        }
        return false;
    }

}