<?php

namespace Bot;

use App\Bot\Messages\Text;

function sendQuickreplies($bot, $param = null, $message){

    $text = new Text($message["text"]);
    foreach($message["quickreplies"] as $qr){
        $text->addQuickReply($qr["label"], $qr["payload"]);
    }

    $bot->sendMessage($text);

}