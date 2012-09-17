<?php
/*
========== * EVE ONLINE TEAMSPEAK BY MJ MAVERICK * ==========
*/
// Required files
require_once("TeamSpeak3/TeamSpeak3.php");
require_once("pheal/Pheal.php");
require_once("config.php");
require_once("functions.php");
// Pheal stuff
spl_autoload_register("Pheal::classload");
PhealConfig::getInstance()->http_keepalive = true;
$pheal = new Pheal(NULL,NULL);
// Activate config
$c = new Config;

//--------------------------------------------------------------------------------------------------------

// make sure API is up
try {
	$testAPI = $pheal->eveScope->CharacterInfo(array("characterID" => $c->TESTID));
	if ($testAPI->characterName == $c->TESTname) {
		format("API Connection was established.\n\n", "API Connection was established.<br /><br />");
	} else {
		format("API Connection could not be established.\n Please contact ".$c->admin.", they likely haven't configured TESTID and TESTname in the config properly.", "API Connection could not be established.<br /> Please contact ".$c->admin.", they likely haven't configured TESTID and TESTname in the config properly.");
		die();
	}
} catch (PhealException $E) {
	format("API Connection could not be established.\n An error occured probably due to the API server being down or taking too long to respond.\n Error: ".$E->getMessage()." [C".__LINE__."]","API Connection could not be established.<br /> An error occured probably due to the API server being down or taking too long to respond.<br /> Error: ".$E->getMessage()." [C".__LINE__."]");
	die();
}
// connect to DB
$con = mysql_connect($c->db_host,$c->db_user,$c->db_pass);
	if (!$con) {
		die("Connect Fail:" . mysql_error());
	}
$db_select = mysql_select_db($c->db_name, $con);
	if (!$db_select) {
		die("DB Select fail:" . mysql_error());
	}
