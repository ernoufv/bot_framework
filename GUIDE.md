# Development guide

## Prologue

For now, there is two ways to create messages in your bot :

- Directly from code in conversational files (located in ./bot/_messaging/responses)
- With YAML structured files (located in ./bot/_messaging/messages)
    - Files must be names *NAME*.**LANGUAGE_CODE**.yml

Files are autoloaded, so you can create them directly in these directories.

You can mix these two methods but YAML structured messages takes advantage on PHP Coded messages if an action exists in the two methods. 

## Sending messages

> Note : Each representation below in PHP Coding & YAML generates the same output.

### Index :

#### Messages templates :
- [Text message](#text-message-)
- [Image](#image-)
- [Video](#video-)
- [Quick replies](#quick-replies-)
- [Buttons](#buttons-)
- [Generic template (carousel)](#generic-template--carousel)

#### Grouping :
- [Group of messages](#group-of-messages-)

#### Parameters :
- [Actions parameters](#actions-parameters-)

---

### **Text message :**

<table>
<tr>
<td> PHP Coding </td>
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
    $bot->sendMessages(
        [
            $text1,
            $text2
        ]
    );
}
```

</td>
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
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

---

### **Image :**

<table>
<tr>
<td> PHP Coding </td>
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
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
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

---

### **Video :**

<table>
<tr>
<td> PHP Coding </td>
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
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
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

---

### **Quick replies :**

<table>
<tr>
<td> PHP Coding </td>
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
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
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

---

### **Buttons :**

<table>
<tr>
<td> PHP Coding </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    $msg = new Text("This messages contrains buttons");
    $msg->addButton("Button Label", "actionName");
    $msg->addButton("Button Label 2", "https://www.google.com");

    $bot->sendMessage($msg);
}
```

</td>
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
<td>

```yaml
actionName:
    type: buttons
    text: This messages contrains buttons
    buttons:
        -
            label: Button Label
            payload: actionName
        -
            label: Button Label 2
            payload: https://www.google.com
```

</td>
</table>

**Buttons restrictions :** 
- Buttons can only be attached to a text
- **Up to 3** buttons can be attached to a text message

---

### **Generic template :** (carousel)

**A generic template is a carousel of cards that contains different types of data :**
- An image (optional)
- A title
- A description (optional)
- A default action URL (optional)
- Up to 3 buttons (optional)

**Note** - At least :
- Title + description (if image not defined)
- Image + title (if description not defined)

<table>
<tr>
<td> PHP Coding </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    $carousel = new Generic();

    $carsouel->addCard(
        "Card 1 title",
        "Card 1 description",
        "https://picsum.photos/500/300",
        array(
            new Button("Button label 1", "actionName"),
            new Button("Button label 2", "https://www.google.com"),
        ),
        "https://www.default_url.com"
    );

    $carsouel->addCard(
        "Card 2 title",
        "Card 2 description",
        "https://picsum.photos/500/300",
        array(
            new Button("Button label 1", "actionName"),
            new Button("Button label 2", "https://www.google.com"),
        ),
        "https://www.default_url.com"
    );

    //Optional, if you want to randomize cards order
    // $carousel->shuffleCards();

    $bot->sendMessage($carousel);

}
```

</td>
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
<td>

```yaml
actionName:
    type: carousel
    #option: random (optional, if you want to randomize cards order)
    cards: 
        -
            image: https://picsum.photos/500/300
            title: Card 1 title
            description: Card 1 description
            default_url: https://www.default_url.com
            buttons:
                -
                    label: Button label 1
                    payload: actionName
                -
                    label: Button label 2
                    payload: https://www.google.com
        -
            image: https://picsum.photos/500/300
            title: Card 2 title
            description: Card 2 description
            default_url: https://images.google.com
            buttons:
                -
                    label: Button label 1
                    payload: actionName
                -
                    label: Button label 2
                    payload: https://www.google.com
```

</td>
</table>

---

### **Group of messages :**

<table>
<tr>
<td> PHP Coding </td>
</tr>
<tr>
<td>

```php
function groupActionName($bot, $param = null){

    actionName1($bot, $param);
    actionName2($bot, $param);

}
```

</td>
</tr>
</table>
<table>
<tr>
<td> YAML </td>
</tr>
<tr>
<td>

```yaml
groupActionName:
    type: group
    next_messages:
        - actionName1
        - actionName2
```

</td>
</table>

**Note that `next_messages` in YAML interpretation can be used in every message type to define a suite of messages which have different template** 

**Eg :**

<table>
<tr>
<td> YAML </td>
</tr>
<tr>
<td>

```yaml
actionName:
    type: text
    text: I will send you other actions
    next_messages:
        - sendDogVideo
        - sendCatImage
```

</td>
</table>

---

### **Actions parameters :**

All actions calls can have a parameter. To use parameters, we highly recommand to prefer PHP Coding in order to exploit them in responses. 

You can pass parameters each time you call an action in your bot. You just have to use it like this :
- Case you pass a payload in buttons/quickreplies :
    - Use a "double underscore" string `__` between action that need to be called and your string parameter

**Note :** When action is triggered with NLP solution, parameters are automatically completed with NLP parameters (eg: numbers in user sentence, context words detected, ..)

<table>
<tr>
<td> PHP Coding </td>
</tr>
<tr>
<td>

```php
function actionName($bot, $param = null){

    $text = new Text("I think I can guess your name !");
    $text->addQuickReply("Uh ? Ok !", "actionNameWithPassedParameter__John Doe");

    $bot->sendMessage($text);

}

function actionNameWithPassedParameter($bot, $param = null){

    $name = $param;
    $text = new Text("Your name is : ". $name); // Your name is John Doe

    $bot->sendMessage($text);

}
```

</td>
</tr>
</table>