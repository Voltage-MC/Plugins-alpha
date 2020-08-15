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

class Gambler
{
    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM gambler WHERE name = '" . strtolower($name) . "'");
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

            MySQL::sendDB("INSERT INTO gambler (name , lang, rank, playtime, xp, connect) VALUES ('". strtolower($name) . "', '". API::getCountry($player->getAddress()) . "', '".VOLTPlayer::RANK_PLAYER."', '0', '1', '". Network::getServer() . "')");
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

        $gambler = mysqli_fetch_row($my->query("SELECT * FROM gambler WHERE name = '"  . strtolower($name) . "'"));
        $my->close();
        return is_null($gambler) ? [strtolower($name), "en", 0, 0, 1, "Lobby"] : $gambler;

        /*
         * 1 = lang
         * 2 = rank
         * 3 = playtime
         * 4 = xp
         * 5 = connect
         */

    }

    /**
     * @param string $name
     * @return string
     */
    public static function getLang(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[1];
    }

    /**
     * @param string $name
     * @param string $lang
     * @return bool
     */
    public static function setLang(string $name, string $lang) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE gambler SET  lang ='" . $lang . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getRank(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[2];
    }

    /**
     * @param string $name
     * @param int $rank
     * @return bool
     */
    public static function setRank(string $name, int $rank) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE gambler SET  rank ='" . $rank . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getPlayTime(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[3];
    }

    /**
     * @param string $name
     * @param int $time
     * @return bool
     */
    public static function addPlayTime(string $name, int $time) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $time = (int) self::getPlayTime($name) + $time;
            MySQL::sendDB("UPDATE gambler SET playtime ='" . $time . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getXps(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[4];
    }

    /**
     * @param string $name
     * @param int $xp
     * @return bool
     */
    public static function addXps(string $name, int $xp) : bool
    {
        $my = MySQL::getData();

        if(self::exists($name)) {

            $xp = (int) self::getXps($name) + $xp;
            MySQL::sendDB("UPDATE gambler SET xp ='" . $xp . "' WHERE name = '" . strtolower($name) . "'");
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getConnect(string $name) :string
    {
        $name = strtolower($name);
        $data = self::get($name);
        return $data[5];
    }

    /**
     * @param string $name
     * @param string $data
     * @return bool
     */
    public static function setConnect(string $name, string $data) : bool
    {
        $name = strtolower($name);
        $my = MySQL::getData();

        if(self::exists($name)) {

            MySQL::sendDB("UPDATE gambler SET connect ='" . $data . "' WHERE name = '" . $name . "'");
            return true;

        }

        $my->close();
        return false;
    }

    public static function getAllPlayers() : array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM gambler");
        $my->close();

        if ($res->num_rows > 0) {

            while($row = mysqli_fetch_assoc($res)) {

                $name = $row["name"];
                $all[] = $name;

            }

        }

        return $all;
    }

    /**
     * @param VOLTPlayer $player
     */
    public static function addPermission(VOLTPlayer $player)
    {
        $permissions = null;

        if ($player->isStaff()) {

            switch ($player->getRank()) {

                case VOLTPlayer::RANK_TRAINEE:
                    $permissions = VOLTPlayer::PERMISSION_TRAINEE;
                    break;

                case VOLTPlayer::RANK_MOD:
                    $permissions = VOLTPlayer::PERMISSION_MOD;
                    break;

                case VOLTPlayer::RANK_ADMIN:
                    $permissions = VOLTPlayer::PERMISSION_ADMIN;
                    break;

                case VOLTPlayer::RANK_OWNER:
                    $permissions = VOLTPlayer::PERMISSION_OWNER;
                    break;

                default:
                    $permissions = null;
                    break;

            }

        }

        if (is_array($permissions)) {

            foreach ($permissions as $permission) {

                $attachment = $player->addAttachment(Core::getInstance());
                $attachment->setPermission($permission, true);

                $player->addAttachment(Core::getInstance(),$permission);

            }

        }

    }

}