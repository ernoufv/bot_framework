<?php

namespace App\Bot;

define("WEB", 0);
define("FACEBOOK", 1);

use App\Bot\Messages\Text;
use App\Bot\Messages\Image;
use App\Bot\Messages\Video;
use App\Bot\Messages\File;
use App\Bot\Messages\Audio;
use App\Bot\Messages\Templates\Generic;

use App\Bot\Channels\FacebookQueryHelper;
use App\Bot\Channels\WebQueryHelper;

use Carbon\Carbon;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class BotInstance
{
    var $channel;
    var $userId;
    var $lang;

    //This variable is used only in Facebook Channel
    var $personaId;
    //-------------------------------------------

    //This variable is used only in Web Channel
    var $webResponse;
    //-------------------------------------------

    var $db;

    public function __construct($channel, $userId, $lang = null)
    {
        switch($channel){
            case WEB:
                $this->channel = "WEB";
                $this->webResponse = array(
                    "sender" => "bot",
                    "user_id" => $userId,
                    "source_type" => null,
                    "source" => null,
                    "messages" => array()
                );
                break;
            case FACEBOOK:
                $this->channel = "FACEBOOK";
                $this->personaId = null;
                break;
            default:
                $this->channel = "UNKNOWN";
            
            $this->intent = null;
        }
        
        $this->userId = $userId;
        $this->lang = $lang == null ? env("DEFAULT_LANG") : $lang;

        $this->db = app('db')->connection(env("DB_CONNECTION"));
    }

    public function getChannel(){
        return $this->channel;
    }

    public function setChannel($channel){
        $this->channel = $channel;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function setUserId($userId){
        $this->userId = $userId;
    }

    public function getLang(){
        return $this->lang;
    }

    public function setLang($lang){
        $this->lang = $lang;
    }

    public function setWebSourceType($sourceType){
        $this->webResponse["source_type"] = $sourceType;
    }

    public function setWebSourceContent($sourceContent){
        $this->webResponse["source"] = $sourceContent;
    }

    public function selectQueryHelper($channel){
        switch($channel){
            case "WEB":
                return new WebQueryHelper();
                break;
            case "FACEBOOK":
                return new FacebookQueryHelper();
                break;
            default:
                return "UNKNOWN";
        }
    }

    public function seen(){
        $queryHelper = $this->selectQueryHelper($this->channel);
        $queryHelper->seen($this->userId);
    }

    public function typing($action, $personaId = null){
        $queryHelper = $this->selectQueryHelper($this->channel);
        $queryHelper->typing($this->userId, $action, $personaId);
    }

    public function sendMessage($message, $personaId = null){
        
        if($this->channel == "FACEBOOK"){
            if($this->personaId !== null && $personaId === null){
                $personaId = $this->personaId;
            }
    
            if(is_string($message)){
                $message = new Text($message);
            }
            
    
            sleep(0.5);
            $this->typing(true, $personaId);
            sleep(1);
    
            $queryHelper = $this->selectQueryHelper($this->channel);
            if($message instanceof Text){
                $result = $queryHelper->sendText($this->userId, $message, $personaId);    
            }else if($message instanceof Generic){
                $result = $queryHelper->sendGeneric($this->userId, $message, $personaId);    
            }else if($message instanceof Image){
                $result = $queryHelper->sendImage($this->userId, $message, $personaId);    
            }else if($message instanceof Video){
                $result = $queryHelper->sendVideo($this->userId, $message, $personaId);    
            }else if($message instanceof File){
                $result = $queryHelper->sendFile($this->userId, $message, $personaId);    
            }else if($message instanceof Audio){
                $result = $queryHelper->sendAudio($this->userId, $message, $personaId);
            }

            if(isset($result["message_id"])){
                $log = new Log();
                $log->logBotMessage($this, $message, $result["message_id"]);
            }
            
            $this->typing(false, $personaId);
            return $result;
        }else if($this->channel == "WEB"){
            if(is_string($message)){
                $message = new Text($message);
            }
            
            $queryHelper = $this->selectQueryHelper($this->channel);
            if($message instanceof Text){
                $this->webResponse["messages"][] = $queryHelper->sendText($this->userId, $message);    
            }else if($message instanceof Generic){
                $this->webResponse["messages"][] = $queryHelper->sendGeneric($this->userId, $message);    
            }else if($message instanceof Image){
                $this->webResponse["messages"][] = $queryHelper->sendImage($this->userId, $message);    
            }else if($message instanceof Video){
                $this->webResponse["messages"][] = $queryHelper->sendVideo($this->userId, $message);    
            }else if($message instanceof File){
                $this->webResponse["messages"][] = $queryHelper->sendFile($this->userId, $message);    
            }else if($message instanceof Audio){
                $this->webResponse["messages"][] = $queryHelper->sendAudio($this->userId, $message);    
            }

            $log = new Log();
            $log->logBotMessage($this, $message);
        }
    }

    public function sendWebResponse(){
        $queryHelper = $this->selectQueryHelper($this->channel);
        return $queryHelper->jsonSerialize($this->webResponse);
    }

    public function sendMessages($messages, $personaId = null){
        if(is_array($messages)){
            foreach($messages as $message){
                $this->sendMessage($message, $personaId);
            }
        }
    }

    public function userFirstname(){
        $queryHelper = $this->selectQueryHelper($this->channel);
        return $queryHelper->getUserFirstname($this->userId); 
    }

    public function userLastname(){
        $queryHelper = $this->selectQueryHelper($this->channel);
        return $queryHelper->getUserLastname($this->userId); 
    }

    public function messageExists($name){
        $lang = $this->getLang();

        try{
            $files = glob("../_messaging/messages/*.".$lang.".yml");

            if ($files === false) {
                throw new RuntimeException("Failed to glob for messages files");
            }else{
                foreach($files as $file){
                    $yamlContent = Yaml::parse(file_get_contents($file));
                    if(isset($yamlContent['messages'][$name])){
                        return $file;
                    }
                }
                return false;
            }
        }catch(Exception $e){
            printf($e);
        }
    }

    public function functionExists($name){
        $name = str_replace("-", "_", $name);
        if(function_exists('\Bot\\'.$name)){
            return true;
        }else{
            return false;
        }
    }

    public function launchFunction($name, array $params){
        $name = str_replace("-", "_", $name);
        call_user_func_array(
            '\Bot\\'.$name, 
            $params
        );
    }

    public function userExists(){
        $this->db->table('users')
        ->updateOrInsert(
            [
                'user_id' => $this->userId, 
                'firstname' => $this->userFirstname(),
                'lastname' => $this->userLastname(),
                'channel' => $this->getChannel()
            ]
        );
    }

    public function hasFlag(){
        $result = $this->db->table('users')->where('user_id', $this->userId)->whereNotNull('flag')
        ->select('flag', 'flag_data')->get();

        if(isset($result[0])){
            return json_decode(json_encode($result[0]), true);
        }else{
            return false;
        }

        
    }

    public function createFlag($flaggedFunction, $flagData = null){
        $this->db->table('users')->where('user_id', $this->userId)
        ->update(
            [
                'firstname' => $this->userFirstname(),
                'lastname' => $this->userLastname(),
                'channel' => $this->getChannel(),
                'flag' => $flaggedFunction,
                'flag_data' => $flagData,
                'flag_date' => Carbon::now()->toDateTimeString()
            ]
        );
    }

    public function deleteFlag(){
        $this->db->table('users')->where('user_id', $this->userId)
        ->update(
            [
                'flag' => null,
                'flag_data' => null,
                'flag_date' => null
            ]
        );
    }

    public function sendFlagInput(){
        $message = new Text(env("FLAG_DEFAULT_TEXT"));
        $message->addQuickReply(env("DISCARD_FLAG_TEXT"), env("DISCARD_FLAG_FUNCTION"));
        $this->sendMessage($message);
    }


}