<?php

namespace Bot;

use App\Bot\Messages\Text;
use App\Bot\Messages\Image;

function sendImage($bot, $param = null, $message){
    
    if(!isset($message['text'])){
        $image = new Image($message['url']);
        $bot->sendMessage($image);
    }else{
        $text = new Text($message['text']);
        $image = new Image($message['url']);
        $bot->sendMessages(
            [
                $text,
                $image
            ]
        );
    }

}