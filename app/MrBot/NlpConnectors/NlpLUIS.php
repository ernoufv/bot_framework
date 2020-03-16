<?php

namespace App\Bot\NlpConnectors;

class NlpLUIS
{

    var $endpoint;
    var $client;
    
    var $intent;

    public function __construct()
    {
        $this->endpoint = "https://"
            .env("LUIS_REGION").
        ".api.cognitive.microsoft.com/luis/v"
            .env("LUIS_API_VERSION").
        "/apps/"
            .env("LUIS_APPID").
        "?verbose="
            .env("LUIS_VERBOSE").
        "&timezoneOffset=0&subscription-key="
            .env("LUIS_SUBSCRIPTION_KEY").
        "&q=bonjour";
        $this->client = new \GuzzleHttp\Client();
    }

    public function sendQuery($input, $userId, $language = "fr"){


        $response = $this->client->request(
            'GET', 
            $this->endpoint, 
            [
                'headers' => [
                    'Content-Type' => "application/json"
                ]
            ]
        );

        $result = json_decode($response->getBody(), true);

        $result["intent"]["name"] = $result["topScoringIntent"]["intent"] == "None" ? "not-understood" : $result["topScoringIntent"]["intent"];

        $this->intent = $result["topScoringIntent"]["intent"];

        return $result;
    }

    public function getIntent(){
        return $this->intent;
    }

    public function getParams($result){
        return $result["entities"];
    }

}