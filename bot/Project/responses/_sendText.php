<?php

namespace Bot;

use App\Bot\Messages\Text;

function sendText($bot, $message, $param = null){

    $text = new Text($message["text"]);
    $bot->sendMessage($text);

}

function sendTextRandom($bot, $message, $param = null){

    $randText = array_rand($message["text"]);
    $text = new Text($message["text"][$randText]);
    $bot->sendMessage($text);

}

function sendTextQueue($bot, $message, $param = null){

    $texts = array();

    foreach($message["text"] as $text){
        $texts[] = new Text($text);
    }
    
    $bot->sendMessages($texts);

}

function sayHello($bot, $message = null, $param = null){
    $bot->sendMessage("Hello from code");
}