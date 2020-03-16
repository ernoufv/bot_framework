<?php

namespace App\Bot\Messages;
use App\Bot\Messages\Templates\Button;

class Text
{

    var $text;
    var $quickReplies;
    var $buttons;

    public function __construct($text = null)
    {
        $this->text = $text;
        $this->quickReplies = array();
        $this->buttons = array();
    }

    public function setText($text){
        $this->text = $text;
    }

    public function getText(){
        return $this->text;
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

    public function addButton($title, $payload){
        $btn = new Button($title, $payload);
        $this->buttons[] = $btn->getButton();
    }

    public function getQuickReplies(){
        return $this->quickReplies;
    }

    public function getButtons(){
        return $this->buttons;
    }

}