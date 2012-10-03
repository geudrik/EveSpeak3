<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          
#####	@ File Location:	
#####
#####   @ Script Version:       Version 1.0
#####   @ License:              GPL-3.0  ::  GNU General Public License version 3.0
#####                                   http://www.gnu.org/licenses/gpl-3.0.txt
#####
#####	@ Author:		geudrik
#####   @ Contributors:         geudrik
#####   @ Date:                 Q3 2012 (August)
#####
#####	@ Description		
#####
#####	 This program is free software; you can redistribute it and/or modify
#####	   it under the terms of the GNU General Public License as published by
#####	   the Free Software Foundation; either version 3 of the License, or
#####	   any later version.
#####
#####	   This program is distributed in the hope that it will be useful,
#####	   but WITHOUT ANY WARRANTY; without even the implied warranty of
#####	   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#####	   GNU General Public License for more details.
#####
#####	   You should have received a copy of the GNU General Public License
#####	   along with this program; if not, write to the Free Software Foundation,
#####	   Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
#####
#####
#####		{{  START  }}
#####   
############################################################################################################

# This page will begin by looking for data being posted to it (as well as use sessions to keep track of where it's at, internally).
session_start();

# This page cannot be access directly. Redirect to the login form..
if(!isset($_POST['register_submit'])) { header("Location: index.php?action=register"); }

# Make sure both fields were submitted and that there were no spaces in either of the text boxes..
$_SESSION['API_ID']	=	$_POST['apiID'];
$_SESSION['API_KEY']	=	$_POST['apiToken'];

# Quick sanitization
if(
	strpos(trim($_SESSION['API_ID']), " ") || 
	strpos(trim($_SESSION['API_KEY']), " ") ||
	empty($_SESSION['API_ID']) ||
	empty($_SESSION['API_KEY'])
) { 

	unset($_SESSION['API_ID'], $_SESION['API_KEY']); 
	die("Your ID or your Key either contained spaces or was submitted blank. Try again"); 

}



# Include and initialize Pheal...
include_once("config.php");
include_once("classes/class.html.php");
include_once("pheal/Pheal.php");
$html						=	new HTML;
$c						=	new Config;
spl_autoload_register("Pheal::classload");
PhealConfig::getInstance()->api_base 		=	"https://api.eveonline.com/"; 
PhealConfig::getInstance()->api_customkeys	=	TRUE;
PhealConfig::getInstance()->cache 		= 	new PhealFileCache($c->pheal_cache);
# Create a new pheal that holds the players API info in stasis...
$pheal						=	new Pheal($_SESSION['API_ID'], $_SESSION['API_KEY']);

