# Framework guide

## Prologue

**Important Note :** There is no GUI to build you bot with this framework. You need development skills to develop your chatbot with this framework. It just simplifies development with pre-build functionnalities & connectors.

For now, there is two ways to create messages in your bot :
- Directly from code in conversational files (located in ./bot/_messaging/responses)
- With YAML structured files (located in ./bot/_messaging/messages)
    - Files must be names *WHAT_YOU_WANT*.**LANG_CODE**.yml

## Sending messages

### Index :
- [Text message](#Text%20message)
- [Quick replies](#Quick%20replies)

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

**Important notes, Quick replies restrictions :** 
- The payload **can't** be a web URL
- The message containing quickreplies **must** be the **last** message of a group of messages
    - This is because the quick replies buttons disapears when there are clicked, or if a messages is sent.
- Quick replies can only be attached to a text
- **Up to 10** quick replies can be attached to a message

