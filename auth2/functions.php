<?php
/*
========== * EVE ONLINE TEAMSPEAK BY MJ MAVERICK * ==========
*/
function saveMember($nickname, $usergroup, $inputID, $inputVCode, $characterID, $blue) {
	$c = new Config;
	try {
		$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$c->tsname.":".$c->tspass."@".$c->tshost.":".$c->tsport."/?server_port=".$c->tscport);
	} catch (TeamSpeak3_Exception $e) {
		die("An error occured: ".$e->getMessage()." [F".__LINE__."]");
	}
	try {
		$tsClient = $ts3_VirtualServer->clientGetByName($nickname); // $tsClient reduces strain on the server
		$tsDatabaseID = $tsClient->client_database_id;
		$tsUniqueID = $tsClient->client_unique_identifier;
		if ($c->verbose == true) {
			echo "<strong>Debug:</strong> Database ID: ".$tsDatabaseID."<br /><strong>Debug:</strong> Unique ID: ".$tsUniqueID."<br />";
		}
	} catch (TeamSpeak3_Exception $e) {
		die("Error: Could not find you on the server, your nickname should be exactly \"$nickname\" (Error: ".$e->getMessage()." [F".__LINE__."])");
	}
	try {
		$ts3_VirtualServer->clientGetByName($nickname)->addServerGroup($usergroup);
	} catch (TeamSpeak3_Exception $e) {
		die("Error: Could not find you on the server, your nickname should be exactly '".$nickname."'. Either that or you already have permissions. (Error: ".$e->getMessage()." [F".__LINE__."])");
	}
	// ATTEMPT TO STORE DETAILS IN DATABASE - IF FAIL THEN REMOVE ACCESS AND REWIND
	try {
		$conINSERT = mysql_connect($c->db_host,$c->db_user,$c->db_pass);
			if (!$conINSERT) {
				$tsClient->remServerGroup($usergroup);
				die("Could not connect: " . mysql_error()." [F".__LINE__."]");
			}
		$db_selectINSERT = mysql_select_db($c->db_name, $conINSERT);
			if (!$db_selectINSERT) {
				$tsClient->remServerGroup($usergroup);
				die("Could not select database: " . mysql_error()." [F".__LINE__."]");
			}
		//destroy any SQL injections that got through our initial checks and "somehow" got through API
		$inputID = mysql_real_escape_string($inputID);
		$inputVCode = mysql_real_escape_string($inputVCode);
		$tsUniqueID = mysql_real_escape_string($tsUniqueID);
		$tsName = mysql_real_escape_string($nickname);
		
		mysql_query("INSERT INTO users (api_kID,api_VCode,characterID,blue,tsDatabaseID,tsUniqueID,tsName) VALUES ('$inputID','$inputVCode','$characterID','$blue','$tsDatabaseID','$tsUniqueID','$tsName')");
		mysql_close($conINSERT);
	} catch (SQL_Exception $e) {
		$tsClient->remServerGroup($usergroup);
		die("Error: Failed to INSERT new member. (Error: ".$e->getMessage()." [F".__LINE__."])");
	}
	echo "Access granted. You should now have permissions on Teamspeak 3.";
}

function removeDuplicates($accountCharacterID1, $accountCharacterID2, $accountCharacterID3, $inputVCode, $inputID) {
	$c = new Config;
	$duplicateCharacter = false;
	$conCheck = mysql_connect($c->db_host,$c->db_user,$c->db_pass);
	if (!$conCheck) {
		die("Could not connect: " . mysql_error()." [F".__LINE__."]");
	}
	$db_select = mysql_select_db($c->db_name, $conCheck);
	if (!$db_select) {
		die("Could not select database: " . mysql_error()." [F".__LINE__."]");
	}
	$query = mysql_query("SELECT * FROM users ORDER BY characterID;");
	while ($row = mysql_fetch_array($query)) {
		$characterID = $row["characterID"];
		if ($characterID == $accountCharacterID1 || $characterID == $accountCharacterID2 || $characterID == $accountCharacterID3) {
			$duplicateCharacter = true;
			$api_kID = $row["api_kID"];
			$api_VCode = $row["api_VCode"];
			break;
		}
	}	
	mysql_close($conCheck);
	if ($duplicateCharacter == true) {
		if ($inputVCode == $api_VCode && $inputID == $api_kID) {
			if ($c->verbose == true) {
				echo "<strong>Debug:</strong> Characters ".$accountCharacterID1." - ".$accountCharacterID2." - ".$accountCharacterID3."<br />";
			}
			die("Attention: Your account has already been registered. If you haven't already registered then contact ".$c->admin." immediately, a spy may have stolen your identity.<br /> If you are trying to register on two computers then click <a href='duplicates.php'>here</a>.<br /> If you have reformatted and not backed up your Teamspeak 3 Identity or otherwise lost your Teamspeak 3 Identity then click <a href='duplicates.php'>here</a>.<br />");
		}
		if ($inputID == $api_kID || $inputID > $api_kID) {
			$removed = 0;
			echo "Removing your old registrations...<br />";
			$conRemoveOld = mysql_connect($c->db_host,$c->db_user,$c->db_pass);
			if (!$conRemoveOld) {
				die("Could not connect: " . mysql_error()." [F".__LINE__."]");
			}
			$db_select = mysql_select_db($c->db_name, $conRemoveOld);
			if (!$db_select) {
				die("Could not select database: " . mysql_error()." [F".__LINE__."]");
			}
			$query = mysql_query("SELECT * FROM users WHERE characterID = $characterID;");
			while ($row = mysql_fetch_array($query)) {
				$entryID = $row["entryID"];
				$api_kID = $row["api_kID"];
				$api_VCode = $row["api_VCode"];
				$tsDatabaseID = $row["tsDatabaseID"];
				$tsName = $row["tsName"];

				try {
					// connect to the server
					$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$c->tsname.":".$c->tspass."@".$c->tshost.":".$c->tsport."/?server_port=".$c->tscport);
				} catch (TeamSpeak3_Exception $e) {
					die("Error: ".$e->getMessage()." [F".__LINE__."]");
				}
				try {
					$ts3_VirtualServer->clientdeleteDb($tsDatabaseID);
					// they are gone, remove them from the database
					try {
						$queryDelete = "DELETE FROM users WHERE characterID = $characterID;";
						mysql_query($queryDelete, $conRemoveOld );
						$removed = $removed + 1;
					} catch (TeamSpeak3_Exception $e) {
						echo "Warning: Failed to remove old registrations of: ".$tsName." from the database, entry ".$entryID.". Please EVEMail this message to ".$c->admin." immediately. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [F".__LINE__."]<br />";
					}
				} catch (TeamSpeak3_Exception $e) {
					echo "Warning: Failed to delete user: ".$tsName." TSDID: ".$tsDatabaseID.". Please EVEMail this message to ".$c->admin." immediately. (Error: ".$e->getMessage().") [F".__LINE__."]<br />";
				}
			}
			echo $removed." old registration removed.<br /><br />";
		}
	}
}

