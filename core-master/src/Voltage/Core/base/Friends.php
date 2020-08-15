<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 01:53
 */

namespace Voltage\Core\base;

use Voltage\Core\utils\MySQL;
use Voltage\Core\VOLTPlayer;

class Friends
{
    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name) : bool
    {
        $my = MySQL::getData();

        $result = $my->query("SELECT * FROM friend WHERE name = '" . strtolower($name) . "'");
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
            MySQL::sendDB("INSERT INTO friend (name , friend, request) VALUES ('". strtolower($name) . "', '-', '-')");
            $my->close();
            return true;

        }

        $my->close();
        return false;
    }
    /**
     * @param string $player
     * @param string $friend
     */
    public static function addFriend(string $player, string $friend)
    {
        $player = strtolower($player);
        $friend = strtolower($friend);

        $players = self::getFriend($player);
        $players[] = $player;

        $friends = self::getFriend($friend);
        $friends[] = $friend;

        self::setFriend($friend, $players);
        self::setFriend($player, $friends);
    }

    /**
     * @param string $name
     * @param array $array
     */
    public static function setFriend(string $name, array $array)
    {
        $name = strtolower($name);
        $my = MySQL::getData();

        if (count($array) == 0) {

            $friend = "-";

        } else {

            $friend = implode(",", $array);

        }

        $my->query("UPDATE friend SET friend = '" . $friend . "' WHERE name = '" . $name . "'");
        $my->close();

    }

    /**
     * @param string $player
     * @param string $friend
     */
    public static function delFriend(string $player, string $friend)
    {
        $player = strtolower($player);
        $friend = strtolower($friend);

        $players = self::getFriend($player);
        $friends = self::getFriend($friend);

        unset($players[array_search($friend, $players)]);
        unset($friends[array_search($player, $friends)]);

        self::setFriend($player, $players);
        self::setFriend($friend, $friends);
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getFriend(string $name)
    {
        $my = MySQL::getData();
        $result = $my->query("SELECT * FROM friend WHERE name = '" . strtolower($name) . "'");
        $data = mysqli_fetch_assoc($result);
        $list = $data["friend"];

        if ($list == "-") {

            $list = [];

        } else {

            $list = explode(",", $list);

        }

        return $list;
    }

    /**
     * @param string $player
     * @param string $friend
     * @return bool
     */
    public static function isFriend(string $player, string $friend) : bool
    {
        $player = strtolower($player);
        $friend = strtolower($friend);

        $my = MySQL::getData();
        $result = mysqli_fetch_row($my->query("SELECT * FROM friend WHERE name = '" . $player . "'"));
        $my->close();
        $list = $result[1];

        if ($list == "-") {

            $list = [];

        } else {

            $list = explode(",", $list);

        }

        if (in_array($friend, $list)){

            return true;

        } else {

            return false;

        }

    }

    /**
     * @param string $player
     * @param string $friend
     */
    public static function addFriendRequest(string $player, string $friend)
    {
        $player = strtolower($player);
        $friend = strtolower($friend);

        $friends = self::getFriendRequest($player);
        $friends[] = $friend;

        self::setFriendRequest($player, $friends);
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getFriendRequest(string $name)
    {
        $my = MySQL::getData();
        $result = $my->query("SELECT * FROM friend WHERE name = '" . strtolower($name) . "'");
        $data = mysqli_fetch_assoc($result);
        $list = $data["request"];

        if ($list == "-") {

            $list = [];

        } else {

            $list = explode(",", $list);

        }

        return $list;
    }

    /**
     * @param string $name
     * @param array $array
     */
    public static function setFriendRequest(string $name, array $array)
    {
        $name = strtolower($name);
        $my = MySQL::getData();

        if (count($array) == 0) {

            $friend = "-";

        } else {

            $friend = implode(",", $array);

        }

        $my->query("UPDATE friend SET request = '" . $friend . "' WHERE name = '" . $name . "'");
        $my->close();

    }

    /**
     * @param string $player
     * @param string $friend
     */
    public static function delFriendRequest(string $player, string $friend)
    {
        $player = strtolower($player);
        $friend = strtolower($friend);

        $players = self::getFriendRequest($player);
        $friends = self::getFriendRequest($friend);

        unset($players[array_search($friend, $players)]);
        unset($friends[array_search($player, $friends)]);

        self::setFriendRequest($player, $players);
        self::setFriendRequest($friend, $friends);
    }

    /**
     * @param string $player
     * @param string $friend
     * @return bool
     */
    public static function isFriendRequest(string $player, string $friend) : bool
    {
        $player = strtolower($player);
        $friend = strtolower($friend);

        $my = MySQL::getData();
        $result = mysqli_fetch_row($my->query("SELECT * FROM friend WHERE name = '" . $player . "'"));
        $my->close();
        $list = $result[2];

        if ($list == "-") {

            $list = [];

        } else {

            $list = explode(",", $list);

        }

        if (in_array($friend, $list)){

            return true;

        } else {

            return false;

        }

    }

    /**
     * @param string $name
     * @return array
     */
    public static function count(string $name) : array
    {
        $online = 0;
        $offline = 0;

        $friends = self::getFriend($name);

        if ($friends !== array("")) {

            foreach ($friends as $friend) {

                if (Gambler::getConnect($friend) === "offline") {

                    $offline++;

                } else {

                    $online++;

                }

            }

        }

        return array($online,$offline);
    }

    public static function maxFriend(int $rank)
    {
        switch ($rank) {

            case VOLTPlayer::RANK_PLAYER:
            case VOLTPlayer::RANK_TRAINEE:
                return 5;

            case VOLTPlayer::RANK_VIP: return 10;
            case VOLTPlayer::RANK_VIP_PLUS: return 25;
            case VOLTPlayer::RANK_VOLT: return 40;

            case VOLTPlayer::RANK_FAMOUS:
            case VOLTPlayer::RANK_PARTNER:
                return 50;

            default: return 70;

        }

    }

}