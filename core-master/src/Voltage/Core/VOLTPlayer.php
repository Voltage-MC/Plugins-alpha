<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 27/04/2019
 * Time: 03:08
 */

namespace Voltage\Core;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use Voltage\Core\base\Economy;
use Voltage\Core\base\Friends;
use Voltage\Core\utils\Level;
use Voltage\Core\utils\Network;
use pocketmine\entity\Attribute;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\network\SourceInterface;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TE;
use Voltage\Core\base\Gambler;
use Voltage\Core\lang\English;

class VOLTPlayer extends Player
{
    private $interact;

    const RANK_PLAYER = 0;
    const RANK_FAMOUS = 1;
    const RANK_VIP = 2;
    const RANK_VIP_PLUS = 3;
    const RANK_VOLT = 4;
    const RANK_PARTNER = 5;
    const RANK_TRAINEE= 6;
    const RANK_MOD = 7;
    const RANK_ADMIN = 8;
    const RANK_OWNER= 9;

    const RANK =
        [
            VOLTPlayer::RANK_PLAYER => TE::BOLD . TE::GRAY . "Player",
            VOLTPlayer::RANK_FAMOUS => TE::BOLD . TE::RED . "Famous",
            VOLTPlayer::RANK_VIP => TE::BOLD . TE::GREEN . "vip",
            VOLTPlayer::RANK_VIP_PLUS => TE::BOLD . TE::GOLD . "vip+",
            VOLTPlayer::RANK_VOLT => TE::BOLD . TE::BLUE . "Volt" ,
            VOLTPlayer::RANK_PARTNER => TE::BOLD . TE::RED . "Partner",
            VOLTPlayer::RANK_TRAINEE => TE::BOLD . TE::DARK_GREEN . "Trainee",
            VOLTPlayer::RANK_MOD => TE::BOLD . TE::DARK_AQUA . "Moderator",
            VOLTPlayer::RANK_ADMIN => TE::BOLD . TE::DARK_RED . "Administrator",
            VOLTPlayer::RANK_OWNER => TE::BOLD . TE::LIGHT_PURPLE . "Owner"
        ];

    const PERMISSION_TRAINEE = ["trainee"];
    const PERMISSION_MOD = ["trainee", "mod"];
    const PERMISSION_ADMIN = ["trainee", "mod", "admin"];
    const PERMISSION_OWNER = ["trainee", "mod", "admin", "owner"];

    /**
     * @param SourceInterface $interface
     * @param string $ip
     * @param int $port
     */
    public function __construct(SourceInterface $interface, $ip, $port)
    {
        parent::__construct($interface, $ip, $port);
    }

    public function setDefaultData()
    {
        if (!Gambler::setDefaultData($this)) new \Exception("Error when recording the data of " . $this->getName());
    }

    /**
     * @return string
     */
    public function getLang() : string
    {
        return Gambler::getLang($this->getName());
    }

    /**
     * @param string $lang
     */
    public function setLang(string $lang)
    {
        if (!Gambler::setLang($this->getName(),$lang)) new \Exception("Error when recording the language of " . $this->getName());
    }

    /**
     * @return int
     */
    public function getRank() : int
    {
        return Gambler::getRank($this->getName());
    }

    /**
     * @param int $rank
     * @return string
     */
    public function getPrefix(int $rank) : string
    {
        return self::RANK[$rank];
    }

    /**
     * @param int $rank
     */
    public function setRank(int $rank) : void
    {
        if (!Gambler::setRank($this->getName(),$rank)) new \Exception("Error when registering the rank of " . $this->getName());
    }

    /**
     * @return int
     */
    public function getXP() : int
    {
        return Gambler::getXps($this->getName());
    }

    public function addXPWithLevel(int $amount)
    {
        Gambler::addXps($this->getName(),$amount);
    }

    /**
     * @return int
     */
    public function getLevelWithXP() : int
    {
        return Level::get($this->getXP())[0];
    }

    /**
     * @param string $lang
     * @return English
     */
    private function translates(string $lang)
    {
        if ("en" === $lang) {

            return new English();

        } else if ("fr" === $lang) {

            //return new Francais();

        } else if ("es" === $lang) {

            //return new Espanol();

        } else if ("de" === $lang) {

            //return new Deutch();

        }

        return new English();
    }

    /**
     * @param string $msg
     * @param array $args
     * @return string
     */
    private function translateMSG(string $msg ,array $args) : string
    {
        if (is_array($args)) {

            foreach ($args as $arg) {

                $msg = preg_replace("/[%]/", $arg, $msg,1);

            }

        }

        return $msg;
    }

    /**
     * @param string $message
     * @param array $args
     * @return string
     */
    public function messageToTranslate(string $message, array $args = array()) : string
    {
        if (isset($this->translates($this->getLang())->translates[$message])) {

            $msg = $this->translates($this->getLang())->translates[$message];

        } else {

            return $this->translates($this->getLang())->translates["ERROR"] . $message;

        }

        $msg = $this->translateMSG($msg , $args);
        return $msg;
    }

    /**
     * @return bool
     */
    public function isStaff() : bool
    {
        if ($this->getRank() >= 6 and $this->getRank() <= 9) return true;
        return false;
    }

    /**
     * @return bool
     */
    public function interact() : bool
    {
        $time = floatval(microtime(true));
        if (!is_null($this->interact)) {

            if ($time - $this->interact <= 0.1) {

                $this->interact = $time;
                return false;

            }

        }

        $this->interact = $time;
        return true;
    }

