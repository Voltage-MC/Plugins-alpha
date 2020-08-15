<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 03:04
 */

namespace Voltage\Core\base;

use Voltage\Core\utils\MySQL;
use Voltage\Core\utils\Network;
use Voltage\Core\VOLTPlayer;

class Server
{
    /**
     * @param $port
     * @return bool
     */
    public static function accountExists($port) : bool
    {
        $my = MySQL::getData();
        $result = $my->query("SELECT * FROM server WHERE port = " . $port);
        $my->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param int $port
     * @param int $count
     * @param VOLTPlayer[] $players
     */
    public static function setServer(int $port , int $count, array $players)
    {
        $play = [];

        foreach($players as $player){

            if($player->isOnline()){

                $play[] = strtolower($player->getName());

            }

        }

        $my = MySQL::getData();

        if (count($play) == 0) {

            $list = "-";

        } else {

            $list = implode(",", $play);

        }

        if(!self::accountExists($port)){

            MySQL::sendDB("INSERT INTO server (port, count, players, state) VALUES (". $port . ", ".$count.", '".$list."', 'online')");

        } else {

            MySQL::sendDB("UPDATE server SET count = ". $count . " WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET players = '". $list . "' WHERE port = " . $port);

        }

        $my->close();

    }

    /**
     * @param int $port
     * @return mixed
     */
    public static function getCount(int $port)
    {
        $my = MySQL::getData();
        $coins = mysqli_fetch_row($my->query("SELECT * FROM server WHERE port = "  . $port));
        $my->close();
        return $coins[1];
    }

    /**
     * @param int $port
     * @return mixed
     */
    public static function getPlayers(int $port)
    {
        $my = MySQL::getData();
        $coins = mysqli_fetch_row($my->query("SELECT * FROM server WHERE port = "  . $port));
        $my->close();
        return $coins[2];
    }

    /**
     * @param int $port
     */
    public static function stopServer(int $port)
    {
        $my = MySQL::getData();

        if(!self::accountExists($port)){

            MySQL::sendDB("INSERT INTO server (port, count, players) VALUES (". $port . ", 0, '-', 'stop')");

        } else {

            MySQL::sendDB("UPDATE server SET count = 0 WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET players = '-' WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET state = 'stop' WHERE port = " . $port);

        }

        $my->close();
    }

    /**
     * @param int $port
     */
    public static function startServer(int $port)
    {
        $my = MySQL::getData();

        if(!self::accountExists($port)){

            MySQL::sendDB("INSERT INTO server (port, count, players) VALUES (". $port . ", 0, '-', 'online')");

        } else {

            MySQL::sendDB("UPDATE server SET count = 0 WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET players = '-' WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET state = 'online' WHERE port = " . $port);

        }

        $my->close();
    }

    public static function restartServer(int $port)
    {
        $my = MySQL::getData();

        if(!self::accountExists($port)){

            MySQL::sendDB("INSERT INTO server (port, count, players) VALUES (". $port . ", 0, '-', 'restart')");

        } else {

            MySQL::sendDB("UPDATE server SET count = 0 WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET players = '-' WHERE port = " . $port);
            MySQL::sendDB("UPDATE server SET state = 'restart' WHERE port = " . $port);

        }

        $my->close();
    }

    /**
     * @param int $port
     */
    public static function ingameServer(int $port)
    {
        $my = MySQL::getData();

        if(!self::accountExists($port)){

            MySQL::sendDB("INSERT INTO server (port, count, players) VALUES (". $port . ", 0, '-', 'ingame')");

        } else {

            MySQL::sendDB("UPDATE server SET state = 'ingame' WHERE port = " . $port);

        }

        $my->close();
    }

    /**
     * @param int $port
     * @return string
     */
    public static function getOnlineServer(int $port) : string
    {
        $my = MySQL::getData();
        $player = mysqli_fetch_row($my->query("SELECT * FROM server WHERE port = "  . $port));
        $my->close();

        switch ($player[3]) {

            case "restart": return "§6§lRestart";
            case "stop": return "§c§lOffline";
            case "ingame": return "§c§lInGame";
            case "online": return "§a§lOnline";

        }

        return "EROR";
    }

    /**
     * @return string[]
     */
    public static function getAllNetwork() : array
    {
        $all = [];
        $my = MySQL::getData();

        $res = $my->query("SELECT * FROM server");
        $my->close();

        if ($res->num_rows > 0) {

            while($row = mysqli_fetch_assoc($res)) {

                foreach (explode(",", $row["players"]) as $name) {

                    $all[] = $name;

                }

            }

        }

        return $all;
    }

    /**
     * @return int
     */
    public static function getNetworkCount() : int
    {
        $all = 0;

        foreach (Network::NAME as $name => $port) {

            $all += self::getCount($port);

        }

        return $all;
    }

}