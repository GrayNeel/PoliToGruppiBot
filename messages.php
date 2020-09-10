<?php

$lastdate = lastAdded();
$count = HowManyAdded($lastdate);
setlocale(LC_TIME, 'it_IT');
$currdate = strftime("%e %B %G",strtotime($lastdate));

if($count == 1) {
    $word = "nuovo link";
} else {
    $word = "nuovi link";
}

$info_response = "<b>ℹ️ INFORMAZIONI ℹ️</b>\n\n" .
 "<b><i>🔍 RICERCA INLINE</i></b>\n" .
 "Sapevi che se tagghi il bot in una qualsiasi chat Telegram puoi ricercare e condividere in maniera semplice e veloce i link ai gruppi? " .
 "Provalo subito!\n\n" .
 "🤔 <b><i>COSA C'E' SOTTO?</i></b>\n" .
 "Nascosto nelle profondità dell'internet è presente il codice del bot, scritto interamente in PHP " .
 "unito alla potenza dei database SQL, luogo dove risiedono tutte le informazioni sui gruppi.\n\n" .
 "🤔 <b><i>SI MA COSA NASCONDE?</i></b>\n" .
 "Assolutamente nulla! Il bot è stato scritto da @GrayNeel con il solo intento di essere utile " . 
 "agli studenti soprattutto a coloro i quali quest'anno non riusciranno ad essere a Torino.\nOvviamente il bot è <b><i>100% gratuito</i></b>.\n\n" .
 "🤩 <b><i>POSSO AIUTARE IN QUALCHE MODO?</i></b>\n" .
 "Certo che sì! Se ti viene in mente qualsiasi gruppo che possa andar bene per questo bot " . 
 "non esitare a segnalarlo inviando il comando /suggest in questa chat. Grazie!\n\n" . 
 "⏫ <b><i>VERSIONE DEL BOT: v2.1.0 - 10/09/2020</i></b>\n" . 
 "- Aggiunta la possibilità di inviare richieste tramite il comando /suggest 📝\n\n" .
 "<i>Sei curioso di sapere quanto il bot è stato utilizzato? Digita</i> /stats!";

$start_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "Ciao <b>$firstname</b>! 👋\nBenvenut* nel bot <b>Gruppi PoliTo</b>!\n\n" .
 "⚠️<b>I GRUPPI FANNO RIFERIMENTO ALL'A.A. 2020/21.\nSE SEI ALLA FINE DEL II ANNO CERCA I GRUPPI III ANNO!</b>\n\n" . 
 "<b><i>🧭 DOVE MI TROVO?</i></b>\n" .
 "Con questo bot è possibile ottenere i link ai gruppi <i>Whatsapp/Telegram</i> delle facoltà del <i>Politecnico di Torino</i>.\n\n" .
 "<b>🔧 COME FUNZIONA?\n</b>" .
 "Naviga tra i menù schiacciando il tasto \"<i><b>Inizia!</b></i>\" in fondo a questo messaggio o rispondi a questo messaggio col nome della tua facoltà.\n" .
 "Digita /info per ulteriori informazioni sul bot.\n\n" .
 "<b><i>⁉️ PERCHÈ NON WHATSAPP?</i></b>\n" .
 "Maggiori info: /nowhatsapp\n\n" .
 "<b><i>🤝 POSSO CONTRIBUIRE?</i></b>\n" .
 "Il tuo aiuto è fondamentale! Comunica i link dei gruppi mancanti usando il comando /suggest. I tuoi colleghi te ne saranno grati!\n\n" .
 "<b><i>🔄 ULTIMI AGGIORNAMENTI</i></b>\n" . 
 "Il bot si aggiorna continuamente. Visitalo regolarmente se al momento non trovi ciò che cerchi!\n" .
 "<b><i>Ultimo aggiornamento:</i>\n$count $word - $currdate</b>";

