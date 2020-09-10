<?php

function askWhat($userid) {
	updateLocation("kb/add", $userid);
	inlinekeyboard([
        [["text" => "Gruppo", "callback_data" => "kb/add/group"],["text" => "Facoltà", "callback_data" => "kb/add/faculty"]],
        [["text" => "↩ Indietro", "callback_data" => "kb/start"]] 
		], $userid, "Cosa vuoi aggiungere?");
	return;
}

function searchCommands($text, $userid, $firstname,&$iscommand,&$database,$cbdata) {
	include "messages.php";
	if (substr($text, 0, 1) === '/') {
		$iscommand = 1;
		$textarraylw = explode(' ',strtolower($text));
        $cmd = $textarraylw[0];

		switch ($cmd) {
            case "/start":
                updateLocation("kb/start", $userid);
				inlinekeyboard([
				  [["text" => "🔎 Inizia!", "callback_data" => "kb/start/0"]] //setto cddata del pulsante a kb/0 e controllerò cbdata dopo per scegliere cosa fare
				], $userid, $start_response);
            break;
            case "/info":
                updateLocation("kb/info", $userid);
			    inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $info_response);
			break;
            case "/stats":
                updateLocation("kb/stats", $userid);
                inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $stats_response);
            break;
            case "/nowhatsapp":
                updateLocation("kb/nowhatsapp", $userid);
                inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $nowhatsapp_response);
            break;
            case "/suggest":
                updateLocation("kb/suggest", $userid);
                inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $suggest_response);
            break;
            case "/addlink":
				if(getUserType($userid) != "admin"){
                    updateLocation("kb/nadd", $userid);
                    inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $notadmin_response);
				}else{
                    askWhat($userid);
                }
            break;
            case "/lista":
                if(getcbDataFromUser($userid) == "0") {
                    updateLocation("kb/list", $userid);
                    inlinekeyboard([[["text" => "♻️ AGGIORNA IL BOT", "callback_data" => "kb/start"]]], $userid, $list_response);
                } else {
                    $response = "<b>⚠️ ATTENZIONE!</b>\nComando non riconosciuto!";
                    updateLocation("kb", $userid);
                    inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $response);
                }
            break;
            //case "/update":
                //sendMess(376824328,"Ciao!\nDi recente hai cercato \"<b>Produzione industriale</b>\" con il Bot ma non hai trovato nulla.\nSono lieto di informarti che tale facoltà è appena stata aggiunta! Il link al gruppo è il seguente: @prodinduspolito!");
            //break;
			default:
              $response = "<b>⚠️ ATTENZIONE!</b>\nComando non riconosciuto.";
              updateLocation("kb", $userid);
              inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $response);
			  break;
		  }
		}
	return;
}

function getFacultiesKeyboard($type) {
    $faculties = getFaculties($type);
    $resarray = array();
    $i = 0;
    $row = array();
    foreach($faculties as $el){
        if($i != 2) {
            array_push($row,["text" => "$el[0]", "callback_data" => "$el[1]"]);
            $i++;
        }else{
            array_push($resarray,$row);
            $row = [];
            array_push($row,["text" => "$el[0]", "callback_data" => "$el[1]"]);
            $i = 1;
        }   
    }
    array_push($resarray,$row);
    return $resarray;
}

function getLinksKeyboard($facultyid) {
    $resarray = array();
    $links = getLinksByFacultyId($facultyid);
    
    $i = 0;
    $row = array();
    foreach($links as $el){
        if($i != 2) {
            array_push($row,["text" => "🔗 " . "$el[0]", "url" => "$el[1]"]);
            $i++;
        }else{
            array_push($resarray,$row);
            $row = [];
            array_push($row,["text" => "🔗 " . "$el[0]", "url" => "$el[1]"]);
            $i = 1;
        }   
    }
    array_push($resarray,$row);
    return $resarray;
}

function manageKeyboard($userid, $cbdata) {
    $db_cbdata = getAllCbdata();
    foreach($db_cbdata as $row) {
        if($row[0] == $cbdata) {
            $facultyid = getFacultyIdBycbdata($cbdata);
            $resarray = getLinksKeyboard($facultyid);
            $type = getTypeByFacultyId($facultyid);
            if($type == "T") 
                array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/tri"]]);
            else
                array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/mag"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
            return;
        }
    }
    return;
}

function searchKeyboard($cbdata, $userid) {
    $firstname = $GLOBALS['firstname'];
    include "messages.php";

    switch($cbdata) {
        case "kb/start":
            editText([
                [["text" => "🔎 Inizia!", "callback_data" => "kb/start/0"]]
            ], $userid,$GLOBALS['msgid'],$start_response);
        break;
        case "kb/start/0":
            updateKeyboard([
                [["text" => "Primo Anno", "callback_data" => "kb/start/0/pri"]],
                [["text" => "Triennale", "callback_data" => "kb/start/0/tri"],
                 ["text" => "Magistrale", "callback_data" => "kb/start/0/mag"]],
                [["text" => "Architettura", "callback_data" => "kb/start/0/arch"],
                 ["text" => "Design", "callback_data" => "kb/start/0/des"]],
                [["text" => "Altro", "callback_data" => "kb/start/0/oth"]]
            ], $userid,$GLOBALS['msgid']);
        break;
        case "kb/start/0/pri":
            $resarray = getLinksKeyboard(1); //FacultyId Primo anno = 1
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
        break;
        case "kb/start/0/tri":
            $resarray = getFacultiesKeyboard("T");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
        break;
        case "kb/start/0/mag":
            $resarray = getFacultiesKeyboard("M");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
        break;
        case "kb/start/0/arch":
            $resarray = getLinksKeyboard(2); //FacultyId arch = 2
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
        break;
        case "kb/start/0/oth":
            $resarray = getLinksKeyboard(27); //FacultyId other = 27
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
        break;
        case "kb/start/0/des":
            $resarray = getLinksKeyboard(28); //FacultyId design = 28
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            updateKeyboard($resarray, $userid,$GLOBALS['msgid']);
        default:
            manageKeyboard($userid, $cbdata);
        break;
    }
    
    return;
}

