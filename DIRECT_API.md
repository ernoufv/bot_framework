
# Direct API - Endpoints

<hr/>

### Format de la réponse

API returns a JSON composed of :
- ***source_type*** : type of source message
- ***source*** : source data of the text or payload
- ***sender*** : bot
- ***user_id*** : user ID
- ***messages*** : array of *messages*. Each *message* contains the key ***type*** (*text*, *carousel*, *image*, *quick_replies* ou *buttons*) and a key ***content*** that contains message content

### Response example :


```
{
    "source_type": "text",
    "source": "hello",
    "sender": "bot",
    "user_id": "<USER_ID>",
    "messages": [
        {
            "type": "text",
            "content": {
                "text_message": "Hi !"
            }
        }
    ]
}
``` 

<hr/>

## Indices

* [Default](#default)

  * [Quick Reply](#1-quick-reply)
  * [Postback](#2-postback)
  * [Text](#3-text)


--------


## Default



### 1. Quick Reply



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://yourURL/messaging
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
    "user_id": "<USER_ID>",
    "type": "quick_reply",
    "message":
    {
    	"payload": "actionName",
    	"title":"Action label"
    }
}
```



***Responses:***


Status: OK | Code: 200



***Response Headers:***

| Key | Value |
| --- | ------|
| Date | Fri, 14 Jun 2019 16:06:58 GMT |
| Server | Apache |
| Connection | close |
| Transfer-Encoding | chunked |
| Content-Type | application/json |



```js
{
    "source_type": "quick_reply",
    "source": "actionName",
    "sender": "bot",
    "user_id": "<USER_ID>",
    "messages": [
        {
            "type": "text",
            "content": {
                "text": "Action was triggered !"
            }
        }
    ]
}
```



### 2. Postback



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://yourURL/messaging
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
    "user_id": "<USER_ID>",
    "type": "postback",
    "message":
    {
    	"payload": "getStarted",
    	"title": "Get Started"
    }
}
```



***Responses:***

Status: OK | Code: 200



***Response Headers:***

| Key | Value |
| --- | ------|
| Date | Fri, 14 Jun 2019 16:04:50 GMT |
| Server | Apache |
| Connection | close |
| Transfer-Encoding | chunked |
| Content-Type | application/json |



```js
{
    "source_type": "postback",
    "source": "getStarted",
    "sender": "bot",
    "user_id": "<USER_ID>",
    "messages": [
        {
            "type": "text",
            "content": {
                "text": "Welcome !"
            }
        }
    ]
}
```



### 3. Text



***Endpoint:***

```bash
Method: POST
Type: RAW
URL: https://yourURL/messaging
```


***Headers:***

| Key | Value | Description |
| --- | ------|-------------|
| Content-Type | application/json |  |



***Body:***

```js        
{
    "user_id": "<USER_ID>",
    "type": "text",
    "message":
    {
    	"text": "hello"
    }
}
```



***Responses:***


Status: OK | Code: 200



***Response Headers:***

| Key | Value |
| --- | ------|
| Date | Fri, 14 Jun 2019 16:04:04 GMT |
| Server | Apache |
| Connection | close |
| Transfer-Encoding | chunked |
| Content-Type | application/json |



```js
{
    "source_type": "text",
    "source": "hello",
    "sender": "bot",
    "user_id": "<USER_ID>",
    "messages": [
        {
            "type": "text",
            "content": {
                "text_message": "Welcome !"
            }
        }
    ]
}
```



---
[Back to top](#direct-api---endpoints)
