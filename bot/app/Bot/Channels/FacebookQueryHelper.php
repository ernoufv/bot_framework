<?php

namespace App\Bot\Channels;
use App\Bot\Messages\Attachments;
//use Illuminate\Database\Eloquent\Model;


class FacebookQueryHelper// extends Model
{

    var $endpoint;
    var $client;

    public function __construct()
    {
        $this->endpoint = "https://graph.facebook.com/v".env("FACEBOOK_API_VERSION")."/me/messages?access_token=".env("PAGE_ACCESS_TOKEN");
        $this->client = new \GuzzleHttp\Client();
    }


    /********************************
     * 
     *  SENDER ACTIONS
     * 
     ********************************/

    public function seen($userId){
        $message = array (
            'recipient' => 
            array (
              'id' => $userId,
            ),
            'sender_action' => "mark_seen"
        );

        return $this->sendQuery($this->jsonSerialize($message));
    }

    public function typing($userId, $action = true, $personaId = null){
        $action = $action == true ? "typing_on" : "typing_off";
        $message = array (
            'recipient' => 
            array (
              'id' => $userId,
            ),
            'sender_action' => $action,
            'persona_id' => $personaId
        );

        return $this->sendQuery($this->jsonSerialize($message));
    }


     /********************************
     * 
     *  "SIMPLE" MESSAGES
     * 
     ********************************/

    public function sendText($userId, $message, $personaId = null){
          
        $textMessage = array(
            "recipient" => array(
                "id" => $userId
            ),
            "message" => array(
                "text" => $message->getText()
            ),
            "persona_id" => $personaId
        );

          
        if(count($message->getQuickReplies()) > 0){
            $textMessage["message"]["quick_replies"] = $message->getQuickReplies();
        }

        if(count($message->getButtons()) > 0){
            $textMessage = array(
                "recipient" => array(
                    "id" => $userId
                ),
                "message" => array(
                    "attachment"=> array(
                        "type" => "template",
                        "payload" => array(
                            "template_type" => "button",
                            "text" => $message->getText(),
                            "buttons" => $message->getButtons()
                        )
                    )
                ),
                "persona_id" => $personaId
            );
        }
        //echo json_encode($textMessage);
        return $this->sendQuery($this->jsonSerialize($textMessage));


    }

    public function sendImage($userId, $message, $personaId = null){

        $image_uri = $message->getImage();

        $attch = new Attachments($image_uri, "image");
        $attch_id = $attch->exists();

        $imageMessage = array(
            "recipient" => array(
                "id" => $userId
            ),
            "message" => array(
                "attachment"=> array(
                    "type" => "image",
                    "payload" => array(
                        "attachment_id" => $attch_id,
                        //If you want to upload each time your media to facebook
                        /*"url" => $message->getImage(),
                        "is_reusable" => true*/
                    )
                )
            ),
            "persona_id" => $personaId
        );

        if(count($message->getQuickReplies()) > 0){
            $imageMessage["message"]["quick_replies"] = $message->getQuickReplies();
        }

        return $this->sendQuery($this->jsonSerialize($imageMessage));


    }

    public function sendVideo($userId, $message, $personaId = null){
          
        $video_uri = $message->getVideo();

        $attch = new Attachments($video_uri, "video");
        $attch_id = $attch->exists();

        $videoMessage = array(
            "recipient" => array(
                "id" => $userId
            ),
            "message" => array(
                "attachment"=> array(
                    "type" => "video",
                    "payload" => array(
                        "attachment_id" => $attch_id,
                        //If you want to upload each time your media to facebook
                        /*"url" => $message->getVideo(),
                        "is_reusable" => true*/
                    )
                )
            ),
            "persona_id" => $personaId
        );

        if(count($message->getQuickReplies()) > 0){
            $videoMessage["message"]["quick_replies"] = $message->getQuickReplies();
        }

        return $this->sendQuery($this->jsonSerialize($videoMessage));


    }

