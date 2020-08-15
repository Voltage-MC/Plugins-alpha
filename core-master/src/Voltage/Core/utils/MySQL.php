<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 28/04/2019
 * Time: 10:46
 */

namespace Voltage\Core\utils;

use Voltage\Core\Core;
use Voltage\Core\task\async\DataBaseAsyncTask;

class MySQL
{
    const HOST = "p:127.0.0.1";
    const USER = "root";
    const PASSWORD = "----------";
    const BASE = "voltage";

    /**
     * @return \Exception|\MySQLi
     */
    public static function getData()
    {
        return new \MySQLi(self::HOST, self::USER, self::PASSWORD, self::BASE);
    }

    /**
     * @param string $text
     */
    public static function sendDB(string $text)
    {
        Core::getInstance()->getServer()->getAsyncPool()->submitTask(new DataBaseAsyncTask($text));
    }

    public static function addTables()
    {
        $my = MySQL::getData();

        //name , lang, rank, playtime, xp, connect
        $my->query("CREATE TABLE IF NOT EXISTS gambler (name TEXT ,lang VARCHAR(2) NOT NULL,rank TEXT NOT NULL,playtime INT NOT NULL,xp INT NOT NULL,connect TEXT NOT NULL)");
        $my->query("CREATE TABLE IF NOT EXISTS friend (name TEXT NOT NULL ,friend VARCHAR(9999) NOT NULL ,request VARCHAR(9999) NOT NULL)");
        $my->query("CREATE TABLE IF NOT EXISTS request (id INT NOT NULL, type VARCHAR(99) NOT NULL, info VARCHAR(99999) NOT NULL, temp INT NOT NULL, time INT NOT NULL, server INT NOT NULL)");
        $my->query("CREATE TABLE IF NOT EXISTS partys (id INT NOT NULL, owner TEXT NOT NULL, players VARCHAR(9999) NOT NULL)");
        $my->query("CREATE TABLE IF NOT EXISTS server (port INT NOT NULL, count INT, players VARCHAR(9999), state TEXT)");
        $my->query("CREATE TABLE IF NOT EXISTS economy (name TEXT, money INT, box_keys INT, credits INT)");
        $my->query("CREATE TABLE IF NOT EXISTS playerinfo (name TEXT, ip TEXT, cid TEXT, uuid TEXT, vpn TEXT)");
        $my->query("CREATE TABLE IF NOT EXISTS ban (id INT, name TEXT, by_name TEXT, ip TEXT, cid TEXT, uuid TEXT, time_sec TEXT, reason TEXT)");
        $my->query("CREATE TABLE IF NOT EXISTS hb (name TEXT, wins INT, losts INT)");
        $my->close();
    }

}