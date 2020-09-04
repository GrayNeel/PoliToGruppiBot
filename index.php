<?php
/**
	* Telegram Bot written using PHP that helps student get all groups' link for faculties.
	* The database is on SQL and is not clearly referred here, so if you clone it
	* will not work.
	* 
	*
	* This code can be reused in accordance with the GNU GPLv3 license.
	*
	* @author     Marco Smorti 
	* @license    https://www.gnu.org/licenses/gpl-3.0.txt
*/

/**
 * This file contains all credentials for telegram bot and SQL Database 
 * and it is not included in the GitHub repository.
 */
include "pvt.php";
define('token', $token);
define('api', 'https://api.telegram.org/bot' . token . '/');

$content = file_get_contents("php://input");
$update = json_decode($content, true); 

/**
 * This section of the code gets all the basefunctions and fuctions files.
 * This index.php is just a wrapper just to make easy to look the code.
 */

include "variables.php";
include "basefunctions.php";
include "sql.php";
include "log.php";
include "functions.php"; //ALL FUNCTIONS
include "admincommands.php"; //CHECKS FOR ADMIN COMMANDS 

//TOR = Type of Request
switch ($tor) {
	case IS_CBQUERY:
		sendDebugRes("CALLBACK QUERY", $update);
		updateLocation($cbdata, $userid);
		answerCallbackQuery($cbid);
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
