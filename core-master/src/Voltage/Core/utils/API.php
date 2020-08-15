<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 01/05/2019
 * Time: 18:04
 */

namespace Voltage\Core\utils;

use pocketmine\math\Vector3;

class API
{

    /**
     * @return string
     */
    public static function getCountry(string $ip) : string
    {
        $query = @unserialize(file_get_contents("http://ip-api.com/php/". $ip));

        if ($query["status"] === "success") {

            $cc = strtolower($query["countryCode"]);

            if (in_array($cc, array("en","us"))) {

                return "en";

            } else if (in_array($cc, array("fr","be","lu","ca"))) {

                return "fr";

            } else if (in_array($cc, array("es","br","me"))) {

                return "es";

            } else if (in_array($cc, array("de"))) {

                return "de";

            }

        }

        return "en";
    }

    /**
     * @param string $ip
     * @return string
     */
    public static function getProxy(string $ip) : string
    {
        $resp = file_get_contents('http://proxycheck.io/v2/'.$ip . '?key=111111-222222-333333-444444&vpn=1', FILE_TEXT);
        $details = json_decode($resp);

        if (!isset($details->$ip->proxy)) return "false";

        if ($details->$ip->proxy === "no") {

           return "false";

        } else {

            return "true";

        }

    }

    /**
     * @return int
     */
    public static function getMicroTime() : int
    {
        $mt = explode(' ', microtime()) ;
        $mt = ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
        return $mt;
    }

    public static function getSurroundingArea(Vector3 $pos1, Vector3 $pos2, int $radius) : bool
    {
        $result = false;

        $minX = self::getMinMax($pos1->getX() - $radius ,$pos1->getX() + $radius)[0];
        $maxX = self::getMinMax($pos1->getX() - $radius ,$pos1->getX() + $radius)[1];

        $minY = self::getMinMax($pos1->getY() - $radius ,$pos1->getY() + $radius)[0];
        $maxY = self::getMinMax($pos1->getY() - $radius ,$pos1->getY() + $radius)[1];

        $minZ = self::getMinMax($pos1->getZ() - $radius ,$pos1->getZ() + $radius)[0];
        $maxZ = self::getMinMax($pos1->getZ() - $radius ,$pos1->getZ() + $radius)[1];
        
        $x = $pos2->getX();
        $y = $pos2->getY();
        $z = $pos2->getZ();

        if ($x > $minX and $x < $maxX) {

            if ($y > $minY and $y < $maxY) {

                if ($z > $minZ and $z < $maxZ) {

                    $result = true;

                }

            }

        }

        return $result;
    }

    /**
     * @param $x1
     * @param $x2
     * @return array
     */
    public static function getMinMax($x1,$x2) : array
    {
        if ($x1 < $x2) {
            return array($x1,$x2);
        } else {
            return array($x2,$x1);
        }
    }

    public static function getTimeFormat(int $sec) : string
    {
        $day = floor($sec / 86400);

        $hours = $sec % 86400;

        $hour = floor($hours / 3600);

        $minutes = $hours % 3600;

        $minute = floor($minutes / 60);

        $remainings = $minutes % 60;

        $second = ceil($remainings);

        return $day . " Day " . $hour . " Hours " . $minute . " Minutes " . $second . " Second";
    }
}