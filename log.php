<?php

/**
 * Log file.
 * In this file every update message sent to the bot is sent into a given
 * channel id.
 * Useful for debug purpose.
 * 
 */

/**
 * @param DEBUGID Channel id
 * @param DEBUG boolean variable to send full or readable message
 */
define("DEBUGID", $channelid); 
define("DEBUG", false);

/**
 * Send the update message to a given channel id
 * 
 * @param $method Type of update 
 * @param $res The received update
 * 
 * @return void
 */
function sendDebugRes($method, $res) {
	return; //IF PRESENT, LOG IS DISABLED
	if(DEBUG == true)
		return sendMess(DEBUGID,"<b>$method</b>:\n\n" . json_encode($res,JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));

	switch($method){
		case 'MESSAGE':
			$response = "<b>" . $method . "</b>\n\n" .
			"<b>ID</b>: " . $res['message']['chat']['id'] . "\n" . 
			"<b>First name</b>: " . $res['message']['chat']['first_name'] . "\n" . 
			"<b>Last name:</b> " . $res['message']['chat']['last_name'] . "\n" .
			"<b>Username</b>: @" . $res['message']['from']['username'] . "\n" .
			"<b>Date:</b> " . date("Y-m-d H:i:s",$res['message']['date']) . "\n" .
			"<b>Text</b>: " . $res['message']['text'];
			sendMess(DEBUGID,$response);
		break;
		case 'INLINE QUERY':
			$response = "<b>" . $method . "</b>\n\n" .
			"<b>ID</b>: " . $res['inline_query']['from']['id'] . "\n" .
			"<b>First name</b>: " . $res['inline_query']['from']['first_name'] . "\n" . 
			"<b>Last name</b>: " . $res['inline_query']['from']['last_name'] . "\n" .
			"<b>Username</b>: @" . $res['inline_query']['from']['username'] . "\n" .
			"<b>Query</b>: " . $res['inline_query']['query'];
			sendMess(DEBUGID,$response);
		break;
		case 'CALLBACK QUERY':
			$response = "<b>" . $method . "</b>\n\n" .
			"<b>ID</b>: " . $res['callback_query']['from']['id'] . "\n" .
			"<b>First name</b>: " . $res['callback_query']['from']['first_name'] . "\n" . 
			"<b>Last name</b>: " . $res['callback_query']['from']['last_name'] . "\n" .
			"<b>Username</b>: @" . $res['callback_query']['from']['username'] . "\n" .
			"<b>Query</b>: " . $res['callback_query']['data'];
			sendMess(DEBUGID,$response);
		break;
	}
}

?>