<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          register.php
#####	@ File Location:	/register.php
#####
#####   @ Script Version:       Version 1.0
#####   @ License:              GPL-3.0  ::  GNU General Public License version 3.0
#####                                   http://www.gnu.org/licenses/gpl-3.0.txt
#####
#####	@ Author:		geudrik
#####   @ Contributors:         geudrik
#####   @ Date:                 Q3 2012 (August)
#####
#####	@ Description		This script processes new registrations (go figure) :P
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

session_start();

include_once("config.php");
include_once("pheal/Pheal.php");
include_once("classes/class.phealgood.php");
include_once("classes/class.cookies.php");
include_once("classes/class.crypto.php");
include_once("classes/class.html.php");
include_once("classes/class.Exceptions.php");

$config			=	new Config;
$html			=	new HTML;

print_r($_SESSION);

# Assume step 1 (API Submission) if no step specified in session
if(!isset($_SESSION['REGISTRATION_STEP']) && isset($_POST['register_submit'])) {

	$key	=	trim($_POST['apiToken']);
	$id	=	trim($_POST['apiID']);

	# Initialize a Pheal instance
	spl_autoload_register("Pheal::classload");
	PhealConfig::getInstance()->api_base 		=	"https://api.eveonline.com/"; 
	PhealConfig::getInstance()->api_customkeys	=	TRUE;
	PhealConfig::getInstance()->cache 			= 	new PhealFileCache($config->pheal_cache);
	$pheal										=	new Pheal($id, $key);


	# Now, the real fun begins. Call our PhealGood class...
	$phealgood					=	new PhealGood($pheal);
	$result 					=	$phealgood->Get_Character_Info();
	unset($pheal, $phealgood);

	# We're confident that our phealgood class executed successfully. Now just (for now) dump the session info as a clickable link
	if($result == TRUE) {

		$_SESSION['REGISTRATION_STEP']		=	(int) 2;
		
		# Begin our HTML Dump
		$html->dump_header();
		foreach($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key => $value) {
			# Give registrant a list of characters, so they can select their main...
			echo("<a href=\"?char=".$value['id']."\">".$key."</a><br />");
		}
		$html->dump_footer();
	} else if(is_string($result)) {
		# If $result is a string, phealgood exploded. Now we're just going to dump the error, telling the user to let an admin know (see id ticket)
		die("TODO: Code the rest of this exception handling ".__FILE__."::".__LINE__);
	} else {
		die("Phealgood ate shit for some reason. A thrown exception wasn't caught for some reason");
	}

# Session variable set for step 2 of the registration page. $_GET will be an ID listed in the array of ID's.
} else if($_SESSION['REGISTRATION_STEP'] == '2') {

	$char_ID	=	$_GET['char'];

	# Let's make sure nobody is trying to pass obscure data...
	if(!is_numeric($char_ID) or empty($char_ID))
	{
		# Generate an error page...
		die("Invalid character id. Try again. [".__LINE__."]")
	}

	# We now have a character ID that the aplicant has nominated as their main.
	#	It is now time to reference that CORP ID (in our session varaible) against the whitelist
	try {
		if($m = $config->mysql("connect") == FALSE)
		{
			throw new EvespeakException("Failed to connect to the database.", 120, NULL)
		}
	} catch (EvespeakException $e) {
		EvespeakException->mysqlException($e);
	}

}