// set query to pull in data from our database
$query = mysql_query("SELECT * FROM users ORDER BY tsDatabaseID;");
// start loopy loop
$runCount = 0;
while ($row = mysql_fetch_array($query)) {
	// store this users data from the database
	$entryID = "$row[entryID]";
	$api_kID = "$row[api_kID]";
	$api_VCode = "$row[api_VCode]";
	$characterID = "$row[characterID]";
	$blue = "$row[blue]";
	$tsDatabaseID = "$row[tsDatabaseID]";
	$tsUniqueID = "$row[tsUniqueID]";
	$tsName = "$row[tsName]";
	
	// lets use Pheal to fetch some details about this user from the API
	try {
		$fetch = $pheal->eveScope->CharacterInfo(array('characterID' => $characterID));
	} catch (PhealException $E) {
		format(">>> Warning: Failed to contact API CharacterInfo regarding character \"$tsName\". (Error: ".$E->getMessage().") [C".__LINE__."]\n",">>> Warning: Failed to contact API CharacterInfo regarding character \"$tsName\". (Error: ".$E->getMessage().") [C".__LINE__."]<br />");
		$errors = true;
		continue;
	}
	$fetchCharacterName = $fetch->characterName;
	$fetchCorporation = $fetch->corporation;
	$fetchAlliance = $fetch->alliance;
	if ($c->verbose == true) {
		format("Checking... $fetchCharacterName\n Debug: Corporation: $fetchCorporation\n Debug: Alliance: $fetchAlliance\n Debug: TS Name: $tsName\n Debug: TS UID: $tsUniqueID\n Debug: TS DID: $tsDatabaseID\n Debug: Blue: $blue\n Debug: Character ID: $characterID\n", "Checking... $fetchCharacterName<br /> Debug: Corporation: $fetchCorporation<br /> Debug: Alliance: $fetchAlliance<br /> Debug: TS Name: $tsName<br /> Debug: TS UID: $tsUniqueID<br /> Debug: TS DID: $tsDatabaseID<br /> Debug: Blue: $blue<br /> Debug: Character ID: $characterID<br />");
	} else {
		format("Checking... $fetchCharacterName\n", "Checking... $fetchCharacterName<br />");
	}
	// check if this persons alliance/corp is on any whitelist
	
	SQLconnect("open");
		$queryAlliance = mysql_query("SELECT * FROM alliances WHERE alliance=\"$fetchAlliance\";");
		$resultAlliance = mysql_num_rows($queryAlliance);
		$queryCorp = mysql_query("SELECT * FROM corporations WHERE corp=\"$fetchCorporation\";");
		$resultCorp = mysql_num_rows($queryCorp);
	SQLconnect("close");

	if ($resultAlliance == 0) {
		// check if this persons corp is in corpList
		if ($resultCorp == 0) {
			// they are not on the alliance list or the corp list, remove their access from Teamspeak 3
			format($fetchCharacterName." of ".$fetchCorporation." is not in the alliance or corp whitelists, trying to remove their access...\n", $fetchCharacterName." of ".$fetchCorporation." is not in the alliance or corp whitelists, trying to remove their access...<br />");
			try {
				// connect to the server
				$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$c->tsname.":".$c->tspass."@".$c->tshost.":".$c->tsport."/?server_port=".$c->tscport);
			} catch (TeamSpeak3_Exception $e) {
				die("Error: ".$e->getMessage()." [C".__LINE__."]");
			}
			// kick them if they are connected
			try {
				$ts3_VirtualServer->clientGetByUid($tsUniqueID)->Kick(TeamSpeak3::KICK_SERVER, "Teamspeak Access Revoked. If this is incorrect contact ".$c->admin.".");
			} catch (TeamSpeak3_Exception $e) {
				if ($c->verbose == true) {
					format("Debug: User could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")\n", "Debug: User could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")<br />");
				}
			}
			// delete the client from TS - DO NOT TEST ON YOURSELF MJ, THIS WOULD BE BAAAAAD
			try {
				$ts3_VirtualServer->clientdeleteDb($tsDatabaseID);
				format("XXX User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."\n", "<img src='images/cross.png' border='0'> User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."<br />");
				// they are gone, remove them from the database
				try {
					$queryDelete = "DELETE FROM users WHERE tsDatabaseID = '".$tsDatabaseID."';";
					mysql_query($queryDelete, $con);
				} catch (TeamSpeak3_Exception $e) {
					format(">>> Warning: Failed to remove: ".$fetchCharacterName." from the database, entry ".$entryID.". You will need to remove manually. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [C".__LINE__."]\n", "Warning: Failed to remove: ".$fetchCharacterName." from the database, entry ".$entryID.". You will need to remove manually. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [C".__LINE__."]<br />");
					$errors = true;
				}
			} catch (TeamSpeak3_Exception $e) {
				format(">>> Warning: Failed to remove access. ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID." (Error: ".$e->getMessage().") [C".__LINE__."]\n", "Warning: Failed to remove access. ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID." (Error: ".$e->getMessage().") [".__LINE__."]<br />");
				$errors = true;
			}			
		} else {
			if ($blue == "Yes" && $fetchCorporation == $c->ourname || $blue == "No" && $fetchCorporation !== $c->ourname) {
				try {
					// connect to the server
					$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$c->tsname.":".$c->tspass."@".$c->tshost.":".$c->tsport."/?server_port=".$c->tscport);
				} catch (TeamSpeak3_Exception $e) {
					die("Error: ".$e->getMessage()." [C".__LINE__."]");
				}
				// kick them if they are connected
				try {
					if ($blue == "Yes") {
						$ts3_VirtualServer->clientGetByUid($tsUniqueID)->Kick(TeamSpeak3::KICK_SERVER, "Welcome to ".$c->ourname.". Please re-register to get your new permissions.");
					} else {
						$ts3_VirtualServer->clientGetByUid($tsUniqueID)->Kick(TeamSpeak3::KICK_SERVER, "You have left ".$c->ourname.". Please re-register to get your new permissions.");
					}
				} catch (TeamSpeak3_Exception $e) {
					if ($c->verbose == true) {
						format("Debug: User could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")\n", "Debug: User could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")<br />");
					}
				}
				// delete the client from TS - DO NOT TEST ON YOURSELF MJ, THIS WOULD BE BAAAAAD
				try {
					$ts3_VirtualServer->clientdeleteDb($tsDatabaseID);
						if ($blue == "Yes") {
							format(">>> User has joined ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."\n", "<img src='images/cross.png' border='0'> User has joined ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."<br />");
						} else {
							format(">>> User has left ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."\n", "<img src='images/cross.png' border='0'> User has left ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."<br />");
						}
					// they are gone, remove them from the database
					try {
						$queryDelete = "DELETE FROM users WHERE tsDatabaseID = '".$tsDatabaseID."';";
						mysql_query($queryDelete, $con);
					} catch (TeamSpeak3_Exception $e) {
						format(">>> Warning: Failed to remove: ".$fetchCharacterName." from the database, entry ".$entryID.". You will need to remove manually. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [C".__LINE__."]\n", ">>> Warning: Failed to remove: ".$fetchCharacterName." from the database, entry ".$entryID.". You will need to remove manually. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [C".__LINE__."]<br />");
						$errors = true;
					}
				} catch (TeamSpeak3_Exception $e) {
					format(">>> Warning: Failed to remove access. ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID." (Error: ".$e->getMessage().") [C".__LINE__."]\n", "Warning: Failed to remove access. ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID." (Error: ".$e->getMessage().") [C".__LINE__."]<br />");
					$errors = true;
				}	
			} else {
				if ($c->verbose == true) {
					format($fetchCharacterName." of ".$fetchCorporation." is on the corporation whitelist, allowing access. No need to do anything.\n", "<img src='images/tick.png' border='0'> ".$fetchCharacterName." of ".$fetchCorporation." is on the corporation whitelist, allowing access. No need to do anything.<br />");
				}
			}
		}
	} else {
		if ($blue == "Yes" && $fetchAlliance == $c->ourname || $blue == "No" && $fetchAlliance !== $c->ourname) {
			try {
				// connect to the server
				$ts3_VirtualServer = TeamSpeak3::factory("serverquery://".$c->tsname.":".$c->tspass."@".$c->tshost.":".$c->tsport."/?server_port=".$c->tscport);
			} catch (TeamSpeak3_Exception $e) {
				die("Error: ".$e->getMessage()." [C".__LINE__."]");
			}
			// kick them if they are connected
			try {
				if ($blue == "Yes") {
					$ts3_VirtualServer->clientGetByUid($tsUniqueID)->Kick(TeamSpeak3::KICK_SERVER, "Welcome to ".$c->ourname.". Please re-register to get your new permissions.");
				} else {
					$ts3_VirtualServer->clientGetByUid($tsUniqueID)->Kick(TeamSpeak3::KICK_SERVER, "You have left ".$c->ourname.". Please re-register to get your new permissions.");
				}
			} catch (TeamSpeak3_Exception $e) {
				if ($c->verbose == true) {
					format("Debug: User could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")\n", "Debug: User could not be kicked. Probably wasn't connected in the first place. (Error: ".$e->getMessage().")<br />");
				}
			}
			// delete the client from TS - DO NOT TEST ON YOURSELF MJ, THIS WOULD BE BAAAAAD
			try {
				$ts3_VirtualServer->clientdeleteDb($tsDatabaseID);
					if ($blue == "Yes") {
						format(">>> User has joined ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."\n", "<img src='images/cross.png' border='0'> User has joined ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."<br />");
					} else {
						format(">>> User has left ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."\n", "<img src='images/cross.png' border='0'> User has left ".$c->ourname." and needs to re-register. User removed: ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID."<br />");
					}
				// they are gone, remove them from the database
				try {
					$queryDelete = "DELETE FROM users WHERE tsDatabaseID = '".$tsDatabaseID."';";
					mysql_query($queryDelete, $con);
				} catch (TeamSpeak3_Exception $e) {
					format(">>> Warning: Failed to remove: ".$fetchCharacterName." from the database, entry ".$entryID.". You will need to remove manually. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [C".__LINE__."]\n", ">>> Warning: Failed to remove: ".$fetchCharacterName." from the database, entry ".$entryID.". You will need to remove manually. (Error: ".$e->getMessage().") (SQL: ". mysql_error() .") [C".__LINE__."]<br />");
					$errors = true;
				}
			} catch (TeamSpeak3_Exception $e) {
				format(">>> Warning: Failed to remove access. ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID." (Error: ".$e->getMessage().") [C".__LINE__."]\n", "Warning: Failed to remove access. ".$fetchCharacterName.". TSUID: ".$tsUniqueID." TSDID: ".$tsDatabaseID." (Error: ".$e->getMessage().") [C".__LINE__."]<br />");
				$errors = true;
			}	
		} else {
			if ($c->verbose == true) {
				format(">>> ".$fetchCharacterName." of ".$fetchAlliance." is on the alliance whitelist, allowing access. No need to do anything.\n", "<img src='images/tick.png' border='0'> ".$fetchCharacterName." of ".$fetchAlliance." is on the alliance whitelist, allowing access. No need to do anything.<br />");
			}
		}
	}
	$runCount = $runCount + 1;
}
format("\n".$runCount." characters checked.\n\n", "<br />".$runCount." characters checked.<br /><br />");
$now = gmdate('jS \of F Y g:ia');
if ($errors == true) {
	echo "<strong>Warning:</strong> Job completed with errors: ".$now." GMT";
} else {
	echo "Job completed without errors: ".$now." GMT";
}
mysql_close($con);
?>