<?php
/**
 * This is the link file between PHP and SQL Database.
 */

/**
 * Opens a new connection to database.
 * 
 * See mysqli documentation for further information.
 */
$dbconn = mysqli_connect($where, $name, $pass, $dbname);
if (mysqli_connect_errno($dbconn)) {
	sendMess($userid,"Errore connessione DB.");
	exit;
}
mysqli_set_charset($dbconn, "utf8mb4");
/**
 * Saves into requests table every Bot request.
 * Useful for statistics purpose.
 */
$stmt = mysqli_prepare($dbconn,"INSERT INTO requests (userid, name, lang, request_data) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $userid, $nametext, $lang, $content);
mysqli_stmt_execute($stmt);

$now = date("Y-m-d H:i:s");

/**
 * Saves into users table every user that uses the Bot.
 * Useful for statistics purpose.
 */
$stmt = mysqli_prepare($dbconn,"INSERT INTO users (userid, name, username, lang) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE date=?, name=?, username=?");
mysqli_stmt_bind_param($stmt, "sssssss", $userid, $nametext, $username,$lang,$now,$nametext,$username);
mysqli_stmt_execute($stmt);



/**
 * Gives the number of users that used at least one time the Bot.
 * 
 * @return The total number of users
 */
function totalUsers() {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) from users");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

/**
 * Gives the number of requests that have been pushed to the Bot.
 * 
 * @return The total number of requests
 */
function totalRequests() {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) from requests");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

/**
 * Gives the number of requests that have been pushed to the Bot today.
 * 
 * @return The total number of requests today
 */
function requestsToday() {
	$today = date("Y-m-d");
	$yesterday = date("Y-m-d", strtotime('yesterday'));
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM requests WHERE date>='$today 00:00:01' AND date<='$today 23:59:59'");
	//mysqli_stmt_bind_param($stmt, "ss", $today, $yesterday);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	//sendMess($userid,mysqli_error($dbconn));
	return $total;
}

/**
 * Gives the number of users that used the Bot today.
 * 
 * @return The total number of users today
 */
function usersToday() {
	$today = date("Y-m-d");
	$yesterday = date("Y-m-d", strtotime('yesterday'));
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM users WHERE date>='$today 00:00:01' AND date<='$today 23:59:59'");
	//mysqli_stmt_bind_param($stmt, "ss", $today, $today);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	//sendMess($userid,mysqli_error($dbconn));
	return $total;
}

/**
 * Gives the number of links that the Bot contains.
 * 
 * @return The total number of links
 */
function getTotLinks() {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM links");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

/**
 * Gives the number of users that used the Bot this month.
 * 
 * @return The total number of users this month
 */
function usersThisMonth() {
	$date = strftime("%m %Y", strtotime('this month'));
	$datevec = explode(' ',$date);
	$month = $datevec[0];
	$year = $datevec[1];
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM users WHERE date>='$year-$month-01 00:00:00' AND date<='$year-$month-31 23:59:59'");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

/**
 * Gives the number of requests that have been pushed to the Bot this month.
 * 
 * @return The total number of requests this month
 */
function RequestsThisMonth() {
	$date = strftime("%m %Y", strtotime('this month'));
	$datevec = explode(' ',$date);
	$month = $datevec[0];
	$year = $datevec[1];
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM requests WHERE date>='$year-$month-01 00:00:00' AND date<='$year-$month-31 23:59:59'");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

/**
 * Gives the number of links added to the bot this month.
 * 
 * @return The total number of links today
 */
function linksThisMonth() {
	$date = strftime("%m %Y", strtotime('this month'));
	$datevec = explode(' ',$date);
	$month = $datevec[0];
	$year = $datevec[1];
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM links WHERE date>='$year-$month-01' AND date<='$year-$month-31'");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

/** 
 * Gets the Type of User
 * 
 * @param $userid The user id to search
 * @return $res Type of requested user
 */
function getUserType($userid) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT Type FROM users WHERE userid='$userid'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);
	return $res;
}

/** 
 * Updates user location to DB.
 * 
 * @param $cbdata The location of user
 * @param $userid User id to search for
 * @return void
 */
function updateLocation($cbdata, $userid) {
	$stmt = mysqli_prepare($GLOBALS['dbconn'],"UPDATE users SET cbdata='$cbdata' WHERE userid='$userid'");
	mysqli_stmt_execute($stmt);
	return;
}

/** 
 * Gets Faculties of a given Type
 * 
 * @param $type The type of Faculty (T,M,A,D,O)
 * @return $rows Requested faculties
 */
function getFaculties($type) {
	$result = mysqli_query($GLOBALS["dbconn"], "SELECT title,cbdata FROM faculties WHERE Type='$type' ORDER BY title");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

/** 
 * Gets links of a given faculty
 * 
 * @param $facultyid Faculty id
 * @return $rows Requested links
 */
function getLinksByFacultyId($facultyid) {
	$result = mysqli_query($GLOBALS["dbconn"], "SELECT text,link FROM links WHERE facultyid='$facultyid'");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

/** 
 * Gets the Type of a given faculty
 * 
 * @param $facultyid Faculty id
 * @return $res Type of requested faculty
 */
function getTypeByFacultyId($facultyid) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT Type FROM faculties WHERE facultyid='$facultyid'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

/**
 * Gives the faculty id of a given path
 * 
 * @param $cbdata The path of faculty
 * @return The faculty id
 */
function getFacultyIdBycbdata($cbdata) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT facultyid FROM faculties WHERE cbdata='$cbdata'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

/**
 * Gets the faculty id by using name and type
 * 
 * @param $title the title of faculty
 * @param $type the type of faculty
 * 
 * @return $res Faculty id
 */
function getFacultyIdByTitle($title,$type) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT facultyid FROM faculties WHERE title='$title' AND type='$type'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

/**
 * Gets all faculties from DB
 * 
 * @return $rows faculties
 */
function getAllFaculties() {
	$result = mysqli_query($GLOBALS["dbconn"], "SELECT facultyid,title,description,type FROM faculties ORDER BY title");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

/**
 * Gets path from user id
 * 
 * @param $userid user id
 * @return $res The path of where user is
 */
function getcbDataFromUser($userid) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT cbdata FROM users WHERE userid='$userid'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

/**
 * Gets all faculties paths
 * 
 * @return $rows faculties paths
 */
function getAllCbdata() {
	$result = mysqli_query($GLOBALS["dbconn"],"SELECT DISTINCT cbdata FROM faculties");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

/**
 * Selects the last added date from links table
 * 
 * @return last added date (string, not vector)
 */
function lastAdded() {
	$result = mysqli_query($GLOBALS["dbconn"],"SELECT DISTINCT date FROM links ORDER BY date");
	$rows = [];
	$i = 0;
	while($row = mysqli_fetch_array($result))
	{
		$rows[] = $row;
		$i++;
	}
	
	return $rows[$i-1][0];
}

/**
 * Gets the number of added link in a given date
 * 
 * @param $date A date (YYYY-MM-DD format)
 * @return $res The number of added links
 */
function HowManyAdded($date) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM links WHERE date='$date'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);
	
	return $res;
}

?>