<?php

namespace App\Bot\NlpConnectors;

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;

class NlpDialogflow
{

    var $projectId;

    var $intent;

    public function __construct()
    {
        $this->projectId = env("DIALOGFLOW_PROJECT_ID");
    }

    public function sendQuery($input, $userId, $language = "fr-FR"){

        // new session
        $keyfile = array('credentials' => env("DIALOGFLOW_CLIENT_SECRET_PATH"));
        $sessionsClient = new SessionsClient($keyfile);
        $session = $sessionsClient->sessionName($this->projectId, $userId ?: uniqid());

        // create text input
        $textInput = new TextInput();
        $textInput->setText($input);
        $textInput->setLanguageCode($language);

        // create query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        // get response and relevant info
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();
        $intent = $queryResult->getIntent();
        $parameters = $queryResult->getParameters();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfillments = $queryResult->getFulfillmentMessages();

        $p = $parameters->serializeToJsonString();

        foreach($fulfillments as $fulfillment){
            $f[] = json_decode($fulfillment->serializeToJsonString(), true);
        }

        $result['intent'] = $displayName;
        $result['params'] = json_decode($p, true);
        $result['confidence'] = $confidence;
        $result['fulfillments'] = $f;

        $sessionsClient->close();

        $this->intent = $result["intent"];
        return $result;
        
    }

    public function getIntent(){
        return $this->intent;
    }

    public function getParams($result){
        return $result["params"];
    }

    

}