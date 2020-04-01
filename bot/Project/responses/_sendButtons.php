<?php

namespace Bot;

use App\Bot\Messages\Text;
use App\Bot\Messages\Templates\Button;

function sendButtons($bot, $message, $param = null){

    $buttons = new Text($message["text"]);
    foreach($message["buttons"] as $btn){
        $buttons->addButton($btn["label"], $btn["payload"]);
    }

    $bot->sendMessage($buttons);

}