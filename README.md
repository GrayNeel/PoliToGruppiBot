# PoliToGruppiBot
This is a Telegram Bot that collects link and organize them using inline keyboard from Telegram APIs and a MySQL database. This project has NO dependencies, since all the Telegram APIs are implemented in __basefunctions.php__ file.

## Get started
To get started with this bot a token is neeeded. Get it from BotFather by creating a new bot and then initialize a new "_pvt.php_" file filling the following variables:

```php
<?php

//Telegram Bot Token
$token = "YOUR TOKEN HERE 00000000:ABCDEFGH";

//SQL Database Variables
$where = "localhost";
$name = "sitename";
$pass = "db_password";
$dbname = "db_name";   

//Your channel ID for debug purposes. 
//Remember to add "-100" in front of the ID
$channelid = -100123456789;
?>
```
Once you're done with the _pvt.php_ file the last step before getting the bot working is to use _webhook_ which is a way to tell to Telegram where to redirect all the bot's requests.
For doing so, create another file called "_register.php_" putting this code:
```php
<?php
//EDIT THE URL BY PUTTING THE LINK OF YOUR SITE THAT REDIRECTS TO index.php
$WEBHOOK_URL = "https://xyz.com/index.php"; // URL main BOT (https://xyz.altervista.org/index.php)
$BOT_TOKEN = "YOUR TOKEN HERE 00000000:ABCDEFGH"; // Token

// NON APPORTARE MODIFICHE NEL CODICE SEGUENTE
$parameters = array('url' => $WEBHOOK_URL);
$url = \sprintf('https://api.telegram.org/bot%s/setWebhook?%s', $BOT_TOKEN, \http_build_query($parameters));
$handle = \curl_init($url);
\curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
\curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
\curl_setopt($handle, CURLOPT_TIMEOUT, 60);
$result = \curl_exec($handle);
\curl_close($handle);
\print_r($result);

```
**IMPORTANT**: Remember to activate HTTPS on your website, otherwise bot will not work.

## index.php

This is the starting point of all your bot's requests. This page gets all the input coming from the bot using
```php
$content = file_get_contents("php://input");
```
And then parsing them into a readable JSON format. Then all the other file are included and the main aim of this page is to distinguish between three main type of request (called TOR):
* **IS_CBQUERY** in this chunk of code is possible to manage all the Callback Queries, which are the event connected to the Inline Keyboard (pressing of the buttons typically).
* **IS_MESSAGE** here all the messages from user will be managed.
* **IS_ILQUERY** manages the inline query (tagging the bot from any chat). 

## variables.php

This files gets all the necessary variables from the JSON created before. You can access it as a vector. In this files it is also recognized if it is a CB_QUERY, IL_QUERY or a MESSAGE.

## basefunctions.php

This file is the **core** that contains Telegram APIs and allows you to communicate with target user.  
For example, you may want to send a message to user by using the following function:
```php
sendMess($id, $text);
```
It receives:
* **id**: it can be a userid or a chatid. Remember that userid must activate the bot to be able to answer. Futhermore, to answer a chatid the bot must be in the chat group.
* **text**: it is the string that you will send back to target.

All the other functions work like this and you just need to get a look to Telegram APIs to create new one.

## sql.php

This file contains all the queries to database. It is the direct interact point with your DB and in my case i used **mysqli** directly from PHP. If everything is set (see _pvt.php_) a successful connection will be open.

## log.php

This is the part that helps you debug your code. set **DEBUG** variable to _true_ if you want to receive all the JSONs queries in your channel, _false_ if you don't want to.

## functions.php

This is where the job has been done. All these functions create the keyboard, search the links, organize them. 

## messages.php

All the messages that Bot sends back are here. If you want you can edit them and maybe translate into another language. 
**NOTE**: there is no multilanguage support. To enable that, you may need to switch those messages to the DB.

## suggest.php

File that implements the "request a  link" function. It sends all the request to the channelid referred in _pvt.php_.

## admincommands.php

This file helps admin of bot to add new link from the chat instead of manipulating directly the bot.