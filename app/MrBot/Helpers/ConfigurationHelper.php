<?php

namespace App\Bot\Helpers;

class ConfigurationHelper
{

    var $request;
    var $endpoint;
    var $client;

    public function __construct($request)
    {
        $this->endpoint = "https://graph.facebook.com/v".env("FACEBOOK_API_VERSION")."/me/messenger_profile?access_token=".env("PAGE_ACCESS_TOKEN");
        $this->client = new \GuzzleHttp\Client();
        $this->request = $request;
    }

    public function fbBotConfiguration()
    {
        $body = $this->request->all();
        $response = $this->client->request(
            'POST', 
            $this->endpoint, 
            [
                'headers' => [
                    'Content-Type' => "application/json",
                ],
                'body' => json_encode($body)
            ]
        );

        if($response->getStatusCode() === 200){
            echo $response->getBody();
        }else if($response->getStatusCode() === 400){
            echo "Bad request - Stack trace:\n";
            echo $response->getBody()."\n\n";
        }else if($response->getStatusCode() === 500){
            echo "Unknown Error - Stack trace:\n";
            $response->getBody()."\n\n";
        }
    }

}