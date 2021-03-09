<?php

include "pvt.php";
define('token', $token);
define('api', 'https://api.telegram.org/bot' . token . '/');
include "sql.php";
include "basefunctions.php";

$list = getUserId();
$response = "Ciao! 👋\nCon l'ultimo aggiornamento è stato aggiunto il menù per i crediti liberi III anno (ingegneria triennale). Se conosci dei link per questi gruppi sarebbe magnifico suggerirli tramite l'apposito pulsante in questo bot. In questo modo, potremmo semplificare il popolamento dei gruppi! Grazie!\n\nRiavvia il bot con /start";
foreach($list as $usrid){
    sendMess($usrid[0],$response);
    setAsSent($usrid[0]);
}
$left = countLeft();
if($left<200)
    sendMess(14868633, "Messaggi inviati con successo. Ne restano " . $left . " da inviare.");

?>