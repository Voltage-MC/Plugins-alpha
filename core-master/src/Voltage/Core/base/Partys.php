<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 10/05/2019
 * Time: 08:33
 */

namespace Voltage\Core\base;

use Voltage\Core\utils\MySQL;

class Partys
{
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
     * @param string $owner
     * @return bool
     */
    public static function create(string $owner) : bool
    {
        $my = MySQL::getData();
        $id = self::generate();

        if (!self::exists($id)) {

            MySQL::sendDB("INSERT INTO partys (id, owner, players) VALUES (" . $id . " , '" . $owner . "' , '" . "-" . "')");
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }

    public static function addPlayer(string $owner, string $player)
    {
        $owner = strtolower($owner);
        $player = strtolower($player);

        $players = self::getFriendRequest($owner);
        $players[] = $player;

        self::setFriendRequest($owner, $players);
    }

}