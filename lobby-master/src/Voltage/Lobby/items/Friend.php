<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 05/05/2019
 * Time: 01:01
 */

namespace Voltage\Lobby\items;

use Voltage\Core\base\Friends;
use Voltage\Core\form\CustomForm;
use Voltage\Core\form\ModalForm;
use Voltage\Core\form\SimpleForm;
use Voltage\Core\VOLTPlayer;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Voltage\Lobby\LBPlayer;
use Voltage\Lobby\Lobby;

class Friend extends Item
{
    private $friends = [];

    public function __construct(int $meta = 0)
    {
        parent::__construct(self::NAME_TAG, $meta, "Name Tag");
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($player instanceof LBPlayer) {

            if ($player->interact()) {

                $this->addUI($player);

            }

        }
        return true;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {
        if ($player instanceof LBPlayer) {

            if ($player->interact()) {

                $this->addUI($player);

            }

        }
        return true;
    }

    private function addUI(LBPlayer $player)
    {
        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch($data){

                        case 0:
                            Lobby::getInstance()->getServer()->dispatchCommand($player, "friend list");
                            break;
                        case 1:
                            $this->formAdd($player);
                            break;
                        case 2:
                            $this->formManage($player);
                            break;

                    }

                }

            }

        );
        $ui->setTitle($player->messageToTranslate("FRIENDS_TITLE", Friends::count($player->getName())));
        $ui->addButton($player->messageToTranslate("FRIENDS_LIST_UI"), 0);
        $ui->addButton($player->messageToTranslate("FRIENDS_ADD_UI"), 0);
        $ui->addButton($player->messageToTranslate("FRIENDS_MANAGE_UI", array(count($player->getFriendRequest()))), 0);
        $ui->sendToPlayer($player);

    }

    private function formAdd(VOLTPlayer $player)
    {
        $ui = new CustomForm
        (
            function (VOLTPlayer $player, $data)
            {

                if (isset($data[0])) {

                    Lobby::getInstance()->getServer()->dispatchCommand($player, "friend add " . $data[0]);

                }

            }

        );
        $ui->setTitle($player->messageToTranslate("FRIENDS_ADD_TITLE"));
        $ui->addInput("The name of the desired player","id");
        $ui->sendToPlayer($player);

    }

    private function formManage(VOLTPlayer $player)
    {
        $this->friends[strtolower($player->getName())] = $player->getFriendRequest();

        $ui = new SimpleForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {


                } else {

                    if (count($this->friends[strtolower($player->getName())]) >= 1) {

                        $name = $this->friends[strtolower($player->getName())][$data];
                        $this->formAccept($player,$name);

                    } else {

                        unset($this->friends[array_search(strtolower($player->getName()), $this->friends)]);

                    }

                }

            }

        );

        $ui->setTitle($player->messageToTranslate("FRIENDS_MANAGE_TITLE"));
        $ui->setContent($player->messageToTranslate("FRIENDS_MANAGE_CONTENT"));


        if (count($this->friends[strtolower($player->getName())]) >= 1) {

            foreach ($this->friends[strtolower($player->getName())] as $request) {

                $ui->addButton($player->messageToTranslate("UI_BUTTON", array(TextFormat::GRAY . $request)));

            }

        } else {

            $ui->addButton($player->messageToTranslate("UI_BUTTON_VOID"));

        }

        $ui->sendToPlayer($player);

    }

    private function formAccept(VOLTPlayer $player , string $name)
    {
        $this->friends[strtolower($player->getName())] = $name;

        $ui = new ModalForm
        (
            function (VOLTPlayer $player, $data)
            {

                if ($data === null) {
                } else {

                    switch ($data) {

                        case true:
                            $name = $this->friends[strtolower($player->getName())];
                            Lobby::getInstance()->getServer()->dispatchCommand($player, "friend accept " . $name);
                            unset($this->friends[strtolower($player->getName())]);
                            break;
                        case false:
                            $name = $this->friends[strtolower($player->getName())];
                            Lobby::getInstance()->getServer()->dispatchCommand($player, "friend decline " . $name);
                            unset($this->friends[strtolower($player->getName())]);
                            break;
                    }

                }

            }
        );
        $ui->setTitle($player->messageToTranslate("FRIENDS_ACCEPT_TITLE", array($name)));
        $ui->setContent($player->messageToTranslate("FRIEND_REQUEST_ACCEPT_UI", array($name)));
        $ui->setButton1($player->messageToTranslate("YES"));
        $ui->setButton2($player->messageToTranslate("NO"));
        $ui->sendToPlayer($player);

    }

}