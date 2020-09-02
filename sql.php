<?php

$dbconn = mysqli_connect($where, $name, $pass, $dbname);
if (mysqli_connect_errno($dbconn)) {
	sendMess($userid,"Errore connessione DB.");
	exit;
}

$stmt = mysqli_prepare($dbconn,"INSERT INTO requests (userid, name, lang, request_data) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $userid, $nametext, $lang, $content);
mysqli_stmt_execute($stmt);

$now = date("Y-m-d H:i:s");

$stmt = mysqli_prepare($dbconn,"INSERT INTO users (userid, name, username, lang) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE date=?, name=?, username=?");
mysqli_stmt_bind_param($stmt, "sssssss", $userid, $nametext, $username,$lang,$now,$nametext,$username);
mysqli_stmt_execute($stmt);

mysqli_set_charset($dbconn, "utf8mb4");

//STATISTICS
function totalUsers() {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) from users");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

function totalRequests() {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) from requests");
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $total);
	mysqli_stmt_fetch($stmt);
	return $total;
}

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

function getTotLinks() {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM links");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

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

//END STATISTICS

//QUERIES
function getTypeUser($userid) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT Type FROM users WHERE userid='$userid'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);
	return $res;
}

function updateLocation($cbdata, $userid) {
	$stmt = mysqli_prepare($GLOBALS['dbconn'],"UPDATE users SET cbdata='$cbdata' WHERE userid='$userid'");
	mysqli_stmt_execute($stmt);
	return;
}

function getFaculties($type) {
	$result = mysqli_query($GLOBALS["dbconn"], "SELECT title,cbdata FROM faculties WHERE Type='$type' ORDER BY title");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

function getLinksByFacultyId($facultyid) {
	$result = mysqli_query($GLOBALS["dbconn"], "SELECT text,link FROM links WHERE facultyid='$facultyid'");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

function getTypeByFacultyId($facultyid) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT Type FROM faculties WHERE facultyid='$facultyid'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

function getFacultyIdBycbdata($cbdata) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT facultyid FROM faculties WHERE cbdata='$cbdata'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

function getFacultyIdByTitle($title,$type) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT facultyid FROM faculties WHERE title='$title' AND type='$type'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

function getAllFaculties() {
	$result = mysqli_query($GLOBALS["dbconn"], "SELECT facultyid,title,description,type FROM faculties ORDER BY title");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

function getcbDataFromUser($userid) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT cbdata FROM users WHERE userid='$userid'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);

	return $res;
}

function getAllCbdata() {
	$result = mysqli_query($GLOBALS["dbconn"],"SELECT DISTINCT cbdata FROM faculties");
	$rows = [];
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
	
	return $rows;
}

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

function HowManyAdded($date) {
	$stmt = mysqli_prepare($GLOBALS["dbconn"],"SELECT COUNT(*) FROM links WHERE date='$date'");
	//mysqli_stmt_bind_param($stmt, "s", $userid);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $res);
	mysqli_stmt_fetch($stmt);
	
	return $res;
}

?>