    public function sendFile($userId, $message, $personaId = null){
         
        $file_uri = $message->getFile();

        $attch = new Attachments($file_uri, "file");
        $attch_id = $attch->exists();

        $fileMessage = array(
            "recipient" => array(
                "id" => $userId
            ),
            "message" => array(
                "attachment"=> array(
                    "type" => "file",
                    "payload" => array(
                        "attachment_id" => $attch_id,
                        //If you want to upload each time your media to facebook
                        /*"url" => $message->getFile(),
                        "is_reusable" => true*/
                    )
                )
            ),
            "persona_id" => $personaId
        );

        if(count($message->getQuickReplies()) > 0){
            $fileMessage["message"]["quick_replies"] = $message->getQuickReplies();
        }

        return $this->sendQuery($this->jsonSerialize($fileMessage));


    }

    public function sendAudio($userId, $message, $personaId = null){
          
        $audio_uri = $message->getAudio();

        $attch = new Attachments($audio_uri, "audio");
        $attch_id = $attch->exists();

        $audioMessage = array(
            "recipient" => array(
                "id" => $userId
            ),
            "message" => array(
                "attachment"=> array(
                    "type" => "audio",
                    "payload" => array(
                        "attachment_id" => $attch_id,
                        //If you want to upload each time your media to facebook
                        /*"url" => $message->getAudio(),
                        "is_reusable" => true*/
                    )
                )
            ),
            "persona_id" => $personaId
        );

        if(count($message->getQuickReplies()) > 0){
            $audioMessage["message"]["quick_replies"] = $message->getQuickReplies();
        }

        return $this->sendQuery($this->jsonSerialize($audioMessage));


    }


    /********************************
     * 
     *  TEMPLATES
     * 
     ********************************/

    public function sendGeneric($userId, $generic, $personaId = null){
        $generic = array (
            'recipient' => 
            array (
              'id' => $userId,
            ),
            'message' => 
            array (
              'attachment' => 
              array (
                'type' => 'template',
                'payload' => 
                array (
                  'image_aspect_ratio' => $generic->getAspectRatio(),
                  'template_type' => 'generic',
                  'elements' => $generic->getCards()
                ),
              ),
            ),
            'persona_id' => $personaId
        );

        //echo $this->jsonSerialize($generic);

        return $this->sendQuery($this->jsonSerialize($generic));
    }


        //https://graph.facebook.com/<PSID>?fields=first_name,last_name,profile_pic&access_token=<PAGE_ACCESS_TOKEN>

    /********************************
     * 
     *  USER INFORMATIONS
     * 
     ********************************/
    
     public function getUserFirstname($userId){
        $response = $this->client->request(
            'GET', 
            "https://graph.facebook.com/".$userId."?fields=first_name&access_token=".env("PAGE_ACCESS_TOKEN")
        );

        $res = json_decode($response->getBody(), true);
        return $res["first_name"];
     }

     public function getUserLastname($userId){
        $response = $this->client->request(
            'GET', 
            "https://graph.facebook.com/".$userId."?fields=last_name&access_token=".env("PAGE_ACCESS_TOKEN")
        );

        $res = json_decode($response->getBody(), true);
        return $res["last_name"];
     }
    

     /********************************
     * 
     *  QUERIES
     * 
     ********************************/
    public function sendQuery($body){
        
        $response = $this->client->request(
            'POST', 
            $this->endpoint, 
            [
                'headers' => [
                    'Content-Type' => "application/json",
                ],
                'body' => $body
            ]
        );

        if($response->getStatusCode() === 200){
            echo "OK:\n";
            echo $response->getBody()."\n\n";
            return json_decode($response->getBody(), true);
        }else if($response->getStatusCode() === 400){
            echo "Bad request - Stack trace:\n";
            echo $response->getBody()."\n\n";
        }else if($response->getStatusCode() === 500){
            echo "Unknown Error - Stack trace:\n";
            $response->getBody()."\n\n";
        }
    }

    public function jsonSerialize($array){
        return json_encode($array);
    }

}