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
				  [["text" => "🔎 Inizia!", "callback_data" => "kb/start/0"]]
				], $userid, $start_response);
            break;
            case "/addlink":
				if(getUserType($userid) != "admin"){
                    updateLocation("kb/nadd", $userid);
                    inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $notadmin_response);
				}else{
                    askWhat($userid);
                }
            break;
            case "/send":
                $tid=$textarraylw[1];
                $response = "✅ La tua richiesta è stata accolta e il link è stato aggiunto. Grazie per il tuo fondamentale contributo!";
                sendMess($tid,$response);
                inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, "Messaggio inviato con successo.");
            break;
            case "/upd":
                $list = getUserId();
                $response = "Ciao! 👋\nCon l'ultimo aggiornamento è stato aggiunto il menù per i crediti liberi III anno (ingegneria triennale). Se conosci dei link per questi gruppi sarebbe magnifico suggerirli tramite l'apposito pulsante in questo bot. In questo modo, potremmo semplificare il popolamento dei gruppi! Grazie!\n\nRiavvia il bot con /start";
                foreach($list as $usrid){
                    sendMess($usrid[0],$response);
                    setAsSent($usrid[0]);
                }
                $left = countLeft();
                inlinekeyboard([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, "Messaggi inviati con successo. Ne restano " . $left . " da inviare.");
            break;
			default:
              $response = "<b>⚠️ ATTENZIONE!</b>\nI comandi non esistono più! Da ora, puoi fare tutto utilizzando i pulsanti integrati. Provali schiacciando il pulsante qui sotto! 👇";
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
    switch($type) {
        case "IT":
            array_push($row,["text" => "🔽 INGEGNERIA (TRIENNALE) 🔽", "callback_data" => "null"]);
            array_push($resarray,$row);
        break;
        case "IM":
            array_push($row,["text" => "🔽 INGEGNERIA (MAGISTRALE) 🔽", "callback_data" => "null"]);
            array_push($resarray,$row);
        break;
        case "AT":
            array_push($row,["text" => "🔽 ARCHITETTURA (TRIENNALE) 🔽", "callback_data" => "null"]);
            array_push($resarray,$row);
        break;
        case "AM":
            array_push($row,["text" => "🔽 ARCHITETTURA (MAGISTRALE) 🔽", "callback_data" => "null"]);
            array_push($resarray,$row);
        case "C3":
            array_push($row,["text" => "🔽 CREDITI III ANNO (TRIENNALE) 🔽", "callback_data" => "null"]);
            array_push($resarray,$row);
        
        break;
    }
    $row = array();
    foreach($faculties as $el){
        if($i != 2) {
            array_push($row,["text" => "$el[2]", "callback_data" => "$el[1]"]);
            $i++;
        }else{
            array_push($resarray,$row);
            $row = [];
            array_push($row,["text" => "$el[2]", "callback_data" => "$el[1]"]);
            $i = 1;
        }   
    }
    array_push($resarray,$row);
    return $resarray;
}

