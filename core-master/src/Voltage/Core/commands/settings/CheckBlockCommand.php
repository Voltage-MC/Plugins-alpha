<?php
/**
 * Created by PhpStorm.
 * User: Walid
 * Date: 11/29/2018
 * Time: 3:03 PM
 */

namespace Voltage\Core\commands\settings;

use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Banner;
use pocketmine\tile\Bed;
use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class CheckBlockCommand extends Command
{
    private static $blocks = [
        158 => [Block::WOODEN_SLAB, 0],
        125 => [Block::DOUBLE_WOODEN_SLAB, ""],
        188 => [Block::FENCE, 0],
        189 => [Block::FENCE, 1],
        190 => [Block::FENCE, 2],
        191 => [Block::FENCE, 3],
        192 => [Block::FENCE, 4],
        193 => [Block::FENCE, 5],
        166 => [Block::INVISIBLE_BEDROCK, 0],
        208 => [Block::GRASS_PATH, 0],
        198 => [Block::END_ROD, 0],
        126 => [Block::WOODEN_SLAB, ""],
        95 => [Block::STAINED_GLASS, ""],
        199 => [Block::CHORUS_PLANT, 0],
        202 => [Block::PURPUR_BLOCK, 0],
        251 => [Block::CONCRETE, 0],
        204 => [Block::PURPUR_BLOCK, 0],
        Block::MOB_HEAD_BLOCK => [Block::AIR, 0]
    ];

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct('check', 'Check');
        $this->setPermission("owner");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof VOLTPlayer) {

            if (
                $sender->isOp()
                or
                $sender->hasPermission("owner")
            ) {

                $sender->sendMessage(TextFormat::GREEN . "Converting all blocks into PE please wait sorry for the lagg....");
                $this->fix($sender->getFloorX() - 30, $sender->getFloorY() - 30, $sender->getFloorZ() - 30, $sender->getFloorX() + 30, $sender->getFloorY() + 30, $sender->getFloorZ() + 30, $sender->getLevel(), $sender);

            }

        }

    }

    public function fix($x1, $y1, $z1, $x2, $y2, $z2, Level $level, Player $player)
    {
        $blocks = self::$blocks;

        $count = 0;

        for ($x = min($x1, $x2); $x <= max($x1, $x2); $x++) {

            for ($y = min($y1, $y2); $y <= max($y1, $y2); $y++) {

                for ($z = min($z1, $z2); $z <= max($z1, $z2); $z++) {

                    $id = $level->getBlock(new Vector3($x, $y, $z))->getId();
                    $d = $level->getBlock(new Vector3($x, $y, $z))->getDamage();

                    if (isset($blocks[$id])) {

                        $level->setBlockIdAt($x, $y, $z, $blocks[$id][0]);

                        if (is_int($blocks[$id][1])) $level->setBlockDataAt($x, $y, $z, $blocks[$id][1]);

                        $count++;
                    }

                    if (isset($blocks[$id . ":" . $d])) {

                        $level->setBlockIdAt($x, $y, $z, $blocks[$id . ":" . $d][0]);

                        if (is_int($blocks[$id . ":" . $d][1])) $level->setBlockDataAt($x, $y, $z, $blocks[$id . ":" . $d][1]);

                        $count++;

                    }

                    switch ($id) {

                        case Block::CHEST:

                            if ($level->getTile(new Vector3($x, $y, $z)) === null)

                                $level->addTile(new Chest($level, Chest::createNBT(new Vector3($x, $y, $z))));

                            break;

                        case Block::SIGN_POST:
                        case Block::WALL_SIGN:

                            if ($level->getTile(new Vector3($x, $y, $z)) === null)

                                $level->addTile(new Sign($level, Sign::createNBT(new Vector3($x, $y, $z))));

                            break;

                        case Block::BED_BLOCK:

                            if ($level->getTile(new Vector3($x, $y, $z)) === null)

                                $level->addTile(new Bed($level, Bed::createNBT(new Vector3($x, $y, $z))));

                            break;

                        case Block::STANDING_BANNER:
                        case Block::WALL_BANNER:

                            if ($level->getTile(new Vector3($x, $y, $z)) === null)

                                $level->addTile(new Banner($level, Banner::createNBT(new Vector3($x, $y, $z))));

                            break;

                    }

                }

            }

        }

        $player->sendMessage("Selected area successfully fixed! ($count blocks changed!)");
    }

}