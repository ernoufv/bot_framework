<?php

namespace Bot;

use App\Bot\Messages\Text;

function sendText($bot, $param = null, $message){

    $text = new Text($message["text"]);
    $bot->sendMessage($text);

}

function sendTextRandom($bot, $param = null, $message){

    $randText = array_rand($message["text"]);
    $text = new Text($message["text"][$randText]);
    $bot->sendMessage($text);

}

function sendTextQueue($bot, $param = null, $message){

    $texts = array();

    foreach($message["text"] as $text){
        $texts[] = new Text($text);
    }
    
    $bot->sendMessages($texts);

}

function sayHello($bot, $message = null, $param = null){
    $bot->sendMessage("Hello from code");
}