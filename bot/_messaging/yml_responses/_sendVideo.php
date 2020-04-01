<?php

namespace Bot;

use App\Bot\Messages\Text;
use App\Bot\Messages\Video;

function sendVideo($bot, $param = null, $message){
    
    if(!isset($message['text'])){
        $video = new Video($message['url']);

        if(isset($message['quickreplies'])){
            foreach($message['quickreplies'] as $qr){
                $video->addQuickReply($qr['label'], $qr['payload']);
            }
        }

        $bot->sendMessage($video);
    }else{
        $text = new Text($message['text']);
        $video = new Video($message['url']);

        if(isset($message['quickreplies'])){
            foreach($message['quickreplies'] as $qr){
                $video->addQuickReply($qr['label'], $qr['payload']);
            }
        }

        $bot->sendMessages(
            [
                $text,
                $video
            ]
        );
    }

}