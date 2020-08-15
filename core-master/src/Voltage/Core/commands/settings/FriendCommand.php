<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 20:04
 */

namespace Voltage\Core\commands\settings;

use Voltage\Core\base\Friends;
use Voltage\Core\base\Gambler;
use Voltage\Core\base\Request;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\utils\Network;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use Voltage\Core\Core;
use Voltage\Core\VOLTPlayer;
use Voltage\Core\task\FriendRemoveDelayTask;

class FriendCommand extends Command
{
    private $friends = [];

    /**
     * LangCommand constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'friend',
            'Get friend',
            '/friend <add|del|list|accept|decline>',
        );
    }

    /**
     * @param CommandSender $sender
     * @param string $label
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $label, array $args) : bool
    {
        $args = array_map('strtolower', $args);
        
        if (Core::getInstance()->isEnabled()) {

            if ($sender instanceof VOLTPlayer) {

                if (!empty($args[0])) {

                    if ($args[0] === "add") {

                        if (!empty($args[1])) {

                            $name = !is_null(Network::getPlayer($args[1]))
                                ?
                                Network::getPlayer($args[1])
                                :
                                null;

                            if (is_null($name)) {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PLAYER_NOT_FOUND"));
                                return true;
                            }

                            if (Friends::count($sender->getName())[0] + Friends::count($sender->getName())[1] < Friends::maxFriend($sender->getRank())) {

                                if (Friends::count($name)[0] + Friends::count($name)[1] < Friends::maxFriend(Gambler::getRank($name))) {

                                    if ($name !== strtolower($sender->getName())) {

                                        if (!$sender->isFriend($name)) {

                                            if (!Friends::isFriendRequest($name, $sender->getName())) {

                                                $sender->sendMessage($sender->messageToTranslate("FRIEND_ADD_SEND", array($name)));
                                                Request::add("FRIEND_REQUEST_ADD", array($sender->getName(), $name));
                                                new FriendRemoveDelayTask($sender->getName(), $name);

                                            } else {

                                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_REQUEST_ALREADY"));

                                            }

                                        } else {

                                            $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_ALREADY"));

                                        }

                                    } else {

                                        $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_NOT_YOU"));

                                    }

                                } else {

                                    //TODO

                                }

                            } else {

                                //TODO

                            }
                            return true;

                        }

                    } else if ($args[0] === "del") {

                        if (!empty($args[1])) {

                            $name = !is_null(Network::getAllPlayer($args[1]))
                                ?
                                Network::getAllPlayer($args[1])
                                :
                                null;

                            if (is_null($name)) {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PLAYER_NOT_FOUND"));
                                return true;
                            }

                            if ($sender->isFriend($name)) {

                                $sender->removeFriend($name);
                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_REMOVE_SEND", array($name)));
                                Request::add("FRIEND_REMOVE_PLAYER", array($sender->getName(),$name));

                            } else {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_REMOVE_RECEIVED"));

                            }
                            return true;

                        }

                    } else if ($args[0] === "accept") {

                        if (!empty($args[1])) {

                            $name = !is_null(Network::getPlayer($args[1]))
                                ?
                                Network::getPlayer($args[1])
                                :
                                null;

                            if (is_null($name)) {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PLAYER_NOT_FOUND"));
                                return true;
                            }

                            if (Friends::count($sender->getName())[0] + Friends::count($sender->getName())[1] < Friends::maxFriend($sender->getRank())) {

                                if (Friends::count($name)[0] + Friends::count($name)[1] < Friends::maxFriend(Gambler::getRank($name))) {

                                    if (Friends::isFriendRequest($sender->getName(), $name)) {

                                        $sender->removeFriendRequest($sender->getName());
                                        $sender->addFriend($name);
                                        $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_REQUEST_ACCEPT_SEND"));
                                        Request::add("FRIEND_REQUEST_ACCEPT", array($sender->getName(),$name));

                                    } else {

                                        //TODO

                                    }
                                    return true;

                                }

                            } else {

                                //TODO

                            }

                        } else {

                            //TODO

                        }

                    } else if ($args[0] === "decline") {

                        if (!empty($args[1])) {

                            $name = !is_null(Network::getPlayer($args[1]))
                                ?
                                Network::getPlayer($args[1])
                                :
                                null;

                            if (is_null($name)) {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PLAYER_NOT_FOUND"));
                                return true;
                            }

                            if ($sender->isFriendRequest($name)) {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_REQUEST_DECLINE_SEND"));
                                Request::add("FRIEND_REQUEST_DECLINE", array($sender->getName(),$name));
                                $sender->removeFriendRequest($name);

                            } else {

                                //TODO

                            }
                            return true;

                        }

                    } else if ($args[0] === "list") {

                        $name = !empty($args[1]) ? null : strtolower($sender->getName());

                        if (is_null($name)) {

                            if (!is_null(Network::getPlayer($args[1]))) {

                                $name = Network::getPlayer($args[1]);

                            }

                            if (is_null($name)) {

                                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("PLAYER_NOT_FOUND"));
                                return true;

                            }

                        }
                        $this->formList($sender, $name);
                        return true;

                    } else if ($args[0] === "join") {

                        //TODO
                        return true;

                    }

                }


                $sender->sendMessage(Core::getPrefix() . $sender->messageToTranslate("FRIEND_LIST"));
                return false;

            }

            $sender->sendMessage(TextFormat::RED . "please use this command in-game");
        }
        return true;

    }

    private function formList(VOLTPlayer $sender)
    {
        $count = count($sender->getFriends());
        $array = [];
        $online = 0;
        $offline = $count - 1;

        foreach ($sender->getFriends() as $friend) {

            if (Gambler::getConnect($friend) !== "offline") {

                $array[$online] = $friend;
                $online++;

            } else {

                $array[$offline] = $friend;
                $online--;

            }

        }

        $this->friends[strtolower($sender->getName())] = $array;

        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {

                } else {

                    if (count($this->friends[strtolower($player->getName())]) >= 1) {

                        $friend = $this->friends[strtolower($player->getName())][$data];
                        unset($this->friends[array_search(strtolower($player->getName()), $this->friends)]);
                        $this->formInfo($player, $friend);

                    } else {

                        unset($this->friends[array_search(strtolower($player->getName()), $this->friends)]);

                    }

                }

            }

        );

        $ui->setTitle($sender->messageToTranslate("FRIENDS_LIST_TITLE"));
        $ui->setContent($sender->messageToTranslate("FRIENDS_LIST_CONTENT"));


        if ($count >= 1) {

            foreach ($array as $name) {

                $color = TextFormat::DARK_RED;

                if (Gambler::getConnect($name) !== "offline") {

                    $color = TextFormat::DARK_GREEN;

                }

                $ui->addButton($sender->messageToTranslate("UI_BUTTON", array(TextFormat::GRAY . $name . $color . " [" . Gambler::getConnect($name) . "]")), 0);

            }

        } else {

            $ui->addButton($sender->messageToTranslate("UI_BUTTON_VOID"));

        }

        $ui->sendToPlayer($sender);
    }

    private function formInfo(VOLTPlayer $sender, string $name)
    {
        $this->friends[strtolower($sender->getName())] = $name;

        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                $name = $this->friends[strtolower($player->getName())];
                unset($this->friends[array_search(strtolower($player->getName()), $this->friends)]);

                if ($data === null) {

                } else {

                    switch ($data) {

                        case 0:

                            Core::getInstance()->getServer()->dispatchCommand($player, "friend del ". $name);
                            break;

                        case 1:
                            Core::getInstance()->getServer()->dispatchCommand($player, "friend join");
                            break;

                    }

                }

            }

        );

        $ui->setTitle($sender->messageToTranslate("FRIENDS_INFO_TITLE"));
        $ui->setContent($sender->messageToTranslate("FRIENDS_INFO_CONTENT", array($name)));

        $ui->addButton($sender->messageToTranslate("FRIENDS_INFO_DELETE"));

        if (Gambler::getConnect($name) !== "offline") {

            $ui->addButton($sender->messageToTranslate("FRIENDS_INFO_JOIN"));

        }

        $ui->sendToPlayer($sender);
    }

}