<?php

namespace Bot;

use App\Bot\Messages\Text;
use App\Bot\Messages\Image;
use App\Bot\Messages\Video;
use App\Bot\Messages\File;
use App\Bot\Messages\Audio;
use App\Bot\Messages\Templates\Generic;
use App\Bot\Messages\Templates\Button;


function cantReply($bot){
    $msg = new Text("Désolé, mais je ne suis pas encore entraîné pour répondre à vos questions.");
    $bot->sendMessage($msg);
}

function defaultUserPhoneNumber($bot){
    $msg = new Text("Merci de nous avoir communiqué votre numéro de téléphone");
    $bot->sendMessage($msg);
}

function defaultUserEmail($bot){
    $msg = new Text("Merci de nous avoir communiqué votre adresse e-mail");
    $bot->sendMessage($msg);
}

function discardFlag($bot){
    $bot->deleteFlag();
    $msg1 = new Text("D'accord, la saisie a été annulée !");
    $msg2 = new Text("Je vous renvoie le menu 🤓");
    $bot->sendMessages(array($msg1, $msg2));

    sleep(2);
    menu($bot);
}

function not_understood($bot, $param = null){
    $bot->sendMessage("Désolé, mais je n'ai pas compris 😫");
}