function getLinksKeyboard($facultyid) {
    $resarray = array();
    $links = getLinksByFacultyId($facultyid);
    $name = getNameByFacultyId($facultyid);
    $type = getTypeByFacultyId($facultyid);
    $name = strtoupper($name);
    $i = 0;
    $row = array();

    switch ($type) {
        case "IT":
        case "C3":
        case "AT":
            $typetotext = "TRIENNALE";
        break;
        case "IM":
        case "AM":
            $typetotext = "MAGISTRALE";
        break;
        default:
            $typetotext = '';
    }

    if($typetotext != '') {
        array_push($row,["text" => "🔽 " . $name . " ($typetotext) 🔽", "callback_data" => "null"]);
        array_push($resarray,$row);
    }else{
        array_push($row,["text" => "🔽 " . $name . " 🔽", "callback_data" => "null"]);
        array_push($resarray,$row);
    }
    
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
            
            switch($type) {
                case "IT":
                    array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing/tri"]]);
                break;
                case "IM":
                    array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing/mag"]]);
                break;
                case "AT":
                    array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/arch/tri"]]);
                break;
                case "AM":
                    array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/arch/mag"]]);
                break;
                case "C3":
                    array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing"]]);
                break;
            }
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
            editText([
                [["text" => "AREA DELL'INGEGNERIA", "callback_data" => "kb/start/0/ing"]],
                [["text" => "AREA DELL'ARCHITETTURA", "callback_data" => "kb/start/0/arch"]],
                [["text" => "GRUPPI GENERICI", "callback_data" => "kb/start/0/oth"]],
                [["text" => "24 CFU - CIFIS PIEMONTE", "url" => "https://t.me/piemonte24cfu"]],
                [["text" => "🤝 DONA PER SOSTENERE IL BOT", "url" => "https://paypal.me/pools/c/8xn0U8qhHx"]],
                [["text" => "ℹ️ Info", "callback_data" => "kb/start/0/info"],["text" => "💡 Segnala link", "callback_data" => "kb/start/0/suggest"]],
                [["text" => "❓ NoWhatsapp", "callback_data" => "kb/start/0/nowhatsapp"],["text" => "📊 Statistiche", "callback_data" => "kb/start/0/stats"]],
                [["text" => "🛠 Estensione Chrome PoliTools", "url" => "https://chrome.google.com/webstore/detail/politools/fbbjhoaakfhbggkegckmjafkffaofnkd?hl=it"]],
                [["text" => "↩ Torna al messaggio di benvenuto", "callback_data" => "kb/start"]]
            ], $userid,$GLOBALS['msgid'],$mainmenu_response);
        break;
        case "kb/start/0/ing":
            editText([
                [["text" => "🔽 AREA DELL'INGEGNERIA 2020/21 🔽", "callback_data" => "null"]],
                [["text" => "Primo Anno", "callback_data" => "kb/start/0/ing/pri"]],
                [["text" => "Triennale", "callback_data" => "kb/start/0/ing/tri"],["text" => "Magistrale", "callback_data" => "kb/start/0/ing/mag"]],
                [["text" => "Crediti liberi III anno", "callback_data" => "kb/start/0/ing/c3"]],
                [["text" => "🔽 AREA DELL'INGEGNERIA 2021/22 🔽", "callback_data" => "null"]],
                [["text" => "🔗 Aspiranti matricole 2021/22", "url" => "https://t.me/matricolepolito2122"]],
                [["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]
            ], $userid,$GLOBALS['msgid'],$ing_response);
        break;
        case "kb/start/0/ing/tri":
            $resarray = getFacultiesKeyboard("IT");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$ingtri_response);
        break;
        case "kb/start/0/ing/mag":
            $resarray = getFacultiesKeyboard("IM");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$ingmag_response);
        break;
        case "kb/start/0/ing/c3":
            $resarray = getFacultiesKeyboard("C3");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$ingcred_response);
        break;
        case "kb/start/0/ing/pri/cogn":
            $resarray = getLinksKeyboard(78); //FacultyId Primo anno per cognome = 78
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing/pri"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$ingpricogn_response);
        break;
        case "kb/start/0/ing/pri/fac":
            $resarray = getLinksKeyboard(79); //FacultyId Primo anno per facoltà = 79
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing/pri"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$ingprifac_response);
        break;    
        case "kb/start/0/ing/pri/eng":
            $resarray = getLinksKeyboard(80); //FacultyId Primo anno in inglese = 80
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing/pri"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$ingprieng_response);
        break;
        case "kb/start/0/ing/pri":
            editText([
                [["text" => "🔽 PRIMO ANNO 🔽", "callback_data" => "null"]],
                [["text" => "Per cognome", "callback_data" => "kb/start/0/ing/pri/cogn"],["text" => "Per facoltà", "callback_data" => "kb/start/0/ing/pri/fac"]],
                [["text" => "1st Year in English", "callback_data" => "kb/start/0/ing/pri/eng"]],
                [["text" => "🔽 PRIMO ANNO GENERICI 🔽", "callback_data" => "null"]],
                [["text" => "🔗 Matricole 2020/21", "url" => "https://t.me/matricolepolito"],["text" => "🔗 Lingua inglese","url" => "https://t.me/joinchat/AWHhTUUXXLMS99Tod9DXWA"]],
                [["text" => "🔗 Gruppi Materie comuni", "url" => "t.me/primoanno_bot"]],
                [["text" => "↩ Indietro", "callback_data" => "kb/start/0/ing"]]
            ],$userid,$GLOBALS['msgid'],$ingpri_response);
        break;
        case "kb/start/0/arch":
            editText([
                [["text" => "🔽 AREA DELL'ARCHITETTURA 🔽", "callback_data" => "null"]],
                [["text" => "Triennale", "callback_data" => "kb/start/0/arch/tri"],["text" => "Magistrale", "callback_data" => "kb/start/0/arch/mag"]],
                [["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]
            ], $userid,$GLOBALS['msgid'],$arch_response);
        break;
        case "kb/start/0/arch/tri":
            $resarray = getFacultiesKeyboard("AT");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/arch"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$archtri_response);
        break;
        case "kb/start/0/arch/mag":
            $resarray = getFacultiesKeyboard("AM");
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0/arch"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$archmag_response);
        break;
        case "kb/start/0/oth":
            $resarray = getLinksKeyboard(27);
            array_push($resarray,[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]);
            editText($resarray,$userid,$GLOBALS['msgid'],$oth_response);
        break;
        case "kb/start/0/info":
            updateLocation("kb/start/0/info", $userid);
            editText([[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]],$userid,$GLOBALS['msgid'],$info_response);
        break;
        case "kb/start/0/suggest":
            updateLocation("kb/start/0/suggest", $userid);
            editText([[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]],$userid,$GLOBALS['msgid'],$suggest_response);
        break;
        case "kb/start/0/nowhatsapp":
            updateLocation("kb/start/0/nowhatsapp", $userid);
            editText([[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]],$userid,$GLOBALS['msgid'],$nowhatsapp_response);
        break;
        case "kb/start/0/stats":
            updateLocation("kb/start/0/stats", $userid);
            editText([[["text" => "↩ Indietro", "callback_data" => "kb/start/0"]]],$userid,$GLOBALS['msgid'],$stats_response);
        break;
        default:
            //updateLocation($cbdata,$userid);
            manageKeyboard($userid, $cbdata);
        break;
    }
    
    return;
}

