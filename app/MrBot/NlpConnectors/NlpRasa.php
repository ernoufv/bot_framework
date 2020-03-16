<?php

namespace App\Bot\NlpConnectors;

class NlpRasa
{

    var $endpoint;
    var $client;

    public function __construct()
    {
        $this->endpoint = env("RASA_ENDPOINT");
        $this->client = new \GuzzleHttp\Client();
    }

    public function sendQuery($input, $userId, $language = "fr"){

        $body = array(
            "text" => $input
        );

        $response = $this->client->request(
            'POST', 
            $this->endpoint, 
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ],
                'body' => json_encode($body)
            ]
        );

        $result = json_decode($response->getBody(), true);


        $result["intent"]["name"] = $result["intent"]["name"] == null ? "not-understood" : $result["intent"]["name"];

        $this->intent = $result["intent"]["name"];

        return $result;
    }

    public function getIntent(){
        return $this->intent;
    }

    public function getParams($result){
        return $result["entities"];
    }

}