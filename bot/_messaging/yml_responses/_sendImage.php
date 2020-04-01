<?php

namespace Bot;

use App\Bot\Messages\Text;
use App\Bot\Messages\Image;

function sendImage($bot, $param = null, $message){
    
    if(!isset($message['text'])){
        $image = new Image($message['url']);

        if(isset($message['quickreplies'])){
            foreach($message['quickreplies'] as $qr){
                $image->addQuickReply($qr['label'], $qr['payload']);
            }
        }

        $bot->sendMessage($image);
    }else{
        $text = new Text($message['text']);
        $image = new Image($message['url']);

        if(isset($message['quickreplies'])){
            foreach($message['quickreplies'] as $qr){
                $image->addQuickReply($qr['label'], $qr['payload']);
            }
        }

        $bot->sendMessages(
            [
                $text,
                $image
            ]
        );
    }

}