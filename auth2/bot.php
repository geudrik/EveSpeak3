<?php
/*
========== * EVE ONLINE TEAMSPEAK BY MJ MAVERICK * ==========
*/
// PHP debug mode
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
// Required files
require_once("TeamSpeak3/TeamSpeak3.php");
require_once("config.php");
require_once("functions.php");
// Activate config
$c = new Config;
//--------------------------------------------------------------------------------------------------------
$clientList = array();
try {
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$c->tsname.":".$c->tspass."@".$c->tshost.":".$c->tsport."/?server_port=".$c->tscport);
} catch (TeamSpeak3_Exception $e) {
	die("An error occured: ".$e->getMessage()." [B".__LINE__."]");
}
try {
	$clientList = $ts3_VirtualServer->clientList();
} catch (TeamSpeak3_Exception $e) {
	die("An error occured: ".$e->getMessage()." [B".__LINE__."]");
}

$kicked = 0;
$users = 0;

$con = mysql_connect($c->db_host,$c->db_user,$c->db_pass);
	if (!$con) {
		die("Could not connect: " . mysql_error()." [B".__LINE__."]");
	}
$db_select = mysql_select_db($c->db_name, $con);
	if (!$db_select) {
		die("Could not select database: " . mysql_error()." [B".__LINE__."]");
	}
foreach ($clientList as $client) {
	if ($client['client_type']) continue;
	$client_database_id = $client['client_database_id'];
	// check if this is a registered user
	$query = mysql_query("SELECT tsDatabaseID,tsUniqueID,tsName FROM users WHERE tsDatabaseID = $client_database_id;");
	if (mysql_num_rows($query) == 0) {
		format("Skipping user, not registered (".$client['client_nickname'].")\n","Skipping user, not registered (".$client['client_nickname'].")<br />");
	} else {
		$tsDatabaseID = mysql_result($query, 0, 0);
		$tsUniqueID = mysql_result($query, 0, 1);
		$tsName = mysql_result($query, 0, 2);
		format("Processing: ".$tsName."\n","Processing: ".$tsName."<br />");
		$users = $users + 1;
		if ($client['client_nickname'] != $tsName) {
			try {
				$ts3_VirtualServer->clientGetByUid($tsUniqueID)->Kick(TeamSpeak3::KICK_SERVER, "SecurityBot: Your nickname should be exactly ".$tsName);
				format(">>> Kicked user ".$tsName.", their name was ".$client['client_nickname']."\n","Kicked user ".$tsName.", their name was ".$client['client_nickname']."<br />");
				$kicked = $kicked + 1;
			} catch (TeamSpeak3_Exception $e) {
				format("Debug: User ".$tsName." could not be kicked. (Error: ".$e->getMessage().")\n", "Debug: User ".$tsName." could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")<br />");
			}
		}
	}
}
mysql_close($con);
echo "$users users checked, $kicked users kicked.";
?>