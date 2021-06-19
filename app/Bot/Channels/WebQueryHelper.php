<?php

namespace App\Bot\Channels;

use App\Bot\Log;

/********************************
 * 
 *  Web channel have restricted functionnalities
 *  -> Actions "mark seen", "is_typing" are not rendered
 * 
 * 
 ********************************/


class WebQueryHelper
{

    public function __construct()
    {
        //
    }

    /********************************
     * 
     *  SENDER ACTIONS
     * 
     ********************************/

    public function seen($userId){
        //Not available in web channel, returns true
        return true;
    }

    public function typing($userId, $action = true, $personaId = null){
        //Not available in web channel, returns true
        return true;
    }

     /********************************
     * 
     *  "SIMPLE" MESSAGES
     * 
     ********************************/

    public function sendText($userId, $message, $personaId = null){
          
        $textMessage = array (
          'type' => 'text',
          'content' => 
          array (
            'text' => $message->getText(),
          ),
        );

          
        if(count($message->getQuickReplies()) > 0){
            $textMessage["content"]["quick_replies"] = $message->getQuickReplies();
        }

        if(count($message->getButtons()) > 0){
            $textMessage["type"] = "buttons";
            $textMessage["content"]["buttons"] = $message->getButtons();
        }

        return $textMessage;


    }

    public function sendImage($userId, $message, $personaId = null){
          
        $imageMessage = array(
          "type" => "image",
          "content" => array(
            "image_url" => $message->getImage()
          )
        );

        if(count($message->getQuickReplies()) > 0){
            $imageMessage["content"]["quick_replies"] = $message->getQuickReplies();
        }

        return $imageMessage;


    }

    public function sendVideo($userId, $message, $personaId = null){
          
        $videoMessage = array(
          "type" => "video",
          "content" => array(
            "video_url" => $message->getVideo()
          )
        );

        if(count($message->getQuickReplies()) > 0){
            $videoMessage["content"]["quick_replies"] = $message->getQuickReplies();
        }

        return $videoMessage;


    }

    public function sendFile($userId, $message, $personaId = null){
          
        $fileMessage = $videoMessage = array(
          "type" => "file",
          "content" => array(
            "file_url" => $message->getFile()
          )
        );

        if(count($message->getQuickReplies()) > 0){
            $fileMessage["content"]["quick_replies"] = $message->getQuickReplies();
        }

        return $fileMessage;


    }

    public function sendAudio($userId, $message, $personaId = null){
          
        $audioMessage = $videoMessage = array(
          "type" => "audio",
          "content" => array(
            "audio_url" => $message->getAudio()
          )
        );

        if(count($message->getQuickReplies()) > 0){
            $audioMessage["message"]["quick_replies"] = $message->getQuickReplies();
        }

        return $audioMessage;


    }


    /********************************
     * 
     *  TEMPLATES
     * 
     ********************************/

    public function sendGeneric($userId, $generic, $personaId = null){

        $generic = array (
            "type" => "carousel",
            "content" => array(
              "elements" => $generic->getCards(),
            ),
            
        );

        return $generic;
    }

    /********************************
     * 
     *  USER INFORMATIONS
     * 
     ********************************/
    
     public function getUserFirstname($userId){
        //Not available in web channel, returns empty string
        return "";
     }

     public function getUserLastname($userId){
        //Not available in web channel, returns empty string
        return "";
     }
    

     /********************************
     * 
     *  QUERIES
     * 
     ********************************/
    public function sendQuery($body){
      //Not available in web channel, returns true
      return true;
    }

    public function jsonSerialize($array){
        return json_encode($array);
    }

}