function sqlCheck($checkThis,$me) {
	$SQL = array(";","=",'"',"'");
	foreach ($SQL as $scanForThis) {
		$SQLcheck = strpos($checkThis, $scanForThis);
		if ($SQLcheck !== false) {
			die("Error: You have entered an illegal character in ".$me." (".$scanForThis."). [F".__LINE__."]");
		}
	}
}

function sqlCheckNames($checkThis,$me) {
	// this check allows ' which is legally in some names so will be dealt with just prior to storage with a substitue
	$SQL = array(";","=",'"');
	foreach ($SQL as $scanForThis) {
		$SQLcheck = strpos($checkThis, $scanForThis);
		if ($SQLcheck !== false) {
			die("Error: You have entered an illegal character in ".$me." (".$scanForThis."). [F".__LINE__."]");
		}
	}
}

// don't put html in a cron jobs output
function format($php, $html) {
	if ($_SERVER['HTTP_USER_AGENT'] == false) {
		echo $php;
	} else {
		echo $html;
	}
}

function SQLconnect($connect) {
	$c = new Config;
	if ($connect == "open") {
		$con = mysql_connect($c->db_host,$c->db_user,$c->db_pass);
			if (!$con) {
				die("Could not connect: " . mysql_error()." [F".__LINE__."]");
			}
		$db_select = mysql_select_db($c->db_name, $con);
			if (!$db_select) {
				die("Could not select database: " . mysql_error()." [F".__LINE__."]");
			}
	} else if ($connect == "close") {
		mysql_close();
	} else {
		echo "Error: \"SQLconnect\" function must be either \"open\" or \"close\".";
	}
}
function magicLink($gate,$charID,$text) {
	if (!isset($_SERVER['HTTP_USER_AGENT'])) {
		$link = "<a href=\"".$gate."\">".$text."</a>";
	} else {
		if (strpos($_SERVER['HTTP_USER_AGENT'],"EVE-IGB") !== false) {
			$link = "<a onClick='CCPEVE.showInfo(1377,".$charID.")' href='#'>".$text."</a>";
		} else {
			$link = "<a href=\"".$gate."\">".$text."</a>";
		}
	}
	return $link;
}


## PHPBB Integration. We're snarfing a userid from the cookie off the forums
function get_phpbb_user_id($prefix = "phpbb_") {
	
	$query = "SELECT config_name, config_value
				FROM ".$prefix."config
				WHERE config_name IN ('cookie_name', 'session_length')";

	SQLconnect("open");
	$result = mysql_query($query) or die("SQL Failed.<br />Error: ".mysql_error()."<br />Query Run:<br />".$query);
	
	while($row = mysql_fetch_assoc($result)) {
		
		$forums_config[$row['config_name']] = $row['config_value'];
		
	}
	
	if(empty($forums_config['cookie_name']) or empty($forums_config['session_length'])) {
		
		echo "ERROR: Some cookie information could not be read. Please re-login on the forums and try again";
		break;
	}
	
	if(!isset($_COOKIE[$forums_config['cookie_name'] . '_u'])) {
		
		return false;
		
	}

	$cookie['User ID'] = mysql_real_escape_string($_COOKIE[$forums_config['cookie_name'] . '_u']);
	$cookie['Session ID'] = mysql_real_escape_string($_COOKIE[$forums_config['cookie_name'] . '_sid']);
	
	# var_dump($cookie);
	# var_dump($forums_config);

	$query = "SELECT session_user_id as 'User ID'
				FROM ".$prefix."sessions
				WHERE session_user_id = '".$cookie['User ID']."'
				AND session_id = '".$cookie['Session ID']."'
				AND UNIX_TIMESTAMP() - session_time < ".$forums_config['session_length'];  

	$result = mysql_query($query) or die("SQL Failed.<br />Error: ".mysql_error()."<br />Query Run:<br />".$query);
	SQLconnect("close");
	
	$session = mysql_fetch_assoc($result);
	
	if($session['User ID'] == 1){
		return false;

	}
	
	return $session['User ID'];
}

?>