<?php

namespace App\Bot\Helpers;

use Bot;
use App\Bot\BotInstance;
use App\Bot\Attachments\Attachment;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class MessageHelper{

    var $channel;
    var $content;
    var $intent;


    public function __construct($channel, $content)
    {
        $this->channel = $channel;
        $this->content = $content;
        $this->intent = null;
    }

    public function setIntent($intent){
        $this->intent = $intent;
    }

    public function getReferral(){
        if(isset($this->content["postback"]["referral"]["ref"])){
            return $this->content["postback"]["referral"]["ref"];
        }else{
            return null;
        }
    }

    public function getType(){
        if($this->channel == "FACEBOOK"){
            if(isset($this->content["postback"])){
                return "POSTBACK";
            }else if(isset($this->content["message"])){
                if(isset($this->content["message"]["attachments"])){
                    return "ATTACHMENTS";
                }else if($this->content["message"]["text"] && !isset($this->content["message"]["quick_reply"])){
                    return "TEXT";
                }else if($this->content["message"]["text"] && $this->content["message"]["quick_reply"]){
                    return "QUICKREPLIES";
                }
            }else{
                return false;
            }
        }else if($this->channel == "WEB"){
            if($this->content["type"] == "postback"){
                return "POSTBACK";
            }else if(isset($this->content["message"])){
                if(isset($this->content["message"]["attachments"])){
                    return "ATTACHMENTS";
                }else if($this->content["type"] == "text"){
                    return "TEXT";
                }else if($this->content["type"] == "quick_reply"){
                    return "QUICKREPLIES";
                }
            }else{
                return false;
            }
        }
    }

    public function getText(){
        if(isset($this->content["message"]["text"])){
            if($this->channel == "FACEBOOK"){
                return $this->content["message"]["text"];
            }else if($this->channel == "WEB"){
                return $this->content["message"]["text"];
            }
        }else{
            return null;
        }
    }

    public function getQuickrepliesPayload(){
        if($this->channel == "FACEBOOK"){
            return $this->content["message"]["quick_reply"]["payload"];
        }else if($this->channel == "WEB"){
            return $this->content["message"]["payload"];
        }
    }

    public function getPostbackPayload(){
        if($this->channel == "FACEBOOK"){
            return $this->content["postback"]["payload"];
        }else if($this->channel == "WEB"){
            return $this->content["message"]["payload"];
        }
    }

    public function setPostbackPayload($payload){
        if($this->channel == "FACEBOOK"){
            $this->content["postback"]["payload"] = $payload;
        }else if($this->channel == "WEB"){
            $this->content["message"]["payload"] = $payload;
        }
    }

    public function getPayloadGuess(){
        if($this->channel == "FACEBOOK"){
            if(isset($this->content["postback"]["payload"])){
                return $this->content["postback"]["payload"];
            }else if(isset($this->content["message"]["quick_reply"]["payload"])){
                return $this->content["message"]["quick_reply"]["payload"];
            }else{
                return null;
            }
        }else if($this->channel == "WEB"){
            if(isset($this->content["message"]["payload"])){
                return $this->content["message"]["payload"];
            }else{
                return null;
            }
        }
    }

    public function processAttachments(){
        $medias = array(
            "types" => array(
                "image" => 0,
                "video" => 0,
                "audio" => 0,
                "file" => 0,
            ),
            "total" => 0,
        );

        foreach($this->content["message"]["attachments"] as $attachment){
            $type = $attachment["type"];
            $url = $attachment["payload"]["url"];

            $user_attachment = new Attachment($type, $url, $this->content["sender"]["id"]);
            $user_attachment->save();

            $medias["types"][$type]  += 1;
            $medias["total"] += 1;
        }

        return $medias;
    }

    public function processText($bot, $nlp, $userId){
        if($bot->getChannel() == "WEB"){
            $bot->setWebSourceType("text");
            $bot->setWebSourceContent($this->getText());
        }

        if($nlp !== false){
            $nlpResult = $nlp->sendQuery($this->getText(), $userId);
            $intent = $nlp->getIntent($nlpResult);
            $parameters = $nlp->getParams($nlpResult);


            $this->setIntent($intent);

            if($bot->functionExists($intent)){
                $bot->launchFunction($intent, array($bot, $parameters));
            }else{
                $bot->sendMessage("⚠️ Error ⚠️");
                $bot->sendMessage("Function \"". $intent ."\" doesn't exists");
                $bot->sendMessage("NLP parameters :\n ". json_encode($parameters));
            }
        }else{
            if($bot->functionExists(env("NLP_DISABLED_DEFAULT_FUNCTION"))){
                $bot->launchFunction(env("NLP_DISABLED_DEFAULT_FUNCTION"), array($bot));
            }else{
                $bot->sendMessage("⚠️ Error ⚠️");
                $bot->sendMessage("Function \"". env("NLP_DISABLED_DEFAULT_FUNCTION") ."\" doesn't exists");
            }
        }
    }

    public function processQuickReply($bot, $userId){
        $payload = $this->getQuickrepliesPayload();

        if($bot->getChannel() == "WEB"){
            $bot->setWebSourceType("quick_reply");
            $bot->setWebSourceContent($payload);
        }
        
        if(strstr($payload, "__")){
            $data = explode("__", $payload);
            $function = $data[0];
            $parameters = $data[1];
        }else{
            $function = $payload;
            $parameters = null;
        }

        $messageFile = $bot->messageExists($function);
        if($messageFile){
            
            $yamlContent = Yaml::parse(file_get_contents($messageFile));
            $message = $yamlContent['messages'][$function];

            $type = $this->getMessageType($message);
            $option = $this->getMessageOption($message);
            $nextMessages = $this->getMessageNext($message);

            if($option){
                $type .= $option;
            }

            if($type == "group"){
                if($nextMessages){
                    foreach($nextMessages as $nextMessage){
                        $this->processPostback($bot, $userId, $nextMessage);
                    }
                }
            }else{
                if($bot->functionExists($type)){
                    $bot->launchFunction($type, array($bot, $message, $parameters));
    
                    if($nextMessages){
                        foreach($nextMessages as $nextMessage){
                            $this->setPostbackPayload($nextMessage);
                            $this->processPostback($bot, $userId, $nextMessage);
                        }
                    }
                }else{
                    $bot->sendMessage("⚠️ Error ⚠️");
                    $bot->sendMessage("Function \"". $type ."\" doesn't exists");
                }
            }

        }else{
            $bot->sendMessage("⚠️ WARNING : Message referenced by \"". $function ."\" doesn't exists in wording files ⚠️");
        }
    }

    public function processPostback($bot, $userId, $forced = false){
        
        if($forced == false){
            $payload = $this->getPostbackPayload();
            if($bot->getChannel() == "WEB" && $forced == false){
                $bot->setWebSourceType("postback");
                $bot->setWebSourceContent($payload);
            }
        }else{
            $payload = $forced;
        }
        
        if(strstr($payload, "__")){
            $data = explode("__", $payload);
            $function = $data[0];
            $parameters = $data[1];
        }else{
            $function = $payload;
            $parameters = null;
        }

        $messageFile = $bot->messageExists($function);
        if($messageFile){
            
            $yamlContent = Yaml::parse(file_get_contents($messageFile));
            $message = $yamlContent['messages'][$function];

            $type = $this->getMessageType($message);
            $option = $this->getMessageOption($message);
            $nextMessages = $this->getMessageNext($message);

            if($option){
                $type .= $option;
            }

            if($type == "group"){
                if($nextMessages){
                    foreach($nextMessages as $nextMessage){
                        $this->processPostback($bot, $userId, $nextMessage);
                    }
                }
            }else{
                if($bot->functionExists($type)){
                    $bot->launchFunction($type, array($bot, $message, $parameters));
    
                    if($nextMessages){
                        foreach($nextMessages as $nextMessage){
                            $this->processPostback($bot, $userId, $nextMessage);
                        }
                    }
                }else{
                    $bot->sendMessage("⚠️ Error ⚠️");
                    $bot->sendMessage("Function \"". $type ."\" doesn't exists");
                }
            }

        }else{
            $bot->sendMessage("⚠️ WARNING : Message referenced by \"". $function ."\" doesn't exists in wording files ⚠️");
        }
    }
    
    public function getMessageType(array $message){
        switch($message["type"]){
            case "text":
                return "sendText";
            case "quickreplies":
                return "sendQuickReplies";
            case "buttons":
                return "sendButtons";
            case "image":
                return "sendImage";
            case "video":
                return "sendVideo";
            case "carousel":
                return "sendGeneric";
            case "group":
                return "group";
            default:
                return false;
        }
    }

    public function getMessageOption(array $message){
        if(isset($message["option"])){
            switch($message["option"]){
                case "random":
                    return "Random";
                case "queue":
                    return "Queue";
                default:
                    return false;
            }
        }else{
            return false;
        }
        
    }

    public function getMessageNext(array $message){
        if(isset($message["next_messages"])){
            return $message["next_messages"];
        }else{
            return false;
        }
        
    }

}