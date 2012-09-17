<?php
/*
========== * EVE ONLINE TEAMSPEAK BY MJ MAVERICK * ==========
*/
// PHP debug mode
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
// Required files
require_once("TeamSpeak3/TeamSpeak3.php");
require_once("pheal/Pheal.php");
require_once("config.php");
require_once("functions.php");
require_once("version.php");
// Pheal stuff
spl_autoload_register("Pheal::classload");
PhealConfig::getInstance()->api_base = 'https://api.eveonline.com/';
PhealConfig::getInstance()->api_customkeys = true;
$pheal = new Pheal(NULL,NULL);
// Activate config
$c = new Config;
$v = new Version;
$forums_user_id = get_phpbb_user_id($c->phpbb_prefix);
var_dump($forums_user_id);
//--------------------------------------------------------------------------------------------------------
if(isset( $_GET["step"] )) {
	$step = $_GET["step"];
} else {
	$step = 0;
}

if(is_null($forums_user_id)) {
	$step = "9"; // not logged in step
}





echo "
<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title>REPO Web Services | TeamSpeak3 Registration and Forum Authentication</title>
	<style type='text/css'> 
body { 
	background:#000; 
	background-size:750px 12px; 
	background-attachment:fixed; 
	background-position:top center; 
	color:#FFF; 
	display: block; 
	font-size:12px;
	font-family: Helvetica, Arial, Verdana;
	margin-top: 0px; 
	margin-bottom: 0px; 
	margin-left: auto; 
	margin-right: auto; 
	padding:0px; 
} 
		#body {
	background:#000 repeat;
	border:1px dotted #009ac1; 
	-moz-border-radius: 10px; 
	-webkit-border-radius: 10px; 
	width:750px;
}
.frontboxcenter { 
	color: #99AAAA;  
	margin-top: 5px; 
	margin-bottom: 0px; 
	margin-left: auto; 
	margin-right: auto; 
	padding-left:0px; 
	padding-right:0px; 
	width: 740px; 
	line-height: 1.3em; 
} 

.frontboxcenter a:link {
	color: #009ac1;
	text-decoration:none;
} 

.frontboxcenter a:visited {
	color:#009ac1;
	text-decoration:none;
} 

.frontboxcenter a:hover {
	color:#009ac1; 
	text-decoration: underline; 
} 

.frontboxcenter a:active {
	color:#009ac1;
	text-decoration:none;
} 

.frontboxcenter ul {
	padding-bottom:15px;
}

.frontboxcenter li { 
	list-style-type: circle;
	font-size:12px;
	color:#FFF; 
} 

.frontboxcenter li:hover { 
	list-style-type: disc;
	font-size:12px;
	color:#FFF;
}
.buttons-bar {
	width:750px;
	text-align:right;
	padding:4px 20px 0px 0px;
	margin:auto;
}
img.center { 
	display: block; 
	margin-left: auto; 
	margin-right: auto; 
} 
#footer {
	width: 750px;
	margin: auto;
	font-size: 8px;
	padding-left:5px;
} 

	</style>
	<script language='javascript' type='text/javascript'>
		function limitText(limitField, limitCount, limitNum) {
			if (limitField.value.length > limitNum) {
				limitField.value = limitField.value.substring(0, limitNum);
			} else {
				limitCount.value = limitNum - limitField.value.length;
			}
		}
	</script>
</head>
<body>
<a href=\"http://momnpopammoshoppe.com\"><img src=\"header.png\" alt=\"REPO Industries\" class=\"center\" border=\"0\" /></a>
<div id=\"body\" style=\"text-align:left\" class=\"frontboxcenter\">
";

