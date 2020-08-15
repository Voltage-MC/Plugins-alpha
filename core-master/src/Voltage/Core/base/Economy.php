<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 16/04/2019
 * Time: 14:59
 */
namespace Voltage\Core\base;

use Voltage\Core\utils\API;
use Voltage\Core\utils\MySQL;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class Economy
{
    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM economy WHERE name = '" . strtolower($name) . "'");
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

        if (!self::exists($name)) {

            MySQL::sendDB("INSERT INTO economy (name , money, box_keys, credits) VALUES ('" . $name . "', 0, 5, 20)");
            $my->close();
            return true;

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

        $economy = mysqli_fetch_row($my->query("SELECT * FROM economy WHERE name = '"  . strtolower($name) . "'"));
        $my->close();

        return is_null($economy) ? [strtolower($name), 0, 5, 20] : $economy;

        /*
         * 1 = money
         * 2 = box_keys
         * 3 = credits
         */

    }

    /**
     * @param string $name
     * @return int
     */
    public static function getMoney(string $name) : int
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[1];
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function setMoney(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE economy SET  money = '" . $amount. "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function addMoney(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $money = self::getMoney($name) + $amount;
            MySQL::sendDB("UPDATE economy SET  money = '" . $money . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function reduceMoney(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $money = self::getMoney($name) - $amount;
            MySQL::sendDB("UPDATE economy SET  money = '" . $money . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getKeys(string $name) : int
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[2];
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function setKeys(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE economy SET  box_keys = '" . $amount. "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function addKeys(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $keys = self::getKeys($name) + $amount;
            MySQL::sendDB("UPDATE economy SET  box_keys = '" . $keys . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function reduceKeys(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $keys = self::getKeys($name) - $amount;
            MySQL::sendDB("UPDATE economy SET  box_keys = '" . $keys . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getCredits(string $name) : int
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[3];
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function setCredits(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE economy SET  credits = '" . $amount. "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function addCredits(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $credits = self::getCredits($name) + $amount;
            MySQL::sendDB("UPDATE economy SET  credits = '" . $credits . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function reduceCredits(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $credits = self::getCredits($name) - $amount;
            MySQL::sendDB("UPDATE economy SET  credits = '" . $credits . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

}