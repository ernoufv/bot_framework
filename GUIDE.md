# Framework guide

## Prologue

**Important Note :** There is no GUI to build you bot with this framework. You need development skills to develop your chatbot with this framework. It just simplifies development with pre-build functionnalities & connectors.

For now, there is two ways to create messages in your bot :
- Directly from code in conversational files (located in ./bot/_messaging/responses)
- With YAML structured files (located in ./bot/_messaging/messages)
    - Files must be names *NAME*.**LANGUAGE_CODE**.yml

## Sending messages

### Index :
- [Text message](#text-message)
- [Image](#image)
- [Video](#video)
- [Quick replies](#quick-replies)
- [Buttons](#buttons)

### Text message :

<table>
<tr>
<td> PHP Coding </td> <td> YAML </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    //Send a simple message
    $text = new Text("Hi there 👋 I'm a text message !");
    $bot->sendMessage($text);

    //Send a random text from an array of texts
    $texts = array(
        "Hi there 👋",
        "Hello 👋",
        "Hi ! 👋"
    );
    $randomText = array_rand($texts);
    $textToSend = $texts[$randomText];
    $bot->sendMessage($textToSend);

    //Send multiple messages at once
    $text1 = new Text("Hi there 👋");
    $text2 = new Text("I'm a text message !");
    $bot->sendMessage(
        [
            $text1,
            $text2
        ]
    );
}
```

</td>
<td>

```yaml
# Send a simple text
actionName:
    type: text
    text: Hi there 👋 I'm a text message !

# Send random text from a list of texts
actionName:
    type: text
    option: random
    text: 
        - Hi there 👋
        - Hello 👋
        - Hi ! 👋

# Send multiple messages at one
actionName:
    type: text
    option: queue
    text: 
        - Hi there 👋
        - I'm a text message

```

</td>
</tr>
</table>

### Image :

<table>
<tr>
<td> PHP Coding </td> <td> YAML </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    $image = new Image("https://picsum.photos/500/300");
    $bot->sendMessage($image);

}
```

</td>
<td>

```yaml
actionName:
    type: image
    url: https://picsum.photos/500/300

```

</td>
</tr>
</table>

**Images restriction :**
- **Up to 25MB** (Facebook Messenger only)
- Formats : JPEG|PNG|GIF

**Information :** When an image is sent to Facebook Messenger, it's binary data are saved in Facebook CDN and returns an "Attachment ID". This attachment ID is saved in bot database to be reused and avoid image re-uploading each time the action is triggered.

### Video :

<table>
<tr>
<td> PHP Coding </td> <td> YAML </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    $video = new Video("https://file-examples.com/wp-content/uploads/2017/04/file_example_MP4_1280_10MG.mp4");
    $bot->sendMessage($video);

}
```

</td>
<td>

```yaml
actionName:
    type: video
    url: https://file-examples.com/wp-content/uploads/2017/04/file_example_MP4_1280_10MG.mp4
```

</td>
</tr>
</table>

**Videos restriction :**
- **Up to 25MB** (Facebook Messenger only)
- Formats : MP4|MOV

**Information :** When a video is sent to Facebook Messenger, it's binary data are saved in Facebook CDN and returns an "Attachment ID". This attachment ID is saved in bot database to be reused and avoid video re-uploading each time the action is triggered.

### Quick replies :

<table>
<tr>
<td> PHP Coding </td> <td> YAML </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    $msg = new Text("This messages contrains quick replies");
    $msg->addQuickReply("QR Label", "actionName");
    $msg->addQuickReply("QR Label 2", "actionName2");

    $bot->sendMessage($msg);
}
```

</td>
<td>

```yaml
actionName:
    type: text
    text: This messages contrains quick replies
    quickreplies:
        -
            label: QR Label
            payload: actionName
        -
            label: QR Label 2
            payload: actionName2

```

</td>
</table>

**Quick replies restrictions :** 
- The payload **can't** be a Web URL, only a bot action
- The message containing quickreplies **must** be the **last** message of a group of messages
    - This is because the quick replies buttons disapears when there are clicked, or if a messages is sent.
- Quick replies is an extension that can only be attached to a text, an image & or a video
- **Up to 10** quick replies can be attached to a message