//GESTIONE INLINE QUERY
function createITText($facultyid, $name){
    $links = getLinksByFacultyId($facultyid);
    $type = getTypeByFacultyId($facultyid);
    $fulltitle = getNameByFacultyId($facultyid);

    switch ($type) {
        case "IT":
        case "AT":
            $typetotext = "Triennale";
        break;
        case "IM":
        case "AM":
            $typetotext = "Magistrale";
        break;
        default:
            $typetotex = '';
    }

	$n = count($links);
	if($n === 1) {
        $text = "È disponibile *" . $n . "* link per la facoltà di *" . $fulltitle .
        "* (" . $typetotext . "):\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
		return $text;
	}
	
    $text = "Sono disponibili *" . $n . "* link per la facoltà di *" . $fulltitle .
    "* (" . $typetotext . "):\n\n";

    if($type == "O") {
        if($n === 1) {
            $text = "È disponibile *" . $n . "* link generale:\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
            return $text;
        }
        
        $text = "Sono disponibili *" . $n . "* link generali:\n\n";
    }

    if(strtolower($name) == "primo anno") {
        if($n === 1) {
            $text = "È disponibile *" . $n . "* link per i gruppi del *primo anno* triennale di ingegneria:\n\n" . "1. [" . $links[0][0] ."](" . $links[0][1] . ")\n";
            return $text;
        }
        
        $text = "Sono disponibili *" . $n . "* link per i gruppi del *primo anno* triennale di ingegneria:\n\n";
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
            case "IT":
            case "AT":
                $title = $group[1] . " (Triennale)";
            break;
            case "IM":
            case "AM":
                $title = $group[1] . " (Magistrale)";
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
                case "IT":
                case "AT":
                case "IP":
                    $title = $group[1] . " (Triennale)";
                break;
                case "IM":
                case "AM":
                    $title = $group[1] . " (Magistrale)";
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
    $textarraylw = explode(' ',strtolower($text));
    foreach ($db as $group) {
		/*if(strncmp($text,strtolower($group[1]),strlen($text)) == 0) {
            $answer = createITText($group[0],$group[1]);
            updateLocation("kb/search", $userid);
            inlinekeyboardMD([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $answer);
            $trovato = true;			
        }*/
      
        foreach($textarraylw as $word) {
            if(strncmp($word,strtolower($group[1]),strlen($word)) == 0) {
                $answer = createITText($group[0],$group[1]);
                updateLocation("kb/search", $userid);
                inlinekeyboardMD([[["text" => "↩ Indietro", "callback_data" => "kb/start"]]], $userid, $answer);
                $trovato = true;			
          }
        }
      
	}
    
    if(!$trovato) {
        $text = "😔 <b>GRUPPO NON TROVATO</b>\nSono spiacente, ma non ho trovato il gruppo che cerchi. Potrebbe essere un errore di battitura. Per essere certo che non ci sia, controlla tramite il menù che appare schiacciando il pulsante qui sotto 👇";
        updateLocation("kb/notfound", $userid);
		inlinekeyboard([[["text" => "♻️ RICOMINCIA", "callback_data" => "kb/start"]]], $userid, $text);
    }
	return;
}

?>