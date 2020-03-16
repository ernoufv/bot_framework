<?php

namespace App\Bot\Attachments;

class Attachment
{

    var $type;
    var $url;
    var $userId;

    public function __construct($type, $url, $userId)
    {
        $this->type = $type;
        $this->url = $url;
        $this->userId = $userId;
    }
    
    public function save(){
        
        switch($this->type){
            case "image":
                $path = env("USER_IMG_PATH").$this->userId."/";
                break;

            case "video":
                $path = env("USER_VID_PATH").$this->userId."/";
                break;

            case "file":
                $path = env("USER_DOC_PATH").$this->userId."/";
                break;  

            case "audio":
                $path = env("USER_AUDIO_PATH").$this->userId."/";
                break;
        }

        $file_name = explode("?", basename($this->url));

        if(is_dir($path)){
            $file = file_get_contents($this->url);
            file_put_contents($path.$file_name[0], $file);
        }else{
            mkdir($path);
            $file = file_get_contents($this->url);
            file_put_contents($path.$file_name[0], $file);
        }
    }

}