    /**
     * @param string $text
     * @param int $pourcentage
     */
    public function addBossBar(string $text, int $pourcentage)
    {
        $pk = new AddEntityPacket();
        $pk->entityRuntimeId = 7;
        $pk->type = 52;
        $pk->position = $this->asVector3();
        $pk->metadata =
            [
                Entity::DATA_LEAD_HOLDER_EID =>
                    [
                        Entity::DATA_TYPE_LONG,
                        -1
                    ],
                Entity::DATA_FLAGS =>
                    [
                        Entity::DATA_TYPE_LONG,
                        0 ^ 1 << Entity::DATA_FLAG_SILENT ^ 1 << Entity::DATA_FLAG_INVISIBLE ^ 1 << Entity::DATA_FLAG_NO_AI
                    ],
                Entity::DATA_SCALE =>
                    [
                        Entity::DATA_TYPE_FLOAT, 0
                    ],
                Entity::DATA_NAMETAG =>
                    [
                        Entity::DATA_TYPE_STRING,
                        $text
                    ],
                Entity::DATA_BOUNDING_BOX_WIDTH =>
                    [
                        Entity::DATA_TYPE_FLOAT,
                        0
                    ],
                Entity::DATA_BOUNDING_BOX_HEIGHT =>
                    [
                        Entity::DATA_TYPE_FLOAT,
                        0
                    ]
            ];
        $this->dataPacket($pk);

        $bpk = new BossEventPacket();
        $bpk->bossEid = $this->getId();
        $bpk->eventType = BossEventPacket::TYPE_SHOW;
        $bpk->title = $text;
        $bpk->overlay = 0;
        $bpk->color = 0;
        $bpk->healthPercent = $pourcentage / 100;
        $this->dataPacket($bpk);

        //POURCENTAGE

        $upk = new UpdateAttributesPacket();
        $upk->entityRuntimeId = $this->getId();
        $attribute = Attribute::getAttribute(Attribute::HEALTH);
        $pourcentage = round($pourcentage * $attribute->getMaxValue() / 100);
        $attribute->setValue($pourcentage);
        $upk->entries[] = $attribute;
        $this->dataPacket($upk);

    }

    /**
     * @param array $array
     */
    public function addScoreBoard(array $array)
    {
        $this->removeScoreBoard();

        $n = count($array);
        $all = 15;
        $title = TE::LIGHT_PURPLE . TE::BOLD . "Voltage";
        $i = $all - strlen(TE::clean($title));

        $spk = new SetDisplayObjectivePacket();
        $spk->displayName = str_repeat(" ", round($i / 2)) . $title . str_repeat(" ", round($i / 2));
        $spk->objectiveName = "" . $this->getId() . "";
        $spk->displaySlot = 'sidebar';
        $spk->criteriaName = 'dummy';
        $spk->sortOrder = 1;

        $this->sendDataPacket($spk);

        foreach ($array as $text) {

            $entry = new ScorePacketEntry();
            $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
            $entry->score = $n;
            $entry->scoreboardId = $n;
            $entry->objectiveName = "" . $this->getId() . "";
            $entry->customName = $text;

            $pk = new SetScorePacket();
            $pk->type = SetScorePacket::TYPE_CHANGE;
            $pk->entries[] = $entry;

            $this->sendDataPacket($pk);

            $n--;

        }

    }

    public function removeScoreBoard()
    {
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = "" . $this->getId() . "";
        $this->dataPacket($pk);
    }

    /**
     * @param string $friend
     * @return bool
     */
    public function isFriend(string $friend) : bool
    {
        return Friends::isFriend($this->getName(), $friend);
    }

    /**
     * @param string $friend
     */
    public function removeFriend(string $friend)
    {
        Friends::delFriend($this->getName(), $friend);
    }

    /**
     * @param string $friend
     */
    public function addFriend(string $friend)
    {
        Friends::addFriend($this->getName(), $friend);
    }

    /**
     * @return array
     */
    public function getFriends() : array
    {
        return Friends::getFriend($this->getName());
    }

    /**
     * @return array
     */
    public function getFriendRequest() : array
    {
        return Friends::getFriendRequest($this->getName());
    }

    /**
     * @param string $friend
     * @return bool
     */
    public function isFriendRequest(string $friend) : bool
    {
        return Friends::isFriendRequest($this->getName(), $friend);
    }

    /**
     * @param string $friend
     */
    public function removeFriendRequest(string $friend)
    {
        Friends::delFriendRequest($this->getName(), $friend);
    }

    /**
     * @param string $friend
     */
    public function addFriendRequest(string $friend)
    {
        Friends::addFriendRequest($this->getName(), $friend);
    }

    /**
     * @param int $amount
     */
    public function addMoney(int $amount)
    {
        Economy::addMoney($this->getName(), $amount);
    }

    /**
     * @param int $amount
     */
    public function addKeys(int $amount)
    {
        Economy::addKeys($this->getName(), $amount);
    }

    /**
     * @param int $amount
     */
    public function addCredits(int $amount)
    {
        Economy::addCredits($this->getName(), $amount);
    }

    /**
     * @return int
     */
    public function getMoney() : int
    {
        return Economy::getMoney($this->getName());
    }

    /**
     * @return int
     */
    public function getKeys() : int
    {
        return Economy::getKeys($this->getName());
    }

    /**
     * @return int
     */
    public function getCredits() : int
    {
        return Economy::getCredits($this->getName());
    }

    /**
     * @param int $amount
     */
    public function setMoney(int $amount)
    {
         Economy::setMoney($this->getName(), $amount);
    }

    /**
     * @param int $amount
     */
    public function setKeys(int $amount)
    {
        Economy::setKeys($this->getName(), $amount);
    }

    /**
     * @param int $amount
     * @return bool
     */
    public function setCredits(int $amount)
    {
        return Economy::setCredits($this->getName(), $amount);
    }

}