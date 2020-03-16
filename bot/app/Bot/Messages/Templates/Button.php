<?php

namespace App\Bot\Messages\Templates;

class Button
{

    var $title;
    var $data;
    var $type;

    public function __construct($title, $data)
    {
        $this->title = $title;
        $this->data = $data;
        $this->type = $this->guessType($data);
    }

    public function guessType($data){
        if(filter_var($data, FILTER_VALIDATE_URL)){
            return "web_url";
        }else{
            return "postback";
        }
    }

    public function getTitle(){
        return $this->title;
    }

    public function getData(){
        return $this->data;
    }

    public function getType(){
        return $this->type;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setData($data){
        $this->data = $data;
        $this->type = $this->guessType($data);
    }

    public function getButton(){
        if($this->type == "web_url"){
            return array (
                'type' => $this->type,
                'url' => $this->data,
                'title' => $this->title,
            );
        }else if($this->type == "postback"){
            return array (
                'type' => $this->type,
                'payload' => $this->data,
                'title' => $this->title,
            );
        }
        
    }


}