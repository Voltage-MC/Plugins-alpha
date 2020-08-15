<?php
/**
 * Created by PhpStorm.
 * User: Lilian
 * Date: 14/04/2019
 * Time: 19:49
 */

namespace Voltage\Core\lang;

use pocketmine\utils\TextFormat as TE;

class English
{
    public $translates =
        array(
            "ERROR" => TE::RED . "There was a problem with the translation of the message ",
            "PLAYER_NOT_FOUND" => TE::RED . "The player has not been found ",
            "RIGHT_CLICK" => "§r§dRight Click",

            "KICK_WHITELIST" => TE::RED . "The oppening will take place friday 12 of july at 12 o'clock (us time).",

            "FLOATING_TEXT_TOP" =>
                TE::LIGHT_PURPLE . "1 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "2 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "3 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "4 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "5 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "6 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "7 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "8 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "9 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n" .
                TE::LIGHT_PURPLE . "10 " . TE::BOLD . TE::DARK_PURPLE . "» " . TE::WHITE . "% " . TE::RESET . TE::GREEN . "% " . TE::GRAY . "with " . TE::LIGHT_PURPLE .  "% Wins" . TE::GRAY . " and " . TE::LIGHT_PURPLE .  "% Lost\n",

            "YES" => "Yes",
            "NO" => "No",

            "USAGE" => TE::RED . "Usage: %",

            "JOIN_MESSAGE" => TE::LIGHT_PURPLE . "You are connected to ". TE::GRAY . "%",
            "JOIN_SUBTITLE" => TE::GRAY . "Electrification is coming",

            "TRANSFER_TELEPORT_UI" => TE::LIGHT_PURPLE . "Are you sure you want to connect to the " . TE::GRAY . "%" . " server " . TE::LIGHT_PURPLE . "?",

            "RANK_BROADCAST" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " received the rank ". TE::GRAY . "%",

            "LOBBY_TELEPORT" => TE::GRAY . "You teleported to the " . TE::LIGHT_PURPLE . "Lobby",

            "HUB_TELEPORT" => TE::GRAY . "You teleported to the " . TE::LIGHT_PURPLE . "Hub",

            "GAME_SELECTOR_TITLE" => TE::BOLD . TE::DARK_RED . "Transfer",
            "GAME_SELECTOR_CONTENT" => TE::WHITE . "Where do you want to go?",

            "TPW_NOT_EXIST" => TE::RED . "The world you want doesn't exist",
            "TPW_TELEPORT" => TE::GRAY . "You were teleported to the world " . TE::LIGHT_PURPLE . "%",
            "TPW_LIST" =>
                "Tpw system:\n" .
                TE::YELLOW . "/tpw" . TE::GOLD." [world]",

            "LANG_CHANGED" => TE::LIGHT_PURPLE . "Your language has changed to ". TE::GRAY . "English",
            "LANG_LIST" =>
                "Lang system:\n" .
                TE::YELLOW . "/lang" . TE::GOLD." [langname]",

            "FLOATING_TEXT_WELCOME" => "§e\n" . TE::GRAY . "play.voltage.eu:19132\n" . TE::RED . "There are" . TE::AQUA . " % " . TE::RED . "conneted in the network\n" . TE::DARK_PURPLE . "For more information, visit " .  TE::AQUA . "https://Voltage.eu\n" . TE::GRAY . "Thank you for playing on our". TE::AQUA . " % " . TE::GRAY .  "network\n§e\n" . TE::WHITE . "click on NPCs to start playing",

            "UI_BUTTON" => TE::BOLD . "%" . TE::RESET . TE::GRAY . "\n(Click)",
            "UI_BUTTON_BACK" => TE::BOLD . TE::DARK_RED .  "« Back",
            "UI_BUTTON_VOID" => TE::BOLD . TE::DARK_PURPLE .  "« Void",

            "BAN_BROADCAST" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " has been banned by ". TE::GRAY . "%",
            "BAN_KICK" =>
                TE::DARK_PURPLE . "*  " . TE::BOLD . TE::LIGHT_PURPLE . "Voltage" . TE::RESET . "\n".
                TE::DARK_PURPLE . "*  " . TE::LIGHT_PURPLE . "You are Banned By " . TE::GRAY . "%" . TE::LIGHT_PURPLE . " for " . TE::GRAY . "%" . "\n".
                TE::DARK_PURPLE . "*  " . TE::LIGHT_PURPLE . "Temporarily banned  from server for " . "%",

            "KICK_BROADCAST" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " has been kicked by ". TE::GRAY . "%",
            "KICK_KICK" =>
                TE::DARK_PURPLE . "*  " . TE::BOLD . TE::LIGHT_PURPLE . "Voltage" . TE::RESET . "\n".
                TE::DARK_PURPLE . "*  " . TE::LIGHT_PURPLE . "You are Kicked By " . TE::GRAY . "%" . TE::LIGHT_PURPLE . " for " . TE::GRAY . "%",

            "PARTY_TITLE" => TE::DARK_GRAY . "Party",
            "PARTY_CREATE_UI" => TE::DARK_GRAY . "Create a party",
            "PARTY_INVITE_UI" => TE::DARK_GRAY . "Invited a friend",
            "PARTY_MANAGE_UI" => TE::DARK_GRAY . "Manage invitations [" . TE::GRAY . "%" . TE::DARK_GRAY . "]",

            "HIDE_DESCRIPTION_1" => "§cHidden all the players of",
            "HIDE_DESCRIPTION_2" => "§cthe server (Reduce lags)",
            "HIDE_MESSAGE_SHOW" => "§aYou can now see the players",
            "HIDE_MESSAGE_HIDE" => "§7You can't now see the players",

            "COINS_WIN" => TE::YELLOW . "You won " . TE::AQUA . "% ",
            "COINS_GET" => TE::YELLOW . "You have " . TE::AQUA . "% ",

            "PET_DESCRIPTION_1" => "§cBuy your Pets here a multitude",
            "PET_DESCRIPTION_2" => "§cof choices are available",

            "FRIENDS_TITLE" => TE::DARK_GRAY . "Friends " . TE::GREEN . "% online" . TE::DARK_GRAY . " : " . TE::RED . "% offline",
            "FRIENDS_LIST_UI" => TE::DARK_GRAY . "List a friend",
            "FRIENDS_ADD_UI" => TE::DARK_GRAY . "Added a friend",
            "FRIENDS_MANAGE_UI" => TE::DARK_GRAY . "Manage invitations [" . TE::GRAY . "%" . TE::DARK_GRAY . "]",
            "FRIENDS_LIST_TITLE" => TE::DARK_GRAY . "Friends list",
            "FRIENDS_LIST_CONTENT" => TE::WHITE . "Here is the list of your friends",
            "FRIENDS_MANAGE_TITLE" => TE::DARK_GRAY . "The list of friend requests",
            "FRIENDS_MANAGE_CONTENT" => TE::WHITE . "To accept the request please check on the nickname",
            "FRIENDS_INFO_TITLE" => TE::DARK_GRAY . "Info %",
            "FRIENDS_INFO_CONTENT" => TE::WHITE . "Managing friendship with " . TE::GRAY . "%",
            "FRIENDS_INFO_DELETE" => TE::DARK_GRAY . "Delete your friend",
            "FRIENDS_INFO_JOIN" => TE::DARK_GRAY . "Join your friend",
            "FRIENDS_ADD_TITLE" => TE::DARK_GRAY . "Added a friend",
            "FRIEND_ADD_SEND" => TE::LIGHT_PURPLE . "You have sent a request for friends to " . TE::GRAY . "%",
            "FRIEND_ADD_RECEIVED" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " you sent requested to be your friend " . TE::GREEN . "/friend accept (name)" . TE::LIGHT_PURPLE . " or" . TE::GREEN . " /friend decline (name)",
            "FRIEND_REQUEST_ACCEPT_SEND" => TE::LIGHT_PURPLE . "You have accepted the request of friends",
            "FRIEND_REQUEST_ACCEPTED_RECEIVED" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " has accepted your request for friends",
            "FRIEND_REQUEST_DECLINE_SEND" => TE::LIGHT_PURPLE . "You have refused the request of friends",
            "FRIEND_REQUEST_DECLINE_RECEIVED" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " has refused your request for friends",
            "FRIEND_REQUEST_ALREADY" => TE::LIGHT_PURPLE . "You have already sent a friend request to this person",
            "FRIEND_ALREADY" => TE::RED . "You are already friends",
            "FRIEND_NOT" => TE::RED . "This person is not your friend",
            "FRIEND_NOT_YOU" => TE::RED . "You can't add yourself",
            "FRIEND_REMOVE_SEND" => TE::LIGHT_PURPLE . "You did delete your " . TE::GRAY .  "%" . TE::LIGHT_PURPLE . " friends",
            "FRIEND_REMOVE_RECEIVED" => TE::GRAY .  "%" . TE::LIGHT_PURPLE . " has you off his list of friends",
            "FRIENDS_ACCEPT_TITLE" => TE::DARK_GRAY . "%" . TE::LIGHT_PURPLE . "'s requests",
            "FRIENDS_REQUEST_ACCEPT_TITLE" => TE::LIGHT_PURPLE . "Are you sure you've accepted " . TE::GRAY . "%" . TE::LIGHT_PURPLE . "'s request?",
            "FRIEND_LIST" =>
                "Friend system:\n" .
                TE::YELLOW . "/friend" . TE::GOLD." [add/del/decline/accept/list]" . TE::RED." <name>",

            "PVP_STATS_TITLE_UI" => TE::DARK_GRAY . "Stats",
            "PVP_STATS_TITLE" => TE::LIGHT_PURPLE . "%" . TE::GRAY . "'s Stats",
            "PVP_STATS" => TE::GRAY . "%" . ": " . TE::LIGHT_PURPLE . "%",
            "PVP_STATS_NETWORK" => TE::GRAY . "Network stats",
            "PVP_STATS_PVP" => TE::GRAY . "Pvp stats",
            "PVP_LIST" => "Stats system:\n"
                . TE::YELLOW . "/seestats" . TE::GOLD." [player]" . TE::GRAY . " - allows you to view the stats of a particular player" ."\n"
                . TE::YELLOW . "/stats" . TE::GRAY . " - allows you to view your stats",
            "PVP_FFA_TITLE_UI" => TE::BOLD . TE::DARK_RED . "Teleport FFA",
            "PVP_GIVE_KIT" => TE::GRAY . "Your kit has been given",
            "PVP_WIN" => TE::YELLOW . "You won " . TE::AQUA . "%" . TE::YELLOW . " Elos",
            "PVP_LOST" => TE::YELLOW . "You lost " . TE::AQUA . "%" . TE::YELLOW . " Elos",

            "GAME_PREJOIN" => TE::LIGHT_PURPLE . "In Games: " . TE::RED . TE::BOLD . "%",
            "GAME_JOIN_INFO" => TE::LIGHT_PURPLE . "Map : " . TE::GRAY . "%" . TE::LIGHT_PURPLE . " by " . TE::GRAY . "%",
            "GAME_JOIN_BROADCAST" => TE::GRAY . "%" . TE::LIGHT_PURPLE . " has joined the game. " . TE::GRAY . "(%/%)",
            "GAME_IS_FULL" => TE::LIGHT_PURPLE . "You have been put in spectator mode because the game is complete",
            "GAME_IS_INGAME" => TE::LIGHT_PURPLE . "You have been put in spectator mode because the game has started",
            "GAME_PREPARATION" => TE::LIGHT_PURPLE . "To wait " . TE::DARK_PURPLE . ": " . TE::GRAY . "Waiting for % player(s)\n\n",
            "GAME_BEGGING" => TE::LIGHT_PURPLE . "Beginning " . TE::DARK_PURPLE . ": " . TE::GRAY . "Starts in %\n\n",
            "GAME_FINISH" => TE::LIGHT_PURPLE . "Finish " . TE::DARK_PURPLE . ": " . TE::GRAY . "Teleportation in %\n\n",
            "GAME_GET_TEAM" => TE::GRAY . "You are a %" . TE::RESET . TE::GRAY . " !",
            "GAME_YOU_HAVE_LOST" => TE::RED . "You have lost !",
            "GAME_YOU_HAVE_WON" => TE::GREEN . "You have won !",
            "GAME_WIN_BROADCAST" => TE::GRAY . "The " . "%". TE::RESET . TE::GRAY . " team wins the game!",
            "GAME_KILL_BY_ENTITY" => TE::GRAY . "% " . TE::LIGHT_PURPLE . "was killed by" . TE::GRAY . " %",
            "GAME_DIED" => TE::GRAY . "% ". TE::LIGHT_PURPLE . "is dead.",
            "GAME_DIED_WHIT_CAUSE_BY_ENTITY" => TE::GRAY . "%" . TE::LIGHT_PURPLE . "%" . " by " . TE::GRAY . "%",
            "GAME_DIED_WHIT_CAUSE" => TE::GRAY . "%" . TE::LIGHT_PURPLE . "%" ,
            "GAME+1_TIP" => TE::GRAY . "% " . TE::DARK_PURPLE . ": " . TE::GRAY . "+1 point !",
            "GAME+1_BROADCAST" => TE::GRAY . "% ". TE::LIGHT_PURPLE . "scores 1 point for the %" . TE::RESET . TE::LIGHT_PURPLE . " Team",
            "GAME_POINT_BROADCAST" => TE::LIGHT_PURPLE . "The " . "% " . TE::RESET . TE::LIGHT_PURPLE . "now have " . TE::GRAY . "% points !",
            "GAME_GO" => TE::GREEN . TE::BOLD . "Let's go",
            "GAME_START_IN" => TE::GREEN . TE::BOLD . "Begins in",
            "GAME_UI_WIN" => TE::WHITE . "Game summary: " . "\n\n" .
                "     " . TE::BOLD . TE::LIGHT_PURPLE . " » % Xps" . "\n" .
                "    " . TE::BOLD . TE::LIGHT_PURPLE . " » % Money for participation" . "\n" .
                "    " . TE::BOLD . TE::LIGHT_PURPLE . " » % Money for win" . "\n" .
                "    " . TE::BOLD . TE::LIGHT_PURPLE . " » % Key" . "\n" .
                "    " . TE::BOLD . TE::LIGHT_PURPLE . " » % Credits" . "\n\n" .
                TE::RESET . TE::WHITE . "We will soon find a game",
            "GAME_TELEPORT" => TE::RED . TE::BOLD . "Teleport",
            "GAME_TELEPORT_TO_PLAYER" => TE::LIGHT_PURPLE . "You were teleported to " . TE::GRAY . "%",
            "GAME_NO_FOUND" => TE::RED . "No game has been found. Please wait until a game is released or join another game",

            "GAME_HIKABRAIN_INGAME" => TE::LIGHT_PURPLE . "End " . TE::DARK_PURPLE . " : " . TE::GRAY . "% " . TE::DARK_PURPLE . ": " . TE::GRAY . "% players alive " . TE::DARK_PURPLE . ": " . TE::GRAY . "% teams alive" . "\n\n",
            "GAME_HIKABRAIN_DIED_IN_VOID" => TE::GRAY . "% " . TE::LIGHT_PURPLE . "died in a vacuum",
            "GAME_HIKABRAIN_DIED_IN_VOID_BY_ENTITY" => TE::GRAY . "% " . TE::LIGHT_PURPLE . "died in a vacuum by " . TE::GRAY . "%",
            "GAME_HIKABRAIN_FLOATING_TEXT" => TE::GRAY . "",
        );

}