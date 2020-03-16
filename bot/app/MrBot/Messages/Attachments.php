<?php

namespace App\Bot\Messages;

class Attachments{

    var $db; 
    var $endpoint;
    var $client;
    var $url;
    var $type;

    public function __construct($url = null, $type = null){
        $this->url = $url;
        $this->type = $type;
        $this->db = app('db')->connection(env("DB_CONNECTION"));
        $this->endpoint = "https://graph.facebook.com/v".env("FACEBOOK_API_VERSION")."/me/message_attachments?access_token=".env("PAGE_ACCESS_TOKEN");
        $this->client = new \GuzzleHttp\Client();
    }

    public function exists(){
        $res = $this->db->table('bot_attachments')->where(
            [
                'bot_url' => $this->url
            ]
        )->select('attachment_id')->get();
        if(isset($res[0])){
            if(isset($res[0]->attachment_id)){
                return $res[0]->attachment_id;
            }else{
                return $this->upload();
            }
        }else{
            return $this->upload();
        }
    }

    public function upload(){

        echo "UPLOADING";


        $body = array(
            "message" => array(
                "attachment" => array(
                    "type" => $this->type,
                    "payload" => array(
                        "is_reusable" => true,
                        "url" => $this->url
                    )
                )
            )
        );
        
        $response = $this->client->request(
            'POST', 
            $this->endpoint,
            [
                'headers' => [
                    'Content-Type' => "application/json",
                ],
                'body' => $this->jsonSerialize($body)
            ]
        );

        $res = json_decode($response->getBody(), true);

        $this->db->table('bot_attachments')->insert(
            [
                "bot_url" => $this->url,
                "attachment_id" => $res["attachment_id"],
                "type" => $this->type
            ]
        );

        return $res["attachment_id"];
    }

    public function jsonSerialize($array){
        return json_encode($array);
    }

}