# This page will be done in steps. Sessions will be used to keep track of the step being request, then destroyed
if(!isset($_SESSION['STEP'])) { # Assume step 1...

	# Now, let's see if we can do some stuff with that players API info
	try {

		# Grab the scope (Account, or just Character) of the API info, for now
		$apiScope				=	$pheal->accountScope->APIKeyInfo();
		$apiAccountType				=	$apiScope->key->type;
		$apiAccountExpires			=	$apiScope->key->expires;

	} catch (PhealAPIException $e) {

		echo("Error: ".$e->getCode()." || ".$e->getMessage()." || You probably failed to provide a matching id/key pair. [".__LINE__."]");
		exit();

	} catch (PhealException $e) {

		echo("Error: Couldn't get your API details from the CCD Server. Error: ".$e->getMessage()." [".__LINE__."]");
		exit();
	}



	# Ensure that their API Info is of the "Account" type, and not just for a specific character.
	if($apiAccountType !== "Account") {

		echo("Error: Your key isn't an Account key. Please remedy this issue and come back here");
		exit();
	}

	# If their key has an expiration date...
	if($apiAccountExpires !== "") {

		echo("Error: Your API information expires. Please ensure that it's set to never expire and try again");
		exit();
	}


	# Create a new pheal so we can begin to get character information.
	#	Once we've got a new pheal, create an array of character names.
	#	We will then redefine the keys as character ID's for use later.
	try {
		$pheal		=	new Pheal($_SESSION['API_ID'], $_SESSION['API_KEY']);
		$result		=	$pheal->Characters();
	} catch (PhealAPIException $e) {

		echo("Error: PHEAL Puked. There was an API Issue with the Key/ID Pair. ".$e->getMessage()."[".__LINE__."]");
		exit();

	} catch (PhealException $e) {

		echo("Couldn't get API Details from server. Error: ".$e->getMessage()."[".__LINE__."]");
		exit();
	}

	# Define a session var that holds a small array (no larger than 3 pairs) of character names...
	$_SESSION['CHARACTERS_ON_ACCOUNT'] = array();

	# For now, let's just make sure we can dump all of the characters tied to the account
	foreach ($result->characters as $apiCharacter) {

		# Now we have a character name, let's grab a character ID to store..
		#$character	=	$apiCharacter->name;
		$character	=	$apiCharacter->name;
		$_SESSION['CHARACTERS_ON_ACCOUNT'][$character]	=	array();

	}


	# Now, we're going to grab character ID's
	#	Once we've got a given characters ID, redefine the index in our array as that ID
	#
	try {

		$pheal		=	new Pheal($_SESSION['API_ID'], $_SESSION['API_KEY'], "eve");
		foreach ($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key => $value) {

			$result = 	$pheal->CharacterID(array("names" => $key));
			$id	=	$result->characters[0]->characterID;
			
			# Create new keypair in our array of characters
			$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['id']	=	$id;


		}


	} catch(PhealHTTPException $e) {
		echo("Pheal Puked. Error: ".$e->getMessage()."[".__LINE__."]");
		exit();
	} catch(PhealException $e) {
		echo("Pheal Puked. Error: ".$e->getMessage()."[".__LINE__."]");
		exit();
	}

print_r($_SESSION);

	# Now that we've got a useable array of character names, let's go ahead and populate it with Corp and Alliance ID's
	try {
		
		# $pheal		=	new Pheal($_SESSION['API_ID'], $_SESSION['API_KEY'], "eve");
		foreach ($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key => $value) {


			$result		=	$pheal->eveScope->CharacterInfo(array('characterID' => $value['id']));
			$corp		=	$result->corporation;
			$corpID		=	$result->corporationID;
			$alliance	=	$result->alliance;
			$allianceID	=	$result->allianceID;

			echo("Assigned Vars: ".$corp."|".$corpID."|".$alliance."|".$allianceID."<br />");

			# Populate our array....
			$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['corporation']		=	$corp;
			$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['corporationID']	=	$corpID;
			$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['alliance']		=	$alliance;
			$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['allianceID']		=	$allianceID;
		}

	} catch(PhealHTTPException $e) {
		echo("Pheal Puked. Error: ".$e->getMessage()."[".__LINE__."]");
		exit();
	} catch(PhealException $e) {
		echo("Pheal Puked. Error: ".$e->getMessage()."[".__LINE__."]");
		exit();
	}


	# For testing purposts, dump our session varaibles
	echo("<br /><br />\$_SESSION variable...<br /><pre>");
	print_r($_SESSION);
	echo("</pre>");
	
	# $_SESSION['STEP'] = 2;
	unset($_SESSION['STEP']);


	#########################################################################################
	#####	Basic character information is now attained. Ask user to select main character
	#########################################################################################
	#####	TODO: Only show characters that fall within the whitelist. Too lazy to that now

	# Time to get the list of characters that actually are allowed to register an account, based on their corpID
	$html->dump_header();
	echo("<div>Please select your main character. If you do not select a character that is in the alliance (or otherwise on the whitelist) your registration will fail.</div>");
	echo("<form action=\"?action=select_character\" method=\"post\">");
	foreach($_SESSION["CHARACTERS_ON_ACCOUNT"] as $key => $value)
	{
		echo("<input type=\"submit\" name=\"".$value['id']."\" value=\"".$key."\" /><br />");
	}
	echo("</form>");
	$html->dump_footer();	


} else if($_SESSION['STEP'] == 2) {

	# We're on to step two, looking at the character supplied to us...
	unset($_SESSION['STEP']);
}
?>
