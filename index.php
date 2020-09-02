<?php

$content = file_get_contents("php://input"); //GET INPUT FROM BOT
$update = json_decode($content, true); //TRANSLATE INTO READABLE CONTENT

include "pvt.php";

//TELEGRAM BOT VARIABLES
define('token', $token);
define('api', 'https://api.telegram.org/bot' . token . '/');

include "variables.php"; //DEFINE VARIABLES FROM UPDATE
include "basefunctions.php"; //BASE FUNCTIONS FOR USER INTERACTIONS
include "sql.php"; //sQL DB
include "log.php"; //LOG CHANNEL FOR MESSAGES
include "functions.php"; //ALL FUNCTIONS

$control = 0; //SET TO 0. IF IT IS AN ADMIN MENU COMMAND, IT GOES TO 1 AND SKIP OTHER COMMANDS
include "admincommands.php"; //CHECKS FOR ADMIN COMMANDS 

//TOR = Type of Request
switch ($tor) {
	case IS_CBQUERY:
		sendDebugRes("CALLBACK QUERY", $update);
		updateLocation($cbdata, $userid);
		searchKeyboard($cbdata, $userid);
	break;
	case IS_MESSAGE:
		if($control == 0) {
			sendDebugRes("MESSAGE", $update);
			$iscommand=0;
			searchCommands($text,$userid,$firstname,$iscommand,$database,$cbdata);
			SearchLinks($userid,$iscommand, $text);
		}
	break;
	case IS_ILQUERY:
		sendDebugRes("INLINE QUERY", $update);
		ILQuery($ilquery,$ilqid);
	break;
}

exit(0);
?>
