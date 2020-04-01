<?php

namespace Bot;

use App\Bot\Messages\Text;

function sendText($bot, $param = null, $message){

    $text = new Text($message["text"]);

    if(isset($message['quickreplies'])){
        foreach($message['quickreplies'] as $qr){
            $text->addQuickReply($qr['label'], $qr['payload']);
        }
    }

    $bot->sendMessage($text);

}

function sendTextRandom($bot, $param = null, $message){

    $randText = array_rand($message["text"]);
    $text = new Text($message["text"][$randText]);

    if(isset($message['quickreplies'])){
        foreach($message['quickreplies'] as $qr){
            $text->addQuickReply($qr['label'], $qr['payload']);
        }
    }

    $bot->sendMessage($text);

}

function sendTextQueue($bot, $param = null, $message){

    $texts = array();

    foreach($message["text"] as $text){
        $texts[] = new Text($text);
    }

    if(isset($message['quickreplies'])){
        foreach($message['quickreplies'] as $qr){
            $texts[count($texts)-1]->addQuickReply($qr['label'], $qr['payload']);
        }
    }
    
    $bot->sendMessages($texts);

}

function sayHello($bot, $message = null, $param = null){
    $bot->sendMessage("Hello from code");
}