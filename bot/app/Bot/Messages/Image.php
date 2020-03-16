<?php

namespace App\Bot\Messages;
use App\Bot\Messages\Templates\Button;

class Image
{

    var $image;
    var $quickReplies;
    var $buttons;

    public function __construct($image = null)
    {
        $this->image = $image;
        $this->quickReplies = array();
        $this->buttons = array();
    }

    public function setImage($image){
        $this->image = $image;
    }

    public function getImage(){
        return $this->image;
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