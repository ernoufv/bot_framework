<?php

namespace App\Bot\NlpConnectors;

class NlpSAPCAI
{

    var $endpoint;
    var $client;
    
    var $intent;

    public function __construct()
    {
        $this->endpoint = "https://api.cai.tools.sap/build/v".env("SAP_CAI_API_VERSION")."/dialog";
        $this->client = new \GuzzleHttp\Client();
    }

    public function sendQuery($input, $userId, $language = "fr"){

        $body = array(
            "message" => array(
                "content" => $input,
                "type" => "text"
            ),
            "conversation_id" => $userId,
            "language" => $language
        );

        $response = $this->client->request(
            'POST', 
            $this->endpoint, 
            [
                'headers' => [
                    'Content-Type' => "application/json",
                    'Authorization' => 'Token '.env("SAP_CAI_TOKEN")
                ],
                'body' => json_encode($body)
            ]
        );

        $result = json_decode($response->getBody(), true);

        $result["results"]["nlp"]["intents"][0]["slug"] = count($result["results"]["nlp"]["intents"]) == 0 ? "not-understood" : $result["results"]["nlp"]["intents"][0]["slug"];

        $this->intent = $result["results"]["nlp"]["intents"][0]["slug"];

        return $result;
    }

    public function getIntent(){
        return $this->intent;
    }

    public function getParams($result){
        return $result["results"]["nlp"]["entities"];
    }

}