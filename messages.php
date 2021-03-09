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
 "agli studenti soprattutto a coloro i quali quest'anno non riusciranno ad essere a Torino.\nIl bot è <b><i>100% gratuito</i></b>.\n\n" .
 "🤩 <b><i>POSSO AIUTARE IN QUALCHE MODO?</i></b>\n" .
 "Certo che sì! Se ti viene in mente qualsiasi gruppo che possa andar bene per questo bot " . 
 "non esitare a segnalarlo schiacciando il pulsante <i>\"💡suggerisci \"</i> in questo bot. Grazie!\n\n" . 
 "⏫ <b><i>VERSIONE DEL BOT: v3.2.0 - 02/03/2021</i></b>\n" . 
 "- Sistemata la sezione \"Primo anno\"\n";

$start_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "Ciao <b>$firstname</b>! 👋\nBenvenut* nel bot <b>Gruppi PoliTo</b>!\n\n" .
 "<b><i>🧭 DOVE MI TROVO?</i></b>\n" .
 "Con questo bot è possibile ottenere i link ai gruppi <i>Whatsapp/Telegram</i> delle facoltà del <i>Politecnico di Torino</i>.\n\n" .
 "<b>🔧 COME FUNZIONA?\n</b>" .
 "Naviga tra i menù schiacciando il tasto \"<i><b>Inizia!</b></i>\" in fondo a questo messaggio o rispondi a questo messaggio col nome della tua facoltà.\n\n" .
 "<b><i>🤝 POSSO CONTRIBUIRE?</i></b>\n" .
 "Il tuo aiuto è fondamentale! Comunica i link dei gruppi mancanti schiacciando il bottone <i>\"💡suggerisci \"</i> all'interno del bot. I tuoi colleghi te ne saranno grati!\n\n" .
 "<b><i>🔄 ULTIMI AGGIORNAMENTI</i></b>\n" . 
 "Il bot si aggiorna continuamente. Visitalo regolarmente se al momento non trovi ciò che cerchi!\n" .
 "<b><i>Ultimo aggiornamento:</i>\n$count $word - $currdate</b>";

$stats_response = "<b><i>📊 STATISTICHE</i></b>\n\nLe statistiche di seguito riportate sono da considerare a " . 
 "partire dal <b>27 agosto 2020</b>, data di inserimento del collettore statistiche.\n\n" .
 "<b>📉 <i>STATISTICHE GENERALI</i></b>\n" .
 "N° utenti unici totali: <b>" . totalUsers() . "</b>\n" . 
 "N° interazioni col bot: <b>" . totalRequests() . "</b>\n" .
 "N° di link presenti: <b>" . getTotLinks() .  "</b>\n" .
 "\n<b>📈 <i>STATISTICHE MENSILI (". strftime("%B %Y", strtotime('last month')) . ")</i></b>\n" .
 "N° utenti unici: <b>" . usersLastMonth() . "</b>\n" .
 "N° interazioni col bot: <b>" . RequestsLastMonth() . "</b>\n" .
 "N° link inseriti: <b>" . linksLastMonth() . "</b>\n" .
 "\n<b>📈 <i>STATISTICHE MENSILI (". strftime("%B %Y", strtotime('this month')) . ")</i></b>\n" .
 "N° utenti unici: <b>" . usersThisMonth() . "</b>\n" .
 "N° interazioni col bot: <b>" . RequestsThisMonth() . "</b>\n" .
 "N° link inseriti: <b>" . linksThisMonth() . "</b>\n" .
 "\n<b>📈 <i>STATISTICHE GIORNALIERE</i></b>\n" .
 "N° utenti unici: <b>" . usersToday() . "</b>\n" .
 "N° interazioni col bot: <b>" . requestsToday() . "</b>\n";

$notadmin_response = "⚠️<b>ATTENZIONE!⚠️\nQuesto comando è disponibile soltanto agli admin del bot.</b>";

$linksucc_response = "✅ <b>Gruppo aggiunto con successo.</b>";
$groupsucc_response = "✅ <b>Facoltà aggiunta con successo.</b>";
$failedlink_response = "❌ <b>Errore nell'inserimento dei parametri.</b>";

$linkinfo_response = "Per aggiungere un gruppo invia i dati come segue:\n\n" .
 "<i>Nome breve (per la ricerca) facoltà del link\nNome da visualizzare sul bottone del link\nURL\nTipo (IP/IT/C3/IM/AT/AM/O)\n\nPrimo anno:\n- Primo anno (per cognome) IPC\n- Primo anno (per facoltà) IPF\n- Primo anno (in English) IPE</i>";

