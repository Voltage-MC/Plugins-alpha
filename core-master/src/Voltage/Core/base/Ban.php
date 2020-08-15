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

class Ban
{
    /**
     * @param string $name
     * @return bool
     */
    public static function existsName(string $name) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM ban WHERE name = '" . strtolower($name) . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    public static function getId() : int
    {
        $my = MySQL::getData();

        $i = 1;
        $result = true;

        while ($result) {

            if (!$my->query("SELECT * FROM ban WHERE id = " . $i)->num_rows > 0 ? true : false) {

                break;

            }

            $i++;
        }

        $my->close();
        return $i;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function existsID(int $id) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM ban WHERE id = " . $id);
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param string $ip
     * @return bool
     */
    public static function existsIP(string $ip) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM ban WHERE ip = '" . $ip . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param string $cid
     * @return bool
     */
    public static function existsCID(string $cid) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM ban WHERE cid = '" . $cid . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public static function existsUUID(string $uuid) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM ban WHERE uuid = '" . $uuid . "'");
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    public static function add(string $name, string $by_name, string $ip, string $cid, string $uuid, string $time, string $reason) : bool
    {
        $my = MySQL::getData();

        if (!self::existsName($name)) {

            MySQL::sendDB("INSERT INTO ban (id, name , by_name, ip, cid, uuid, time_sec, reason) VALUES ('" . self::getId() . "', '". $name . "', '" . $by_name . "' , '" .  $ip . "', '" . $cid . "', '" . $uuid . "', '" . $time . "', '" . $reason . "')");
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    public static function get($data, $type) : ?array
    {
        $my = MySQL::getData();

        $gambler = mysqli_fetch_row($my->query("SELECT * FROM ban WHERE " . $type . " = '"  . $data . "'"));
        $my->close();
        return $gambler;

        /*
         * 1 = name
         * 2 = by_name
         * 3 = ip
         * 4 = cid
         * 5 = uuid
         * 6 = time
         * 7 = reason
         */
    }

    /**
     * @param string $id
     * @return bool
     */
    public static function delByID(string $id) : bool
    {
        $my = MySQL::getData();

        if (self::existsID($id)) {

            MySQL::sendDB("DELETE FROM ban WHERE id = " . $id);
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    public static function delByName(string $name) : bool
    {
        $my = MySQL::getData();

        if (self::existsName($name)) {

            MySQL::sendDB("DELETE FROM ban WHERE name = '" . strtolower($name) . "'");
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    public static function getBetween(int $min, int $max) : array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM ban WHERE id  BETWEEN ". $min ." AND " . $max);

        if ($res->num_rows > 0) {

            while($row = mysqli_fetch_assoc($res)) {

                //ip, cid, uuid, time, reason
                $all[$row["name"]] = array($row["ip"], $row["cid"], $row["uuid"], $row["time"], $row["reason"]);

            }

        }

        return $all;
    }

}