<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 04/05/2019
 * Time: 20:38
 */

namespace Voltage\Core\utils;

class Level
{
    public $levels =
        [

        ];

    /**
     * @param $xps
     * @return array
     * Level , MinXP, MaxXP
     */
    public static function get($xps) : array
    {
        if ($xps <= 10) {

            return array(0 , 0 , 10); //+10

        } else if ($xps <= 20) {

            return array(1 , 10 , 20);//+20

        } else if ($xps <= 40) {

            return array(2 , 40 , 70);//+30

        } else if ($xps <= 70) {

            return array(3 , 70 , 110);//+40

        } else if ($xps <= 110) {

            return array(4 , 110 , 160);//+50

        } else if ($xps <= 160) {

            return array(5 , 160 , 220);//+60

        } else if ($xps <= 220) {

            return array(6 , 220 , 290);//+70

        } else if ($xps <= 290) {

            return array(7 , 290 , 370);//+80

        } else if ($xps <= 370) {

            return array(8 , 370 , 460);//+90

        } else if ($xps <= 460) {

            return array(9 , 460 , 560);//+100

        } else if ($xps <= 560) {

            return array(10 , 560 , 670);//+110

        } else if ($xps <= 670) {

            return array(11 , 670 , 790);//+120

        } else if ($xps <= 790) {

            return array(12 , 790 , 920);//+130

        } else if ($xps <= 920) {

            return array(13 , 920 , 1060);//+140

        } else if ($xps <= 1060) {

            return array(14 , 1060 , 1210);//+150

        } else if ($xps <= 1210) {

            return array(15 , 1210 , 1370);//+160

        } else if ($xps <= 1370) {

            return array(16 , 1370 , 1540);//+170

        } else if ($xps <= 1540) {

            return array(17 , 1540 , 1720);//+180

        } else if ($xps <= 1720) {

            return array(18 , 1720 , 1910);//+190

        } else if ($xps <= 1910) {

            return array(19 , 1910 , 2110);//+200

        }  else if ($xps <= 2110) {

            return array(20 , 2110 , 2320);//+210

        } else {

            return array(0 , 0 , 0);
        }

    }
}