$facsucc_response = "✅ <b>Facoltà aggiunta con successo.</b>";

$facultyinfo_response = "Per aggiungere una facoltà invia i dati come segue:\n\n" .
 "<i>Nome completo (sul bottone)\nNome facoltà breve (per la ricerca)\nDescrizione (es. <b>Invia i link per la facoltà di Architettura (Triennale)</b>)\nTipo (IT,IM,AT,AM,O)\nCBData (es. <b>kb/start/0/ing/tri/amb</b>)</i>"; 

$list_response = "<b><i>COMANDO DISATTIVATO</i></b>\n\n😔 Mi dispiace, dalla tua ultima visita il bot ha subito profondi " . 
 "aggiornamenti e questo comando non è più disponibile. Ma non preoccuparti, adesso cercare i gruppi è ancora più facile!\nAggiorna il bot schiacciando il pulsante qui in basso!";

$nowhatsapp_response = "<b>💬 PERCHÈ QUESTO ODIO PER WHATSAPP?</b>\n\n" . 
 "I bot e la maggior parte dei gruppi sono stati creati su Telegram e speriamo che possiate popolarli qui su telegram per svariati motivi. Tra i problemi di Whatsapp troviamo:\n\n" .
 "❌ <i>Limite di 256 persone per gruppo</i>\n" .
 "❌ <i>Numeri di telefono <b>visibili a tutti</b></i>\n" .
 "❌ Chat invisibile a chi entra per ultimo (che quindi perde tutto il materiale inviato in precedenza)\n" .
 "❌ Difficoltà nel gestire il materiale stesso\n" . 
 "❌ Limite nella dimensione dei documenti\n" .
 "❌ Milioni di altri motivi che non sto qua a elencare..\n\n" .
 "Anche i professori, inoltre, sanno che il modo più corretto di comunicare con gli studenti durante l'emergenza coronavirus è tramite Telegram, infatti durante lo scorso semestre tutti i gruppi con i docenti erano proprio su questa piattaforma!";

$suggest_response = "Invia adesso un messaggio contenente tutte le informazioni utili per poter aggiungere il gruppo come:\n\n" .
"- <i>Link del gruppo (Telegram o Whatsapp)</i>\n" . 
"- <i>Nome della facoltà</i>\n" .
"- <i>Tipo (Triennale, Magistrale o Architettura/design)</i>\n\n" . 
"Esempio:\n" . 
"<i>Ciao! Vorrei aggiungere al bot il gruppo per la triennale in Ingegneria delle Merendine! Il link Whatsapp è https://www.google.it/</i>";

$mainmenu_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" .
 "<b>Seleziona l'area principale della facoltà che cerchi oppure seleziona i gruppi generici del Politecnico.</b>\n\n" . 
 "Esempio: <i>la facoltà di Design fa parte dell'area di Architettura</i>.";

$ing_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "<i>Seleziona una tra le alternative (primo anno, triennale o magistrale)</i>";

$ingtri_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "<i>Di seguito, trovi le facoltà triennali per i quali è presente almeno un gruppo Telegram o Whatsapp.</i>";

$ingmag_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "<i>Di seguito, trovi le facoltà magistrali per i quali è presente almeno un gruppo Telegram o Whatsapp.</i>";

$ingpri_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "Scegli <b>Per cognome</b> se cerchi i gruppi con gli scaglioni del I semestre, oppure <b>Per facoltà</b> per i gruppi del II semestre (materia di indirizzo)";
 
$ingpricogn_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "<i>Di seguito trovi tutti i link dei gruppi divisi per cognome.</i>";

$ingprieng_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "<i>Di seguito trovi tutti i link dei gruppi del primo anno in inglese.</i>";

$ingprifac_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
 "<i>Di seguito trovi tutti i link dei gruppi divisi per facoltà.</i>";

$arch_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
"<i>Seleziona una tra le alternative (triennale o magistrale)</i>";

$archtri_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
"<i>Di seguito, trovi le facoltà triennali per i quali è presente almeno un gruppo Telegram o Whatsapp.</i>";

$archmag_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
"<i>Di seguito, trovi le facoltà magistrali per i quali è presente almeno un gruppo Telegram o Whatsapp.</i>";

$oth_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
"<i>Di seguito trovi una serie di link che potrebbero esserti utili.</i>";

$ingcred_response = "👥 <b><i>BOT GRUPPI POLITO</i></b> 👥\n\n" . 
"<i>Di seguito trovi i link dei crediti liberi.</i>";
?>