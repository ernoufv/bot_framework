<?php

namespace App\Bot\Messages;
use App\Bot\Messages\Templates\Button;

class File
{

    var $file;
    var $quickReplies;
    var $buttons;

    public function __construct($file = null)
    {
        $this->file = $file;
        $this->quickReplies = array();
        $this->buttons = array();
    }

    public function setFile($text){
        $this->file = $file;
    }

    public function getFile(){
        return $this->file;
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