$stats_response = "<b><i>📊 STATISTICHE</i></b>\n\nLe statistiche di seguito riportate sono da considerare a " . 
 "partire dal <b>27 agosto 2020</b>, data di inserimento della funzionalità.\n\n" .
 "<b>📉 <i>STATISTICHE GENERALI</i></b>\n" .
 "N° utenti totali: <b>" . totalUsers() . "</b>\n" . 
 "N° richieste inviate: <b>" . totalRequests() . "</b>\n" .
 "N° di link presenti: <b>" . getTotLinks() .  "</b>\n" .
 "\n<b>📈 <i>STATISTICHE MENSILI (". strftime("%B %Y", strtotime('this month')) . ")</i></b>\n" .
 "N° utenti: <b>" . usersThisMonth() . "</b>\n" .
 "N° richieste inviate: <b>" . RequestsThisMonth() . "</b>\n" .
 "N° link inseriti: <b>" . linksThisMonth() . "</b>\n" .
 "\n<b>📈 <i>STATISTICHE GIORNALIERE</i></b>\n" .
 "N° utenti: <b>" . usersToday() . "</b>\n" .
 "N° richieste inviate: <b>" . requestsToday() . "</b>\n";

$notadmin_response = "⚠️<b>ATTENZIONE!⚠️\nQuesto comando è disponibile soltanto agli admin del bot.</b>";

$linksucc_response = "✅ <b>Gruppo aggiunto con successo.</b>";
$groupsucc_response = "✅ <b>Facoltà aggiunta con successo.</b>";
$failedlink_response = "❌ <b>Errore nell'inserimento dei parametri.</b>";

$linkinfo_response = "Per aggiungere un gruppo invia i dati come segue:\n\n" .
 "<i>Nome facoltà del link\nNome da visualizzare sul bottone link\nURL\nTipo (A/D/T/M/O)</i>";

$facsucc_response = "✅ <b>Facoltà aggiunta con successo.</b>";

$facultyinfo_response = "Per aggiungere una facoltà invia i dati come segue:\n\n" .
 "<i>Nome facoltà\nDescrizione (es. <b>Invia i link per la facoltà di Architettura</b>.)\nTipo (A/D/T/M/O)\nCBData (es. <b>kb/start/0/tri/amb</b>)</i>"; 

$list_response = "<b><i>COMANDO DISATTIVATO</i></b>\n\n😔 Mi dispiace, dalla tua ultima visita il bot ha subito profondi " . 
 "aggiornamenti e questo comando non è più disponibile. Ma non preoccuparti, adesso cercare i gruppi è ancora più facile!\nAggiorna il bot schiacciando il pulsante qui in basso!";

$nowhatsapp_response = "<b>💬 PERCHÈ QUESTO ODIO PER WHATSAPP?</b>\n\n" . 
 "I bot e la maggior parte dei gruppi sono stati creati su Telegram e speriamo che possiate popolarli qui su telegram per svariati motivi:\n\n" .
 "❌ <i>Limite di 256 persone per gruppo</i>\n" .
 "❌ <i>Numeri di telefono <b>visibili a tutti</b></i>\n" .
 "❌ Chat invisibile a chi entra per ultimo (che quindi perde tutto il materiale inviato in precedenza)\n" .
 "❌ Difficoltà nel gestire il materiale stesso\n" . 
 "❌ Limite nella dimensione dei documenti\n" .
 "❌ Milioni di altri motivi che non sto qua a elencare..\n\n" .
 "Persino i professori hanno capito che Telegram è il modo più corretto di comunicare con gli studenti durante l'emergenza coronavirus, infatti durante lo scorso semestre tutti i gruppi con i docenti erano proprio su questa piattaforma!";

$suggest_response = "Invia adesso un messaggio contenente tutte le informazioni utili per poter aggiungere il gruppo come:\n\n" .
"- <i>Link del gruppo (Telegram o Whatsapp)</i>\n" . 
"- <i>Nome della facoltà</i>\n" .
"- <i>Tipo (Triennale, Magistrale o Architettura/design)</i>\n\n" . 
"Esempio:\n" . 
"<i>Ciao! Vorrei aggiungere al bot il gruppo per la triennale in Ingegneria delle Merendine! Il link Whatsapp è https://www.google.it/</i>";

?>