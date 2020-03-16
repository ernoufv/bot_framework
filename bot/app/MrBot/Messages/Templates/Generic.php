<?php

namespace App\Bot\Messages\Templates;

class Generic
{

    var $cards;
    var $aspect_ratio;

    public function __construct($cards = array(), $aspect_ratio = "horizontal")
    {
        $this->cards = $cards;
        $this->aspect_ratio = $aspect_ratio;
    }

    public function getAspectRatio(){
        return $this->aspect_ratio;
    }

    public function setAspectRatio($aspect_ratio){
        $controlAspectRatio = array("horizontal", "square");
        if(in_array($aspect_ratio, $controlAspectRatio)){
            $this->aspect_ratio = $aspect_ratio;
        }else{
            $this->aspect_ratio = "honrizontal";
        }
    }

    public function addCard($title, $subtitle = null, $image_url = null, $buttons = null, $default_action_url = null, $default_action_wv_height = "TALL"){
        
        $this->controlCard($subtitle, $image_url, $buttons) == false ? $this->genericError("You must complete at least another field (Subtitle, Image URL or Buttons)") : null;

        $formatted_buttons = null;
        $default_action = null;

        if($buttons !== null && is_array($buttons)){
            $formatted_buttons = array();
            foreach($buttons as $button){
                if($button instanceof Button){
                    $formatted_buttons[] = $button->getButton();
                }
            }
        }

        if($default_action_url !== null){
            $default_action = array(
                "type" => "web_url",
                "url" => $default_action_url,
                "messenger_extensions" => false,
                "webview_height_ratio" => $default_action_wv_height
            );
        }

        $card = array(
            "title" => $title,
            "image_url" => $image_url,
            "subtitle" => $subtitle,
            "default_action" => $default_action,
            "buttons" => $formatted_buttons
        );

        

        $this->cards[] = $card;
    }

    public function getCards(){
        return $this->cards;
    }

    public function controlCard($subtitle, $image_url, $buttons){
        $variables = array($subtitle, $image_url, $buttons);
        foreach($variables as $variable){
            if($variable !== null){
                return true;
            }
        }
    }

    public function genericError($message){
        header('Content-Type: application/json');
        die(json_encode(array("error"=>array("message"=>$message))));
    }

    public function shuffleCards(){
        shuffle($this->cards);
    }

}