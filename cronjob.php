<?php

include "pvt.php";
define('token', $token);
define('api', 'https://api.telegram.org/bot' . token . '/');
include "sql.php";
include "basefunctions.php";

$list = getUserId();
$response = "Ciao! 👋\nQuesto bot esiste soprattutto grazie al vostro contributo nel mantenere questo database sempre aggiornato.".
"\n\nPer tale motivo, da oggi è possibile utilizzare il bottone \"<b>💡 Segnala link</b>\" presente nel menù sia per comunicare nuovi link, ".
"che per segnalare eventuali link non più funzionanti.\n\nVi invito quindi a suggerire nuovi link dei quali siete venuti a ".
"conoscenza per aiutare i vostri colleghi e a segnalare quelli non più funzionanti!\n\nGrazie mille per il vostro contributo.".
"\n\nRicomincia a usare il bot cliccando /start\n\n~ @GrayNeel";

foreach($list as $usrid){
    sendMess($usrid[0],$response);
    setAsSent($usrid[0]);
}
$left = countLeft();

if($left<200)
    sendMess(14868633, "Messaggi inviati con successo. Ne restano " . $left . " da inviare.");

?>