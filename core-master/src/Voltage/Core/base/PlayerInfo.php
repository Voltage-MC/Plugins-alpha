<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 16/04/2019
 * Time: 14:59
 */
namespace Voltage\Core\base;

use Voltage\Core\Core;
use Voltage\Core\utils\API;
use Voltage\Core\utils\MySQL;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class PlayerInfo
{
    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM playerinfo WHERE name = '" . strtolower($name) . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param VOLTPlayer $player
     * @return bool
     */
    public static function setDefaultData(VOLTPlayer $player) : bool
    {
        $name = strtolower($player->getName());
        $my = MySQL::getData();

        $ip = $player->getAddress();
        $cid = $player->getClientId();
        $uuid = $player->getUniqueId();

        if (!self::exists($name)) {

            MySQL::sendDB("INSERT INTO playerinfo (name , ip, cid, uuid, vpn) VALUES ('". $name . "', '" . $ip . "', '" . $cid . "', '" . $uuid . "', '" . API::getProxy($ip) . "')");
            $my->close();
            return true;

        } else {

            MySQL::sendDB("UPDATE playerinfo SET ip = '" . $ip . "' WHERE name = '" . $name . "'");
            MySQL::sendDB("UPDATE playerinfo SET cid = '" . $cid . "' WHERE name = '" . $name . "'");
            MySQL::sendDB("UPDATE playerinfo SET uuid = '" . $uuid . "' WHERE name = '" . $name . "'");
            MySQL::sendDB("UPDATE playerinfo SET vpn = '" . API::getProxy($ip) . "' WHERE name = '" . $name . "'");

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return array
     */
    public static function get(string $name) : array
    {
        $my = MySQL::getData();

        $gambler = mysqli_fetch_row($my->query("SELECT * FROM playerinfo WHERE name = '"  . strtolower($name) . "'"));
        $my->close();
        return is_null($gambler) ? [strtolower($name), "0.0.0.0", "0", "0-0-0-0", "false"] : $gambler;

        /*
         * 1 = ip
         * 2 = cid
         * 3 = uuid
         * 4 = vpn
         */

    }

    /**
     * @param string $name
     * @return string
     */
    public static function getIp(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[1];
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getCid(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[2];
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getUUID(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[3];
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getVpn(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[4];
    }

}