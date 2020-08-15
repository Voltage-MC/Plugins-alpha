<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 14:50
 */

namespace Voltage\Core\base;

use Voltage\Core\Core;
use Voltage\Core\utils\MySQL;

class Request
{
    /**
     * @return int
     */
    public static function generate() : int
    {
        $id = rand(0,9999);

        while (self::exists($id)) {

            $id = rand(0,9999);

        }

        return $id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public static function exists(int $id) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM request WHERE id = " . $id);
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param int $id
     * @return int
     */
    public static function getTemp(int $id) : int
    {
        $my = MySQL::getData();
        $result = $my->query("SELECT * FROM request WHERE id = " . $id);
        $data = mysqli_fetch_assoc($result);
        $temp = $data["temp"];
        return $temp;
    }

    public static function addTemp(int $id) : bool
    {
        $my = MySQL::getData();

        if(self::exists($id)) {

            $temp = (int) self::getTemp($id) + 1;
            MySQL::sendDB("UPDATE request SET temp = " . $temp . " WHERE id = " . $id);
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $type
     * @param array $info
     * @return bool
     */
    public static function add(string $type, array $info) : bool
    {
        $my = MySQL::getData();
        $id = self::generate();
        $time = time() + 5;
        $server = Core::getInstance()->getServer()->getPort();

        if (count($info) == 0) {

            $infos = "-";

        } else {

            $infos = implode(",", $info);

        }

        if (!self::exists($id)) {

            MySQL::sendDB("INSERT INTO request (id, type, info, temp, time, server) VALUES (" . $id . ", '". $type . "', '". $infos . "', ". 0 . ", " . $time . ", " . $server . ")");
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @param string $id
     * @return bool
     */
    public static function del(string $id) : bool
    {
        $my = MySQL::getData();

        if (self::exists($id)) {

            MySQL::sendDB("DELETE FROM request WHERE id = " . $id);
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    /**
     * @return array
     */
    public static function getAll() : array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM request");

        if ($res->num_rows > 0) {

            while($row = mysqli_fetch_assoc($res)) {

                //id, type, info, temp, time, server
                $all[$row["id"]] = array($row["type"],explode(",", $row["info"]),(int) $row["temp"], (int) $row["time"], (int) $row["server"]);

            }

        }

        return $all;
    }

}