switch($step) {
case 9:

	echo "<div align=\"center\"><br ><br />You are not logged in on the forums. Please do so and come back to this page.<br /><br /></div>";
	break;
	
case 0:
case 1:
	?>
		<form action="?step=2" method="post"><br /><br />
		<table class="frontboxcenter" align="center">
        	<tr>
				<td width="50%" style="text-align: right;">Character  Name:</td>
				<td><input name="inputName" size="30" /></td>
			</tr>
			<tr>
				<td width="50%" style="text-align: right;">Key ID:</td>
				<td><input name="inputID" size="30" /></td>
			</tr>
			<tr>
				<td width="50%" style="text-align: right;">Verification Code:</td>
				<td><textarea name="inputVCode" rows="3" cols="30" onKeyDown="limitText(this.form.inputAPI,this.form.countdown,65);" onKeyUp="limitText(this.form.inputAPI,this.form.countdown,65);"></textarea><br />
				  <span style="text-align: right;">
				  <input name="submit" type="submit" value="Register" />
			    </span></td>
			</tr>
		</table>
		<div align="center">Need your customizable API key? Make one <a href="https://support.eveonline.com/api/Key/Create" target="_blank">here</a>.</div>
		</form>
	<?php
break;
case 2:
	if ($c->verbose == true) {
		echo "<strong>Debug:</strong> Running: Teamspeak 3 PHP Framework version: ".TeamSpeak3::LIB_VERSION."<br /><br />";
	}
	// make sure API is up
	try {
		$testAPI = $pheal->eveScope->CharacterInfo(array("characterID" => $c->TESTID));
		if ($testAPI->characterName == $c->TESTname) {
			echo "API Connection was established.<br /><br />";
		} else {
			echo "API Connection could not be established.<br /> Please try again later, make sure the CCP API server is online first.<br /> If the <u>API</u> server is online and the problem persists please contact your administrator, they likely haven't configured TESTID and TESTname in the config properly.";
			break;
		}
	} catch (PhealException $E) {
		echo "An error occured: ".$E->getMessage()." [".__LINE__."]";
		break;
	}
	// store the forms details and strip any spaces from either side
	$inputName = trim($_POST["inputName"]);
	$inputID = trim($_POST["inputID"]);
	$inputVCode = trim($_POST["inputVCode"]);
	
	// make sure there are no spaces in the API Key	
	$spacebar = " ";
	$spacecheckAPI = strpos($inputVCode, $spacebar);
	if ($spacecheckAPI !== false) {
		echo "Error: Your Verification Code has a space in it. Check your vCode is correct before submitting and be careful when copy and pasting! [".__LINE__."]";
		break;
	}
	// make sure there are no spaces in the User ID
	$spacecheckUID = strpos($inputID, $spacebar);
	if ($spacecheckUID !== false) {
		echo "Error: Your Key ID has a space in it. Check your Key ID is correct before submitting and be careful when copy and pasting! [".__LINE__."]";
		break;
	}
	// make sure the form still has content after removing spaces
	if ($inputName == "" || $inputID == "" || $inputVCode == "") {
		echo "Error: You must fill in all of the form.";
		break;
	}
	// initial SQL security checks
	$me = "Character Name";
	sqlCheckNames($inputName,$me);
	$me = "Key ID";
	sqlCheck($inputID,$me);
	$me = "Verification Code";
	sqlCheck($inputVCode,$me);
	
	// create a new Pheal that holds API ready
	$phealapi = new Pheal($inputID,$inputVCode);
	
	// make sure API is for the account and has no expiry
	try {
		$apiAccount = $phealapi->accountScope->APIKeyInfo();
		$apiAccountType = $apiAccount->key->type;
		$apiAccountExpires = $apiAccount->key->expires;
	} catch (PhealAPIException $E) {
		echo "Error: ".$E->getCode()." ".$E->getMessage()." Most likely cause is that your Key ID doesn't match the Verification Code used. [".__LINE__."]";
		break;
	} catch (PhealException $E) {
		echo "Error: Couldn't get API key details from CCP. (Error: ".$e->getMessage().") [".__LINE__."]";
		break;
	}

	if ($apiAccountType !== "Account") {
		echo "Error: Your API must be an account API (<strong>Character:</strong> All), not a character API. Please update your API key.";
		break;
	}
	if ($apiAccountExpires !== "") {
		echo "Error: Your key cannot have an expiry date. Please update your API key.";
		break;
	}
	
	if ($c->verbose == true) {
	echo "Checking...<br /> Character: $inputName<br /><strong>Debug:</strong> Key ID: $inputID<br /><strong>Debug:</strong> vCode: $inputVCode<br /><br />";
	} else {
		echo "Checking...<br /> Character: $inputName<br /><br />";
	}
	// connect to API and get the characterID of who they are claiming to be
	try {
		$APIcharacterID = $pheal->eveScope->CharacterID(array("names" => $inputName));
		foreach($APIcharacterID->characters  as $character) {
			$characterID = $character->characterID;
		}
		if ($characterID == 0) {
			echo "Error: According to the CCP API server, the character \"".$inputName."\" does not exist.";
			break;
		} else {
			if ($c->verbose == true) {
				echo "<strong>Debug:</strong> Character ID: ".$characterID."<br /><br />";
			}
		}
	} catch (PhealException $e) {
		echo "An error occured: Make sure you have entered your character name correctly. (Error: ".$e->getMessage().") [".__LINE__."]";
		break;
	}
	// connect using the provided API details
	try {
		$APIcharacters = $phealapi->accountScope->Characters();
	} catch (PhealException $e) {
		echo "An error occured: API server couldn't retrieve your account or the API wasn't correct, check for spaces after your entered API. (Error: ".$e->getMessage().") [".__LINE__."]";
		break;
	}
	// scan through the characters on this account 
	try {
		if ($c->verbose == true) {
			echo "<strong>Debug:</strong> Character List:<br />";
		}
		$characterCounter = 0;
		foreach($APIcharacters->characters as $char) {
			if ($c->verbose == true) {
				echo "<strong>Debug:</strong> ".$char->name." [".$char->characterID."]<br />";
			}
			// record all characterIDs for duplicate checks later
			$characterCounter = $characterCounter + 1;
			if ($characterCounter == 1) {
				$accountCharacterID1 = $char->characterID;
			} else if ($characterCounter == 2) {
				$accountCharacterID2 = $char->characterID;
			} else if ($characterCounter == 3) {
				$accountCharacterID3 = $char->characterID;
			} else {
				echo "Something went wrong on line ".__LINE__.", apparently I can't count.<br />";
			}
			
			// if one of the characterIDs on this account match then verify
			if ($char->characterID == $characterID) {
				$character = $char->name;
				$verified = true;
			}
		}
		if ($c->verbose == true) {
			echo "<br />";
		}
	} catch (PhealException $e) {
		echo "An error occured: ".$e->getMessage()." [".__LINE__."]";
		break;
	}
	
	// process the verified (or not) account
	if ($verified == true) {
		// Ok, we are dealing with the owner of the account, lets get this characters corp/alliance
		if ($c->verbose == true) {
			echo "<strong>Debug:</strong> API verified.<br /><br />";
		}
		try {
			$fetch = $pheal->eveScope->CharacterInfo(array('characterID' => $characterID));
			$fetchCorporation = $fetch->corporation;
			$fetchCorporationID = $fetch->corporationID;
			$fetchAlliance = $fetch->alliance;
			$fetchAllianceID = $fetch->allianceID;
		} catch (PhealException $e) {
			echo "An error occured: ".$e->getMessage()." [".__LINE__."]";
			break;
		}
		// CHECK IF THIS CHARACTERS ALLIANCE/CORP IS ON ANY WHITELIST
		SQLconnect("open");
			$queryAlliance = mysql_query("SELECT * FROM alliances WHERE alliance=\"$fetchAlliance\";");
			$resultAlliance = mysql_num_rows($queryAlliance);
			$queryCorp = mysql_query("SELECT * FROM corporations WHERE corp=\"$fetchCorporation\";");
			$resultCorp = mysql_num_rows($queryCorp);
			

		
		SQLconnect("close");
		if ($resultAlliance == 0) {
			// CHECK IF THIS CHARACTERS CORP IS ON THE WHITELIST
			if ($resultCorp == 0) {
				// CHARACTER IS NOT ON ANY WHITELIST
				echo "You are not allowed to register on this server.<br />";
			} else {
				// CHARACTER IS ON OUR CORP WHITELIST
				echo "You are on our corp whitelist<br />";
				if ($fetchCorporation == $c->ourname) {
					// USER IS IN OUR CORP - SET USER GROUP
					$usergroup = $c->group;
					$blue = "No";
				} else {
					// USER IS NOT IN OUR CORP - SET USER GROUP
					$usergroup = $c->bluegroup;
					$blue = "Yes";
				}
				// CONNECT TO PHEAL AND GET CORP TICKER
				if ($c->verbose == true) {
					echo "<strong>Debug:</strong> Getting your corp ticker... ";
				}
				try {
					$corp = $pheal->corpScope->CorporationSheet(array('corporationID' => $fetchCorporationID));
					$corpTicker = $corp->ticker;
				} catch (PhealException $E) {
					echo "An error occured: ".$E->getMessage()." [".__LINE__."]";
					break;
				}
				if ($c->verbose == true) {
					echo $corpTicker."<br />";
				}
				// SET NICKNAME
				if ($c->spacer !== "") {
					$nickname = $corpTicker." ".$c->spacer." ".$character;
				} else {
					$nickname = $corpTicker." ".$character;
				}
				$nickname = substr($nickname, 0, 30); // Teamspeak 3 only allows nicknames of up to 30 characters
				echo "Please connect to Teamspeak 3 <a href=\"ts3server://".$c->tshost."?port=".$c->tscport."&nickname=".$nickname."&addbookmark=".$c->ourname." Teamspeak\">automatially</a> or using the following details:<br /> Address: ".$c->tshost.":".$c->tscport."<br /> Nickname: \"".$nickname."\"<br /><br />Once connected, click register.";
				echo "
				<form method='post' action='?step=3'>
					<input type='hidden' name='blue' value=\"".$blue."\" />
					<input type='hidden' name='characterID' value=\"".$characterID."\" />
					<input type='hidden' name='inputVCode' value=\"".$inputVCode."\" />
					<input type='hidden' name='inputID' value=\"".$inputID."\" />
					<input type='hidden' name='nickname' value=\"".$nickname."\" />
					<input type='hidden' name='usergroup' value=\"".$usergroup."\" />
					<input type='hidden' name='accountCharacterID1' value=\"".$accountCharacterID1."\" />
					<input type='hidden' name='accountCharacterID2' value=\"".$accountCharacterID2."\" />
					<input type='hidden' name='accountCharacterID3' value=\"".$accountCharacterID3."\" />
					<input type='submit' value='Register' />
				</form>
				";
			}
		} else {
			// CHARACTER IS ON OUR ALLIANCE WHITELIST
			echo "You are on our alliance whitelist<br /><br />";
			if ($fetchAlliance == $c->ourname) {
				// USER IS IN OUR ALLIANCE - SET USER GROUP
				$usergroup = $c->group;
				$alliancemate = true;
				$blue = "No";
			} else {
				// USER IS NOT IN OUR ALLIANCE - SET USER GROUP
				$usergroup = $c->bluegroup;
				$alliancemate = false;
				$blue = "Yes";
			}
			// CONNECT TO PHEAL AND GET CORP TICKER
			if ($c->verbose == true) {
				echo "<strong>Debug:</strong> Getting your corp ticker... ";
			}
			try {
				$corpSheet = $pheal->corpScope->CorporationSheet(array('corporationID' => $fetchCorporationID));
				$corpTicker = $corpSheet->ticker;
			} catch (PhealException $E) {
				echo "An error occured: ".$E->getMessage()." [".__LINE__."]";
				break;
			}
			if ($c->verbose == true) {
				echo $corpTicker."<br />";
			}
			// CONNECT TO PHEAL AND GET ALLIANCE TICKER
			if ($c->verbose == true) {
				echo "<strong>Debug:</strong> Getting your alliance ticker... ";
			}
			try {
				$allianceList = $pheal->eveScope->AllianceList();
				foreach($allianceList->alliances as $a) {
					// SKIP IF allianceID DOESN'T MATCH THE ONE WE ARE AFTER
					if($a->allianceID == $fetchAllianceID) {
						$allianceTicker = $a->shortName;
					} else {
						continue;						
					}
				}
			} catch (PhealException $E) {
				echo "An error occured: ".$E->getMessage()." [".__LINE__."]";
				break;
			}
			if ($c->verbose == true) {
				echo $allianceTicker."<br />";
			}
			
			
			
			
			
			
			
			
			
			## Update phpbb user group
			try {
				
				$sql = "SELECT user_id 
						FROM ".$c->phpbb_prefix."users
						WHERE user_id = ".$forums_user_id;
				SQLconnect("open");
				
				$query = mysql_query($sql);
				$result = mysql_num_rows($query);
				
				# We found our username in the forums database
				if($result == 1) {
					
					# $fetchCorporation = $fetch->corporation;
					# $fetchCorporationID = $fetch->corporationID;
					# $fetchAlliance = $fetch->alliance;
					# $fetchAllianceID = $fetch->allianceID;
					# $fetchCharacterID = $fetch->characterID;
					
					$groups = $c->corp_group_ids;
					if(array_key_exists($fetchCorporationID, $groups)) {
						
						# This is a valid corporation!
						$sql_primary_group 	= 	"UPDATE ".$c->phpbb_prefix."users 
												SET group_id=".$groups[$fetchCorporationID]." 
												WHERE user_id=".$forums_user_id." 
												LIMIT 1";
								
						$sql_add_to_group	=	"INSERT INTO ".$c->phpbb_prefix."user_group (group_id, user_id, user_pending)
												VALUES ('$groups[$fetchCorporationID]', '$forums_user_id', '0')";
												
						$sql_force_eve_avatar	=	 "UPDATE ".$c->phpbb_prefix."users
														SET user_avatar = 'https://image.eveonline.com/Character/".$characterID."_256.jpg',
															user_avatar_type = '2',
															user_avatar_width = '256',
															user_avatar_height = '256'
														WHERE user_id = ".$forums_user_id;
						


						
						
						# Lets update their forum information
						mysql_query($sql_primary_group) or die("SQL Failed.<br />Error: ".mysql_error()."<br />Query Run:<br />".$sql_primary_group);
						mysql_query($sql_add_to_group) or die("SQL Failed.<br />Error: ".mysql_error()."<br />Query Run:<br />".$sql_add_to_group);
						mysql_query($sql_force_eve_avatar) or die("SQL Failed.<br />Error: ".mysql_error()."<br />Query Run:<br />".$sql_force_eve_avatar);
						
					} else {
						
						echo "ERROR: Either we hate you, or your corporation isn't allowed to have forum and teamspeak access.";
						break;
					}
					
					
					
					 
					
				} else {
					# We couldn't find our username in the forums database...
					echo"ERROR: Your token couldn't be found. You shouldn't ever see this error...";
				}
				
						
			} catch (SQLError $E) {
				echo "An error occured: ".$E->getMessage()." [".__LINE__."]";
				
			}
			
			
			
			
			
			
			
			
			
			
			// SET NICKNAME
			if ($alliancemate == true) {
				if ($c->spacer !== "") {
					$nickname = $corpTicker." ".$c->spacer." ".$character;
				} else {
					$nickname = $corpTicker." ".$character;
				} 
			} else {
				if ($c->spacer !== "") {
					$nickname = $allianceTicker." ".$c->spacer." ".$corpTicker." ".$c->spacer." ".$character;
				} else {
					$nickname = $allianceTicker." ".$corpTicker." ".$character;
				}
			}
			$nickname = substr($nickname, 0, 30); // Teamspeak 3 only allows nicknames of up to 30 characters
			echo "Please connect to Teamspeak 3 <a href=\"ts3server://".$c->tshost."?port=".$c->tscport."&nickname=".$nickname."&addbookmark=".$c->ourname." Teamspeak\">automatially</a> or using the following details:<br /> Address: ".$c->tshost.":".$c->tscport."<br /> Nickname: \"".$nickname."\"<br /><br />Once connected, click register.";
			echo "
			<form method='post' action='?step=3'>
				<input type='hidden' name='blue' value=\"".$blue."\" />
				<input type='hidden' name='characterID' value=\"".$characterID."\" />
				<input type='hidden' name='inputVCode' value=\"".$inputVCode."\" />
				<input type='hidden' name='inputID' value=\"".$inputID."\" />
				<input type='hidden' name='nickname' value=\"".$nickname."\" />
				<input type='hidden' name='usergroup' value=\"".$usergroup."\" />
				<input type='hidden' name='accountCharacterID1' value=\"".$accountCharacterID1."\" />
				<input type='hidden' name='accountCharacterID2' value=\"".$accountCharacterID2."\" />
				<input type='hidden' name='accountCharacterID3' value=\"".$accountCharacterID3."\" />
				<input type='submit' value='Register' />
			</form>
			";
		}
	} else {
		// COULD NOT VERIFY ACCOUNT HOLDER
		echo "Error: API does not match the character you entered's account. (Denied.)<br />";
	}
break;
case 3:
	$blue = $_POST["blue"];
	$characterID = $_POST["characterID"];
	$inputVCode = $_POST["inputVCode"];
	$inputID = $_POST["inputID"];
	$nickname = $_POST["nickname"];
	$usergroup = $_POST["usergroup"];
	$accountCharacterID1 = $_POST["accountCharacterID1"];
	$accountCharacterID2 = $_POST["accountCharacterID2"];
	$accountCharacterID3 = $_POST["accountCharacterID3"];
	if ($blue == "" || $characterID == "" || $inputVCode == "" || $inputID == "" || $nickname == "" || $usergroup == "") {
		echo "Skipping steps? <a href='index.php'>Go back and try again.</a>";
	} else {
		// TRY TO CONNECT - GATHER DETAILS - GRANT PERMISSIONS - STORE DETAILS
		echo "Attempting to grant access to: $nickname...<br /><br />";
		removeDuplicates($accountCharacterID1, $accountCharacterID2, $accountCharacterID3, $inputVCode, $inputID);
		saveMember("$nickname", $usergroup, $inputID, $inputVCode, $characterID, $blue);
	}
break;
}
$link = magicLink("https://gate.eveonline.com/Profile/MJ%20Maverick","935338328","MJ Maverick");
$link2 = magicLink("https://gate.eveonline.com/Profile/librarat","1963387687","Librarat");
echo "
<div id=\"footer\">
R.E.P.O. Web Services ".$v->release." by ".$link2." | EVEOTS Code by ".$link." | Powered by <a href=\"https://github.com/ppetermann/pheal/\">Pheal</a>
</div>
</div> <!-- Close our open -->

<!-- Buttons div -->
<div class='buttons-bar'>
<a rel=\"nginx\" href=\"http://wiki.nginx.org/Main/\"><img alt=\"Proudly Powered by Nginx\" style=\"border-width:0\" src=\"buttons_nginx.png\" /></a>
<a rel=\"linux\" href=\"http://www.linux.org/\"><img alt=\"Linux Powered!\" style=\"border-width:0\" src=\"buttons_linux.png\" /></a>
<a rel=\"vim\" href=\"http://www.vim.org/\"><img alt=\"VIM - The Improved Editor\" style=\"border-width:0\" src=\"buttons_vim.png\" /></a>

</div>

</body>
</html>
";
?>