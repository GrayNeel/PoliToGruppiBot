<?php
//VARIABILI PER INVIO DATI AL CANALE DI LOG
define("DEBUGID", -1001374739530); //ID DEL CANALE
define("DEBUG", false);
//END VARIABILI PER INVIO DATI AL CANALE DI LOG

//FUNZIONE PER INVIO DATI AL CANALE DI LOG. INSERITA QUA PERCHE' UTILIZZATA NELLO SWITCH CREAZIONE VARIABILI
function sendDebugRes($method, $res) {
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