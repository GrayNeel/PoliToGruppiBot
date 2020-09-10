<?php

if(getcbDataFromUser($userid)=="kb/suggest" && $tor == IS_MESSAGE && $text != "/suggest") {
    $sent_response = "✅Richiesta inviata con successo. Se ritenuta valida, verrà aggiunta al bot il prima possibile.\nGrazie per il tuo aiuto!";

    $response = "<b>" . "RICHIESTA AGGIUNTA LINK" . "</b>\n\n" .
	"<b>ID</b>: " . $userid . "\n" . 
	"<b>Nome</b>: " . $firstname . "\n" . 
	"<b>Cognome:</b> " . $lastname . "\n" .
	"<b>Username</b>: @" . $username . "\n" .
	"<b>Data invio:</b> " . date("Y-m-d H:i:s",$update['message']['date']) . "\n" .
	"<b>Richiesta</b>: \n" . $text;

    sendMess(DEBUGID, $response);
    updateLocation("kb/done", $userid);
    inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $sent_response);

    $control=1;
}

?>