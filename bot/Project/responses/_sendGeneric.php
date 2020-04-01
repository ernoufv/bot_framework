<?php

namespace Bot;

use App\Bot\Messages\Templates\Generic;
use App\Bot\Messages\Templates\Button;

function sendGeneric($bot, $message, $param = null){
    
    $carousel = new Generic();

    foreach($message['cards'] as $card){
        
        if(isset($card['buttons'])){
            $buttons = array();
            foreach($card['buttons'] as $button){
                $buttons[] = new Button($button['label'], $button['payload']);
            }
        }

        $carousel->addCard(
            isset($card['title']) ? $card['title'] : null,
            isset($card['description']) ? $card['description'] : null,
            isset($card['image']) ? $card['image'] : null,
            isset($buttons) ? $buttons : null,
            isset($card['default_url']) ? $card['default_url'] : null
        );
        
    }

    $bot->sendMessage($carousel);

}

function sendGenericRandom($bot, $message, $param = null){
    
    $carousel = new Generic();

    foreach($message['cards'] as $card){
        
        if(isset($card['buttons'])){
            $buttons = array();
            foreach($card['buttons'] as $button){
                $buttons[] = new Button($button['label'], $button['payload']);
            }
        }

        $carousel->addCard(
            isset($card['title']) ? $card['title'] : null,
            isset($card['description']) ? $card['description'] : null,
            isset($card['image']) ? $card['image'] : null,
            isset($buttons) ? $buttons : null,
            isset($card['default_url']) ? $card['default_url'] : null
        );
        
    }

    $carousel->shuffleCards();
    $bot->sendMessage($carousel);

}