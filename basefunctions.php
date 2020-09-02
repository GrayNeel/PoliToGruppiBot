<?php

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

//sendMessage in formato HTML
function sendMess($id, $urltext) {
	if (strpos($urltext, "\n")) $urltext = urlencode($urltext);
	return request("sendMessage?text=$urltext&parse_mode=HTML&chat_id=$id&disable_web_page_preview=true");
}

//sendMessage in formato Markdown
function sendMessMD($id, $urltext) {
	if (strpos($urltext, "\n")) $urltext = urlencode($urltext);
	return request("sendMessage?text=$urltext&parse_mode=markdown&chat_id=$id&disable_web_page_preview=true");
}

//INLINEKEYBOARD PRELEVA IL LAYOUT E LO INVIA ALL'UTENTE CON UNA SENDMESS
function inlinekeyboard($layout, $id, $msgtext) {
	//Se dentro msgtext trovo un a capo, codiifca il testo e lo rende  utile per essere inviato in un url
	if (strpos($msgtext, "\n")) $msgtext = urlencode($msgtext);
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("sendMessage?text=$msgtext&parse_mode=HTML&chat_id=$id&reply_markup=$keyboard&disable_web_page_preview=true");
}

//INLINEKEYBOARD MARKDOWN
function inlinekeyboardMD($layout, $id, $msgtext) {
	//Se dentro msgtext trovo un a capo, codiifca il testo e lo rende  utile per essere inviato in un url
	if (strpos($msgtext, "\n")) $msgtext = urlencode($msgtext);
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("sendMessage?text=$msgtext&parse_mode=markdown&chat_id=$id&reply_markup=$keyboard&disable_web_page_preview=true");
}

//AGGIORNA LA TASTIERA INLINE
function updateKeyboard($layout, $id, $msgid) {
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("editMessageReplyMarkup?chat_id=$id&message_id=$msgid&reply_markup=$keyboard");
}

function editText($layout, $id, $msgid, $msgtext) {
	$keyboard = json_encode(array("inline_keyboard" => $layout));
	return request("editMessageText?chat_id=$id&message_id=$msgid&reply_markup=$keyboard&text=$msgtext&parse_mode=HTML&disable_web_page_preview=true");
}

//RISPONDI ALLA ILQUERY
function ansquery($q_id, $ans) {
	$res = json_encode($ans);
	return request("answerInlineQuery?inline_query_id=$q_id&results=$res");
}

?>