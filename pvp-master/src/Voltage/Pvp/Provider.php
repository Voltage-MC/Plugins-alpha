<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 11/06/2019
 * Time: 19:33
 */

namespace Voltage\Pvp;


use Voltage\Core\utils\MySQL;

class Provider
{
    const Table = "pvp";

    public static function create()
    {
        $my = MySQL::getData();
        $my->query("CREATE TABLE IF NOT EXISTS " . self::Table . " (name TEXT, kills INT, deaths INT, elos INT, streak INT)");
        $my->close();
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM " . self::Table . " WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param PVPPlayer $player
     * @return bool
     */
    public static function setDefaultData(PVPPlayer $player) : bool
    {
        $name = strtolower($player->getName());
        $my = MySQL::getData();

        if (!self::exists($name)) {

            MySQL::sendDB("INSERT INTO " . self::Table . " (name , kills, deaths, elos, streak) VALUES ('" . $name . "', 0, 0, 0, 0)");
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

        $provider = mysqli_fetch_row($my->query("SELECT * FROM " . self::Table . " WHERE name = '"  . strtolower($name) . "'"));
        $my->close();
        return is_null($provider) ? [strtolower($name), 0, 0, 0, 0] : $provider;

        /*
         * 1 = kills
         * 2 = deaths
         * 3 = elos
         */

    }

    /**
     * @param string $name
     * @return int
     */
    public static function getKills(string $name) : int
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
    public static function setKills(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE " . self::Table . " SET kills = '" . $amount. "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
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
    public static function addKills(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $kill = self::getKills($name) + $amount;
            MySQL::sendDB("UPDATE " . self::Table . " SET kills = '" . $kill . "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getDeaths(string $name) : int
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
    public static function setDeaths(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE " . self::Table . " SET deaths = '" . $amount. "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
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
    public static function addDeaths(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $death = self::getDeaths($name) + $amount;
            MySQL::sendDB("UPDATE " . self::Table . " SET deaths = '" . $death . "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getElos(string $name) : int
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
    public static function setElos(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE " . self::Table . " SET elos = '" . $amount. "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
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
    public static function addElos(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $elo = self::getElos($name) + $amount;
            MySQL::sendDB("UPDATE " . self::Table . " SET elos = '" . $elo . "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
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
    public static function reduceElos(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $elo = self::getElos($name) - $amount;

            if ($elo < 0) {

                $elo = 0;

            }

            MySQL::sendDB("UPDATE " . self::Table . " SET elos = '" . $elo . "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return int
     */
    public static function getStreak(string $name) : int
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[4];
    }

    /**
     * @param string $name
     * @param int $amount
     * @return bool
     */
    public static function setStreak(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            if ($amount > self::getStreak($name)) {

                MySQL::sendDB("UPDATE " . self::Table . " SET streak = '" . $amount. "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");

            }
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
    public static function reduceStreak(string $name, int $amount) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $streak = self::getStreak($name) - $amount;
            MySQL::sendDB("UPDATE " . self::Table . " SET streak = '" . $streak . "' WHERE name = '" . $my->real_escape_string(strtolower($name)) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    public static function getAllElos() : array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM " . self::Table);
        $my->close();

        if ($res->num_rows > 0) {

            while($row = mysqli_fetch_assoc($res)) {

                $all[] = $row["name"] . $row["elos"];

            }

        }

        return $all;
    }

    public static function getPlayers() : array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM " . self::Table);
        $my->close();

        if ($res->num_rows > 0) {

            while($row = mysqli_fetch_assoc($res)) {

                $all[] = $row["name"];

            }

        }

        return $all;
    }

    public static function getPlayer(string $name) : ?string
    {
        $found = null;
        $name = strtolower($name);

        $delta = PHP_INT_MAX;

        foreach(self::getPlayers() as $names){

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

}