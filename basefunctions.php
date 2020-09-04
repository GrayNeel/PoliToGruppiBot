<?php

/** These are all the base functions that will be used for communicating
 * with Telegram Bot.
 * No libraries are used in this project.
 */

/**
 * Encodes a created URL to Telegram
 * 
 * @param $url the URL to encode
 * @return $result The result of the encode
 */
function request($url) {
	$url = api . $url;
	$url = str_replace(array(" ", "\n", "'", "#"), array("%20", "%0A%0D", "%27", "%23"), $url);
	$CurlSession = curl_init();
	curl_setopt($CurlSession,CURLOPT_URL,$url);
	curl_setopt($CurlSession,CURLOPT_HEADER, false);
	curl_setopt($CurlSession,CURLOPT_RETURNTRANSFER, true);
	$result=curl_exec($CurlSession);
	curl_close($CurlSession);
	return $result;
}

/** 
 * Send a message using HTML parse mode.
 * 
 * @param $id the userid
 * @param $urltext The message to send
 * 
 * @return $result Result of the encode
 */
function sendMess($id, $urltext) {
	if (strpos($urltext, "\n")) $urltext = urlencode($urltext);
	return request("sendMessage?text=$urltext&parse_mode=HTML&chat_id=$id&disable_web_page_preview=true");
}

/** 
 * Send a message using Markdown parse mode.
 * 
 * @param $id the userid
 * @param $urltext The message to send
 * 
 * @return The result of the encode
 */
function sendMessMD($id, $urltext) {
	if (strpos($urltext, "\n")) $urltext = urlencode($urltext);
	return request("sendMessage?text=$urltext&parse_mode=markdown&chat_id=$id&disable_web_page_preview=true");
}

/**
 * Gets the keyboard layout and send it to the user. A new message is created.
 * 
 * @param $layout Keyboard layout to send
 * @param $id userid
 * @param $msgtext Message text to send using HTML parse mode
 * 
 * @return The result of the encode
 */
function inlinekeyboard($layout, $id, $msgtext) {
	if (strpos($msgtext, "\n")) $msgtext = urlencode($msgtext);
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("sendMessage?text=$msgtext&parse_mode=HTML&chat_id=$id&reply_markup=$keyboard&disable_web_page_preview=true");
}

/**
 * Gets the keyboard layout and send it to the user. A new message is created.
 * 
 * @param $layout Keyboard layout to send
 * @param $id userid
 * @param $msgtext Message text to send using Markdown parse mode
 * 
 * @return The result of the encode
 */
function inlinekeyboardMD($layout, $id, $msgtext) {
	if (strpos($msgtext, "\n")) $msgtext = urlencode($msgtext);
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("sendMessage?text=$msgtext&parse_mode=markdown&chat_id=$id&reply_markup=$keyboard&disable_web_page_preview=true");
}

/**
 * Updates the keyboard without sending a new message, but modifies the existing one
 * 
 * @param $layout Keyboard layout to send
 * @param $id user id
 * @param $msgid message id to modify
 * 
 * @return THe result of the encode
 */
function updateKeyboard($layout, $id, $msgid) {
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("editMessageReplyMarkup?chat_id=$id&message_id=$msgid&reply_markup=$keyboard");
}

/**
 * Edits a sent message (including Keyboard Layout).
 * 
 * @param $layout Keyboard layout to send
 * @param $id user id
 * @param $msgid Message text to modify
 * @param $msgtext Message text to send
 * 
 * @return The result of the encode
 */
function editText($layout, $id, $msgid, $msgtext) {
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("editMessageText?chat_id=$id&message_id=$msgid&reply_markup=$keyboard&text=$msgtext&parse_mode=HTML&disable_web_page_preview=true");
}

/**
 * Answers an inline query
 * 
 * @param $q_id Query id
 * @param $ans The answers
 *
 * @return The result of the encode
 */
function ansquery($q_id, $ans) {
	$res = json_encode($ans);
	return request("answerInlineQuery?inline_query_id=$q_id&results=$res");
}

function answerCallbackQuery($cbid) {
	return request("answerCallbackQuery?callback_query_id=$cbid");
}

?>