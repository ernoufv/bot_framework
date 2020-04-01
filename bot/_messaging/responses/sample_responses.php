<?php

namespace Bot;

use App\Bot\Messages\Text;
use App\Bot\Messages\Image;
use App\Bot\Messages\Video;
use App\Bot\Messages\File;
use App\Bot\Messages\Audio;
use App\Bot\Messages\Templates\Generic;
use App\Bot\Messages\Templates\Button;

function get_started($bot, $param = null){
    //Retrieving the channel of your bot : ("FACEBOOK", "WEB")
    $channel = $bot->getChannel();
    //Retreiving the user firstname
    $firstname = $bot->userFirstname();
    //New instances of Text
    $msg1 = new Text("Hey ".$firstname.", welcome to your chatbot ! 🥳");
    $msg2 = new Text("This is a good day to discover your bot framework ! Isn't it ? 🤓");

    //Sending Messages to the user
    $bot->sendMessage($msg1);
    $bot->sendMessage($msg2);

    //Calling another of your functions
    genericTemplate($bot);
}

function menu($bot, $param = null){
    $bot->sendMessage("Pleased to see you again ! 😉");
    genericTemplate($bot);
}

function genericTemplate($bot, $param = null){
    $msg1 = new Text("Let's go ! 👇");
    
    //New instance of Generic Template
    $generic = new Generic();

    //Creating buttons for this Generic Template (Card 1)
    $buttonsCard1 = array();
    $buttonsCard1[] = new Button("Website", "https://www.google.com");

    $generic->addCard(
        "Create your bot with DialogFlow 🤖", //This is the title of your Card
        "I'm Google DialogFlow ready", //This is the subtitle of your Card
        "https://images.unsplash.com/photo-1485846234645-a62644f84728?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1340&q=80", //This is the image URL for your Card
        $buttonsCard1, //Pass the buttons array to your card
        "https://www.google.com", //This is a default Web Url when you click on the image of your card
        "TALL" //This is the size of the webview opened when you open the default action URL
    );

    //Creating buttons for this Generic Template (Card 2)
    $buttonsCard2 = array();
    $buttonsCard2[] = new Button("See medias", "more_medias");

    $generic->addCard(
        "Medias 🎬", //This is the title of your Card
        "Your chatbot can send images, videos, sounds, files !", //This is the subtitle of your Card
        "https://images.unsplash.com/photo-1485846234645-a62644f84728?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1340&q=80", //This is the image URL for your Card
        $buttonsCard2 //Pass the buttons array to your card
    );

    $buttonsCard3 = array();
    $buttonsCard3[] = new Button("Discover how !", "more_personalize");

    $generic->addCard(
        "Personalization 🙋‍♀️ 🙋‍♂️", //This is the title of your Card
        "Use directly profile attributes of your users", //This is the subtitle of your Card
        "https://images.unsplash.com/photo-1517732306149-e8f829eb588a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1352&q=80", //This is the image URL for your Card
        $buttonsCard3 //Pass the buttons array to your card
    );

    //You can define the aspect ratio of your Generic Template (default : horizontal)
    //$generic->setAspectRatio("horizontal"); //1:1.91 image aspect ration
    //$generic->setAspectRatio("square"); //1:1 image aspect ration

    //Sending Messages to the user
    $bot->sendMessage($msg1);
    $bot->sendMessage($generic);
}

function more_medias($bot, $param = null){
    //For text messages, you can also directly type in a string without creating a new instance of Text.
    $msg1 = "Très bien !";

    $msg2 = "Nous allons voir quels types de médias il est possible d'envoyer !";

    //But you must create the instance of Text if you want to attach Buttons or Quick Replies to your message.
    $msg3 = new Text("Quel type de média ?");
    $msg3->addQuickReply("Image 🏙", "media_image");
    $msg3->addQuickReply("Vidéo 🎬", "media_video");
    $msg3->addQuickReply("Son 🔊", "media_sound");
    $msg3->addQuickReply("Fichier 📑", "media_file");

    $msgStack = array($msg1, $msg2, $msg3);
    $bot->sendMessages($msgStack);
}

function more_personalize($bot, $param = null){
    $firstname = $bot->userFirstname();

    $msg1 = new Text($firstname.", vous l'avez peut-être pas remarqué, mais au début de notre conversation, je vous ai appelé par votre prénom !");

    $msg2 = new Text("C'est bien pratique pour personnaliser votre expérience !");
    $msg2->addQuickReply("Retour au menu", "menu");

    //You can also stack messages and send them at once
    $msgStack = array($msg1, $msg2);
    $bot->sendMessages($msgStack);
}

function media_image($bot, $param = null){
    $msg1 = "C'est parti pour une image 🙂";

    $msg2 = new Image("https://images.unsplash.com/photo-1462953491269-9aff00919695?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80");
    //You can also attach Quick Replies to medias
    $msg2->addQuickReply("Vidéo 🎬", "media_video");
    $msg2->addQuickReply("Son 🔊", "media_sound");
    $msg2->addQuickReply("Fichier 📑", "media_file");
    $msg2->addQuickReply("Retour au menu", "menu");

    $msgStack = array($msg1, $msg2);
    $bot->sendMessages($msgStack);
}

function media_video($bot, $param = null){
    $msg1 = "Alors, une vidéo ! 🤗";

    $msg2 = new Video("https://file-examples.com/wp-content/uploads/2017/04/file_example_MP4_640_3MG.mp4");
    $msg2->addQuickReply("Image 🏙", "media_image");
    $msg2->addQuickReply("Son 🔊", "media_sound");
    $msg2->addQuickReply("Fichier 📑", "media_file");
    $msg2->addQuickReply("Retour au menu", "menu");

    $msgStack = array($msg1, $msg2);
    $bot->sendMessages($msgStack);
}

function media_sound($bot, $param = null){
    $msg1 = "Voici un son 🔊";

    $msg2 = new Audio("https://file-examples.com/wp-content/uploads/2017/11/file_example_MP3_1MG.mp3");
    $msg2->addQuickReply("Image 🏙", "media_image");
    $msg2->addQuickReply("Vidéo 🎬", "media_video");
    $msg2->addQuickReply("Fichier 📑", "media_file");
    $msg2->addQuickReply("Retour au menu", "menu");

    $msgStack = array($msg1, $msg2);
    $bot->sendMessages($msgStack);
}

function media_file($bot, $param = null){
    $msg1 = "Et voilà un fichier 📑";

    $msg2 = new File("https://images.pexels.com/photos/2893960/pexels-photo-2893960.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260");
    $msg2->addQuickReply("Image 🏙", "media_image");
    $msg2->addQuickReply("Vidéo 🎬", "media_video");
    $msg2->addQuickReply("Son 🔊", "media_sound");
    $msg2->addQuickReply("Retour au menu", "menu");

    $msgStack = array($msg1, $msg2);
    $bot->sendMessages($msgStack);
}