//GESTIONE INLINE QUERY
function createITText($facultyid, $name){
    $links = getLinksByFacultyId($facultyid);
    $type = getTypeByFacultyId($facultyid);

    switch ($type) {
        case "T":
            $typetotext = "Triennale";
        break;
        case "M":
            $typetotext = "Magistrale";
        break;
        default:
            $typetotex = '';
    }

	$n = count($links);
	if($n === 1) {
        $text = "È disponibile *" . $n . "* link per la facoltà di *" . $name .
        "* (" . $typetotext . "):\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
		return $text;
	}
	
    $text = "Sono disponibili *" . $n . "* link per la facoltà di *" . $name .
    "* (" . $typetotext . "):\n\n";

    if($type == "A") {
        if($n === 1) {
            $text = "È disponibile *" . $n . "* link per la facoltà di *" . $name .
            "*:\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
            return $text;
        }
        
        $text = "Sono disponibili *" . $n . "* link per la facoltà di *" . $name .
        "*\n\n";
    }

    if($type == "O") {
        if($n === 1) {
            $text = "È disponibile *" . $n . "* link generale:\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
            return $text;
        }
        
        $text = "Sono disponibili *" . $n . "* link generali:\n\n";
    }

    if(strtolower($name) == "primo anno") {
        if($n === 1) {
            $text = "È disponibile *" . $n . "* link per i gruppi *primo anno*:\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
            return $text;
        }
        
        $text = "Sono disponibili *" . $n . "* link per i gruppi *primo anno*:\n\n";
    }

	$sum = 0;  
	foreach ($links as $link) {
		$sum++;
 		$text = $text . $sum . ". [" . $link[0] . "](" . $link[1] .")\n";
	}
	  
	return $text;
}

function searchDB_all(&$responsearray) {
    $db = getAllFaculties();

	foreach ($db as $group) {
		$answer["message_text"] = createITText($group[0],$group[1]);
		$answer["parse_mode"] = "markdown";
		$answer["disable_web_page_preview"] = true;
        $id = count($responsearray) + 1;

        switch($group[3]) {
            case "T":
                $title = $group[1] . " (Triennale)";
            break;
            case "M":
                $title = $group[1] . " (Magistrale)";
            break;
            case "A":
                $title = $group[1];
            break;
            case "O":
                $title = $group[1];
            break;
            default:
                $title = $group[1];
            break;
        }
        //$group[2][26] = "a";
		$output = array(
			"type" => "article",
			"id" => $id,
			"input_message_content" => $answer,
			"title" => $title,
			"description" => $group[2],
			"thumb_url" => "https://telegram.org/img/t_logo.png"
		);
		
		array_push($responsearray, $output);
	}
	  return;
}

function searchDB_filtered($question, &$responsearray) {
    $db = getAllFaculties();

	foreach ($db as $group) {
		if(strncmp($question,strtolower($group[1]),strlen($question)) == 0) {
			$answer["message_text"] = createITText($group[0],$group[1]);
			$answer["parse_mode"] = "markdown";
			$answer["disable_web_page_preview"] = true;
            $id = count($responsearray) + 1;
            switch($group[3]) {
                case "T":
                    $title = $group[1] . " (Triennale)";
                break;
                case "M":
                    $title = $group[1] . " (Magistrale)";
                break;
                default:
                    $title = $group[1];
                break;
            }
            //$group[2][26] = "a";
			$output = array(
				"type" => "article",
				"id" => $id,
				"input_message_content" => $answer,
				"title" => $title,
				"description" => $group[2],
				"thumb_url" => "https://telegram.org/img/t_logo.png"
			);
			
			array_push($responsearray, $output);
	  }
	}
	  return;
}

function searchDB($question, $is_empty) {
	$responsearray = array();

	if($is_empty){
        
		searchDB_all($responsearray);
		return $responsearray;
	}

	$inputstr = strtolower($question);
	
	searchDB_filtered($inputstr, $responsearray);
	
	return $responsearray;
}

function ILQuery($ilquery,$ilqid) {
	if ($ilquery != null) {
		$ilquery = trim($ilquery);
		$queryans = searchDB($ilquery,0);
		ansquery($ilqid, $queryans);
		return;
    }
    
    $queryans = searchDB($ilquery,1);
    ansquery($ilqid, $queryans);
	
	return;
}

//RICERCA DAL BOT

function SearchLinks($userid,$iscommand, $text) {
	if($iscommand === 1) return;
    $db = getAllFaculties();

	$length = strlen($text);
    $text = strtolower($text);
    $trovato = false;

    foreach ($db as $group) {
		if(strncmp($text,strtolower($group[1]),strlen($text)) == 0) {
            $answer = createITText($group[0],$group[1]);
            updateLocation("kb/search", $userid);
            inlinekeyboardMD([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $answer);
            $trovato = true;			
	  }
	}
    
    if(!$trovato) {
    	$text = "😔 *GRUPPO NON TROVATO*\nSono spiacente, ma non ho trovato il gruppo che cerchi. Potrebbe essere un errore di battitura. Per essere certo che non ci sia, controlla tramite il menù che appare schiacciando /start.";
        sendMessMD($userid, $text);
    }
	return;
}

?>