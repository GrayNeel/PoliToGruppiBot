<?php

include "messages.php";

$control = 0; //SET TO 0. IF IT IS AN ADMIN MENU COMMAND, IT GOES TO 1 AND SKIP OTHER COMMANDS

if(getUserType($userid) == 'admin') {
	if($tor == IS_CBQUERY) {
		updateLocation($cbdata, $userid);
	}
	
	switch(getcbDataFromUser($userid)) {
		case "kb/add":
			$GLOBALS['control']=1;
			askWhat($userid);
		break;
		case "kb/add/group":
			$GLOBALS['control']=1;
			if($tor == IS_CBQUERY) {
				$resarray=[[["text" => "↩ Indietro", "callback_data" => "kb/add"]]];
				editText($resarray, $userid, $msgid, $GLOBALS['linkinfo_response']); 
			} else {
				$row = explode(PHP_EOL,$text);
				$title = $row[0];
				$testo = $row[1];
				$link = $row[2];
				$type = $row[3];
				$facultyid = getFacultyIdByTitle($title,$type);
	
				if(count($row)<=1){
					updateLocation("kb/add/ko", $userid);
					inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/add"],["text" => "Riprova", "callback_data" => "kb/add/group"]]], $userid, $GLOBALS['failedlink_response']);
					return;
				}
				
				$now = date("Y-m-d");
				$stmt = mysqli_prepare($dbconn,"INSERT INTO links (facultyid, facultyname, text, link, Type, date) VALUES (?, ?, ?, ?, ?, ?)");
				mysqli_stmt_bind_param($stmt, "ssssss", $facultyid,$title,$testo,$link,$type,$now);
				mysqli_stmt_execute($stmt);

				updateLocation("kb/add/ok", $userid);
   				inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $GLOBALS['linksucc_response']);
				return;
			}
		break;
		case "kb/add/faculty":
			$GLOBALS['control']=1;
			if($tor == IS_CBQUERY) {
				$resarray=[[["text" => "↩ Indietro", "callback_data" => "kb/add"]]];
				editText($resarray, $userid, $msgid, $GLOBALS['facultyinfo_response']); 
			} else {
				$row = explode(PHP_EOL,$text);
				$title = $row[0];
				$description = $row[1];
				$type = $row[2];
				$cbdata = $row[3];
	
				if(count($row)<=1){
					updateLocation("kb/add/ko", $userid);
					inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/add"],["text" => "Riprova", "callback_data" => "kb/add/faculty"]]], $userid, $GLOBALS['failedlink_response']);
					return;
				}
				
				$stmt = mysqli_prepare($dbconn,"INSERT INTO faculties (title, description, Type, cbdata) VALUES (?, ?, ?, ?)");
				mysqli_stmt_bind_param($stmt, "ssss", $title,$description,$type,$cbdata);
				mysqli_stmt_execute($stmt);

				updateLocation("kb/add/ok", $userid);
 			    inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $GLOBALS['facsucc_response']);
				return;
			}
		break;
	}
}

?>