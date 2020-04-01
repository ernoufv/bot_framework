<?php

namespace App\Bot;

use App\Bot\Helpers\MessageHelper;
use Illuminate\Database\Eloquent\Model;

class Log extends Model{

    var $db;

    public function __construct()
    {
        $this->db = app('db')->connection(env("DB_CONNECTION"));
    }

    public function logUserMessage($bot, $message, $nlp = null){
        $messageHelper = new MessageHelper($bot->getChannel(), $message);
        $this->db->table('logs')
        ->insert(
            [
                'user_id' => $bot->getUserId(), 
                'channel' => $bot->getChannel(),
                'sender' => 'user',
                'text' => $messageHelper->getText(),
                'type' => strtolower($messageHelper->getType()),
                'payload' => $messageHelper->getPayloadGuess(),
                'message' => json_encode($message, JSON_UNESCAPED_UNICODE),
                'intent' => $nlp ? $nlp->getIntent() : $messageHelper->getPayloadGuess()
            ]
        );
    }

    public function logBotMessage($bot, $message, $messageId = null){
        $messageHelper = new MessageHelper($bot->getChannel(), $message);
        $type_long = get_class($message);
        $type = new \ReflectionClass($type_long);
        $message_payload = null;
        $message_text = null;
        if(isset($message->text)){
            $message_text = $message->text;
        }else if(isset($message->image)){
            $message_payload = $message->image;
        }else if(isset($message->audio)){
            $message_payload = $message->audio;
        }
        $this->db->table('logs')
        ->insert(
            [
                'user_id' => $bot->getUserId(), 
                'channel' => $bot->getChannel(),
                'sender' => 'bot',
                'type' => strtolower($type->getShortName()),
                'text' => $message_text,
                'payload' => $message_payload,
                'message' => json_encode($message, JSON_UNESCAPED_UNICODE),
            ]
        );
    }

}