<?php

namespace App\Bot\Helpers;

use Bot;
use App\Bot\BotInstance;
use App\Bot\Log;
use App\Bot\Messages\Text;
use App\Bot\NlpConnectors\NlpSAPCAI;
use App\Bot\NlpConnectors\NlpDialogflow;
use App\Bot\NlpConnectors\NlpLUIS;
use App\Bot\NlpConnectors\NlpRasa;

class EventHelper
{

    var $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function eventHelper()
    {
        $body = $this->request->all();
        $channel = $this->guessChannel($body);
        $log = new Log();

        if($channel == "WEB"){
            $message = $body;
            $userId = $body["user_id"];

            $bot = new BotInstance(WEB, $userId);

        }else if($channel == "FACEBOOK"){
            $message = $body["entry"][0]["messaging"][0];
            $userId = $message["sender"]["id"];
            
            $bot = new BotInstance(FACEBOOK, $userId);

            //$this->respondOK();
            if(env("APP_DEBUG") !== true){
                $this->respondOK();
            }

            if(isset($message['message']['is_echo']) || isset($message["delivery"]) || isset($message["read"])){
                return response("OK", 200);
            }

            $bot->seen();
        }

        $bot->userExists();
        
        $messageHelper = new MessageHelper($channel, $message);

        switch($messageHelper->getType()){
            case "TEXT":
                $flag = $bot->hasFlag();
                if($flag !== false && $bot->functionExists($flag["flag"])){
                    $bot->deleteFlag();
                    $bot->launchFunction($flag["flag"], array(
                        $bot, 
                        array(
                            "flag_data" => $flag["flag_data"], 
                            "flag_user_input" => $messageHelper->getText()
                        )
                    ));
                }else{
                    $nlp = $this->initNLP();
                    $messageHelper->processText($bot, $nlp, $userId);
                    $log->logUserMessage($bot, $message, $nlp);
                }
                break;
            case "QUICKREPLIES":
                $log->logUserMessage($bot, $message);
                $messageHelper->processQuickReply($bot, $userId);
                break;
            case "POSTBACK":
                if($channel == "FACEBOOK"){
                    $referral = $messageHelper->getReferral();
                }else{
                    $referral = null;
                }

                if($referral != null){
                    $log->logUserMessage($bot, $message);
                    $bot->launchFunction($message["postback"]["referral"]["ref"], array(
                        $bot
                    ));
                }else{
                    $log->logUserMessage($bot, $message);
                    $messageHelper->processPostback($bot, $userId);
                }
                break;
            case "ATTACHMENTS":
                $attachments = $messageHelper->processAttachments();
                $bot->sendMessage(env("DEFAULT_USER_MEDIA_RECEIVED"));
                if(env("LIST_SENT_ATTACHMENTS") == true){
                    $attch_message = "";
                    $attachments["types"]["image"] > 0 ? $attch_message .= "🌄" : $attch_message .= "";
                    $attachments["types"]["video"] > 0 ? $attch_message .= "🎬" : $attch_message .= "";
                    $attachments["types"]["audio"] > 0 ? $attch_message .= "🔊" : $attch_message .= "";
                    $attachments["types"]["file"] > 0 ? $attch_message .= "📑" : $attch_message .= "";
                    $bot->sendMessage($attch_message);
                }
                break;
            default:
                if(isset($message["referral"])){
                    $bot->launchFunction($message["referral"]["ref"], array(
                        $bot
                    ));
                }
                break;
        }
        
        if($channel == "WEB"){
            if($bot->hasFlag()){
                $bot->sendFlagInput($bot);
            }
            return response(
                $bot->sendWebResponse(), 
                200
            )->header('Content-Type', 'application/json');
        }else if($channel == "FACEBOOK"){
            if($bot->hasFlag()){
                $bot->sendFlagInput();
            }
            return "OK";
        }else{
            return response(
                array("error" => array("message" => "This request/channel is not handled by Bot Framework.")), 
                400
            )->header('Content-Type', 'application/json');
        }

    }

    public function guessChannel($body){
        if(isset($body["entry"])){
            return "FACEBOOK";
        }else{
            return "WEB";
        }
    }

    public function fbChallengeEvent(){
        $headers = $this->request->header();
        $body = $this->request->all();

        if ($this->request->input("hub_mode") === "subscribe"
        && $this->request->input("hub_verify_token") === env("APP_VERIFICATION_TOKEN")) {
            return response($this->request->input("hub_challenge"), 200);
        }
    }

    public function initNLP(){
        if(env("NLP_SAP_CAI") == true){
            return new NlpSAPCAI();
        }else if(env("NLP_DIALOGFLOW") == true){
            return new NlpDialogflow();
        }else if(env("NLP_RASA") == true){
            return new NlpRasa();
        }else if(env("NLP_LUIS") == true){
            return new NlpLUIS();
        }else if(env("NLP_DISABLED") == true){
            return false;
        }else{
            return false;
        }
    }

    public function respondOK($text = null)
    {
        // check if fastcgi_finish_request is callable
        if (is_callable('fastcgi_finish_request')) {
            if ($text !== null) {
                echo $text;
            }
            /*
            * http://stackoverflow.com/a/38918192
            * This works in Nginx but the next approach not
            */
            session_write_close();
            fastcgi_finish_request();
    
            return;
        }
    
        ignore_user_abort(true);
    
        ob_start();
    
        if ($text !== null) {
            echo $text;
        }
    
        $serverProtocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
        header($serverProtocol . ' 200 OK');
        // Disable compression (in case content length is compressed).
        header('Content-Encoding: none');
        header('Content-Length: ' . ob_get_length());
    
        // Close the connection.
        header('Connection: close');
    
        ob_end_flush();
        ob_flush();
        flush();
    }
    

}