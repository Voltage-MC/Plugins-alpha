<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 19/05/2019
 * Time: 02:53
 */

namespace Voltage\Core\resources;

use Voltage\Core\Core;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\BinaryStream;

class LoadResources
{
    const NAME =
        [
            "hub"
        ];

    public static function getSize($bytes) : ?array
    {
        switch(strlen($bytes)){

            case 64*64*4:
                $l = 64;
                $L = 64;
                return [$l, $L];
            case 64*32*4:
                $l = 64;
                $L = 32;
                return [$l, $L];
            case 128*128*4:
                $l = 128;
                $L = 128;
                return [$l, $L];
            default :
                return null;

        }

    }

    public static function getHeadBYTEStoIMG($skin)
    {
        $img = LoadResources::BYTEStoIMG($skin);
        $L = (int) self::getSize($skin)[0] / 8;
        $l = (int) self::getSize($skin)[1] / 8;

        $head = @imagecrop($img,['x' => $L, 'y' => $l, 'width' => $L, 'height' => $l]);
        return $head;
    }

    /**
     * @param string $image
     * @return string
     */
    public static function PNGtoBYTES(string $image) : string
    {
        $path = Core::getResourcesPath() . "/image/" . $image .".png";
        $img = @imagecreatefrompng($path);
        $bytes = "";

        $L = (int) @getimagesize($path)[0];
        $l = (int) @getimagesize($path)[1];

        for ($y = 0; $y < $l; $y++) {

            for ($x = 0; $x < $L; $x++) {

                $rgba = @imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);

            }

        }

        @imagedestroy($img);

        return $bytes;
    }

    public static function BYTEStoIMG(string $bytes)
    {
        $size = self::getSize($bytes);
        $l = $size[0];
        $L = $size[1];

        $img = imagecreatetruecolor($l, $L);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $stream = new BinaryStream($bytes);

        for ($y = 0; $y < $l; $y++) {

            for ($x = 0; $x < $L; $x++) {

                $r = $stream->getByte();
                $g = $stream->getByte();
                $b = $stream->getByte();
                $a = 127 - (int) floor($stream->getByte() / 2);
                $colour = @imagecolorallocatealpha($img, $r, $g, $b, $a);
                @imagesetpixel($img, $x, $y, $colour);

            }

        }

        return $img;
    }

    /**
     * @return string
     */
    public static function getGeometry() : string
    {
        $geometry = file_get_contents(Core::getResourcesPath() . "geometry.json");
        return $geometry;
    }

    public static function overlayingSkin(string $image, $img)
    {
        $L = (int) @getimagesize($img)[0];
        $l = (int) @getimagesize($img)[1];

        $image = self::getSkinNameWhitSize($l, $L, $image);
        $path = Core::getResourcesPath() . $image .".png";

        $skin = @imagecreatefrompng($path);

        $bytes = "";

        for ($y = 0; $y < $l; $y++) {

            for ($x = 0; $x < $L; $x++) {

                $rgba = @imagecolorat($skin, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;

                if ($g === 255 and $a === 254 and $r === 0 and $b === 0) { //IDK for 254

                    $rgba = @imagecolorat($img, $x, $y);
                    $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                    $r = ($rgba >> 16) & 0xff;
                    $g = ($rgba >> 8) & 0xff;
                    $b = $rgba & 0xff;

                }

                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);

            }

        }

        @imagedestroy($skin);
        @imagedestroy($img);

        return $bytes;
    }

    public static function getSkinTag() : NamedTag
    {
        $skin =
            new CompoundTag
            (
                "Skin",
                [
                    "Data" => new StringTag("Data", LoadResources::PNGtoBYTES("zeus")),
                    "Name" => new StringTag("Name", "SkinTag")
                ]
            );

        return $skin;
    }

    public static function getSkinNameWhitSize(int $l, int $L, string $img) : string
    {
        return $img . $l . "x" . $L;
    }

}