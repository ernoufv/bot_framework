<?php

namespace App\Bot\Helpers;

class PersonaHelper
{

    var $request;
    var $endpoint;
    var $client;

    public function __construct($request)
    {
        $this->endpoint = "https://graph.facebook.com/v".env("FACEBOOK_API_VERSION")."/me/personas?access_token=".env("PAGE_ACCESS_TOKEN");
        $this->client = new \GuzzleHttp\Client();
        $this->request = $request;
    }

    public function createPersona()
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
            $res_array = json_decode($response->getBody(), true);
            $path = base_path('.env');
            if (file_exists($path)) {
                $current = file_get_contents($path);

                $new = $current."\nPERSONA_".strtoupper($body["name"])."=".$res_array["id"];
                $new = str_replace(" ", "_", $new);
                file_put_contents($path, $new);
            }

            return response($response->getBody(), 200)->header('Content-Type', 'application/json');
        }else if($response->getStatusCode() === 400){
            echo "Bad request - Stack trace:\n";
            echo $response->getBody()."\n\n";
        }else if($response->getStatusCode() === 500){
            echo "Unknown Error - Stack trace:\n";
            $response->getBody()."\n\n";
        }

        
    }

    public function retrievePersonas()
    {
        $body = $this->request->all();
        $response = $this->client->request(
            'GET', 
            $this->endpoint
        );

        if($response->getStatusCode() === 200){
             return response($response->getBody(), 200)->header('Content-Type', 'application/json');
        }else if($response->getStatusCode() === 400){
            echo "Bad request - Stack trace:\n";
            echo $response->getBody()."\n\n";
        }else if($response->getStatusCode() === 500){
            echo "Unknown Error - Stack trace:\n";
            $response->getBody()."\n\n";
        }
    }

    public function deletePersona($personaId)
    {
        $endpoint = str_replace("v".env("FACEBOOK_API_VERSION")."/me/personas", $personaId, $this->endpoint);
        $body = $this->request->all();
        $response = $this->client->request(
            'DELETE', 
            $endpoint
        );

        if($response->getStatusCode() === 200){
            return response($response->getBody(), 200)->header('Content-Type', 'application/json');
        }else if($response->getStatusCode() === 400){
            echo "Bad request - Stack trace:\n";
            echo $response->getBody()."\n\n";
        }else if($response->getStatusCode() === 500){
            echo "Unknown Error - Stack trace:\n";
            $response->getBody()."\n\n";
        }
    }

}