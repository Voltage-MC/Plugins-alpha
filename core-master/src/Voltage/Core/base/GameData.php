<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 16/04/2019
 * Time: 14:59
 */
namespace Voltage\Core\base;

use Voltage\Core\utils\MySQL;
use Voltage\Core\VOLTPlayer;

class GameData
{
    /**
     * @param string $name
     * @param string $server
     * @return bool
     */
    public static function exists(string $name, string $server): bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM " . $server . " WHERE name = '" . strtolower($name) . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param VOLTPlayer $player
     * @param string $server
     * @return bool
     */
    public static function setDefaultData(VOLTPlayer $player, string $server): bool
    {
        $name = strtolower($player->getName());
        $my = MySQL::getData();

        if (!self::exists($name, $server)) {

            MySQL::sendDB("INSERT INTO " . $server . " (name , wins, losts) VALUES ('" . $name . "', 0, 0)");
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param string $server
     * @return array
     */
    public static function get(string $name, string $server): array
    {
        $my = MySQL::getData();

        $economy = mysqli_fetch_row($my->query("SELECT * FROM " . $server . " WHERE name = '" . strtolower($name) . "'"));
        $my->close();

        return is_null($economy) ? [strtolower($name), 0, 0] : $economy;

        /*
         * 1 = win
         * 2 = lost
         */

    }

    /**
     * @param string $name
     * @param string $server
     * @return int
     */
    public static function getWin(string $name, string $server): int
    {
        $name = strtolower($name);
        $data = self::get($name, $server);
        return $data[1];
    }

    /**
     * @param string $name
     * @param string $server
     * @return bool
     */
    public static function addWin(string $name, string $server): bool
    {
        $my = MySQL::getData();

        if (self::exists($name, $server)) {

            $money = self::getWin($name, $server) + 1;
            MySQL::sendDB("UPDATE " . $server . " SET  wins = '" . $money . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param string $server
     * @return int
     */
    public static function getLost(string $name, string $server): int
    {
        $name = strtolower($name);
        $data = self::get($name, $server);
        return $data[2];
    }

    /**
     * @param string $name
     * @param string $server
     * @return bool
     */
    public static function addLost(string $name, string $server): bool
    {
        $my = MySQL::getData();

        if (self::exists($name, $server)) {

            $money = self::getLost($name, $server) + 1;
            MySQL::sendDB("UPDATE " . $server . " SET  losts = '" . $money . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $server
     * @return array
     */
    public static function getAll(string $server): array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM " . $server . " ORDER BY wins DESC LIMIT 10");
        $my->close();

        if ($res->num_rows > 0) {

            while ($row = mysqli_fetch_assoc($res)) {

                $name = $row["name"];

                if (!isset($all[$name])) $all[] = $name;

            }

        }

        return $all;

    }

}