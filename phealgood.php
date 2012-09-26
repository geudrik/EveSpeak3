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
include_once("pheal/Pheal.php");
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
		break;

	} catch (PhealException $e) {

		echo("Error: Couldn't get your API details from the CCD Server. Error: ".$e->getMessage()." [".__LINE__."]");
		break;
	}



	# Ensure that their API Info is of the "Account" type, and not just for a specific character.
	if($apiAccountType !== "Account") {

		echo("Error: Your key isn't an Account key. Please remedy this issue and come back here");
		break;
	}

	# If their key has an expiration date...
	if($apiAccountExpires !== "") {

		echo("Error: Your API information expires. Please ensure that it's set to never expire and try again");
		break;
	}

	# We've made it through basic checks on the account. Because we've set a cache above, we're going to create a new pheal using that cache
	try {
		$pheal		=	new Pheal($_SESSION['API_ID'], $_SESSION['API_KEY']);
		$pheal->scope	=	"eve";
		$result		=	$pheal->Characters()->characters;
	} catch (PhealAPIException $e) {

		echo("Error: PHEAL Puked. There was an API Issue with the Key/ID Pair. ".$e->getMessage()."[".__LINE__."]");
		break;

	} catch (PhealException $e) {

		echo("Couldn't get API Details from server. Error: ".$e->getMessage()."[".__LINE__."]");
		break;
	}

	# Define a session var that holds a small array (no larger than 3 pairs) of character names...
	$_SESSION['CHARACTERS_ON_ACCOUNT'] = array();

	# For now, let's just make sure we can dump all of the characters tied to the account
	foreach ($result as $apiCharacter) {

		# Now we have a character name, let's grab a character ID to store..
		$character	=	$apiCharacter->name;
		echo($character."<br />");
		array_push($_SESSION['CHARACTERS_ON_ACCOUNT'], $character);

	}

	# Well, Pheal is being dumb an won't let me condense these two pieces together. Have to do it separately..
	foreach ($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key=>$value) {

		$result = $pheal->CharacterID(array("names" => $value));
		$id	=	$result->characters[0]->characterID;

		echo $id;
	}
	# For testing purposts, dump our session varaibles
	print_r($_SESSION);
	
	# $_SESSION['STEP'] = 2;

	unset($_SESSION['STEP'])
;
} else if($_SESSION['STEP'] == 2) {

	# We're on to step two, looking at the character supplied to us...
	unset($_SESSION['STEP']);
}
?>
