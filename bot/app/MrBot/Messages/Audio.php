<?php

namespace App\Bot\Messages;
use App\Messages\Templates\Button;

class Audio
{

    var $audio;
    var $quickReplies;
    var $buttons;

    public function __construct($audio = null)
    {
        $this->audio = $audio;
        $this->quickReplies = array();
        $this->buttons = array();
    }

    public function setAudio($text){
        $this->audio = $audio;
    }

    public function getAudio(){
        return $this->audio;
    }

    public function addQuickReply($title, $payload, $content_type = "text", $image_url = null){
        $content_types = array("text", "user_phone_number", "user_email");
        if(in_array($content_type, $content_types)){
            if($content_type == "text"){
                $this->quickReplies[] = array(
                    "content_type" => $content_type,
                    "title" => $title,
                    "payload" => $payload,
                    "image_url" => $image_url
                );
            }else{
                $this->quickReplies[] = array(
                    "content_type" => $content_type,
                    "payload" => $payload,
                    "image_url" => $image_url
                );
            }
        }
    }

    public function getQuickReplies(){
        return $this->quickReplies;
    }

}