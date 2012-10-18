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

$config		=	new Config;
$html		=	new HTML;

print_r($_SESSION);

# Assume step 1 (API Submission) if no step specified in session
if(!isset($_SESSION['REGISTRATION_STEP']) && isset($_POST['register_submit'])) {

	$key	=	trim($_POST['apiToken']);
	$id	=	trim($_POST['apiID']);

	spl_autoload_register("Pheal::classload");
	PhealConfig::getInstance()->api_base 		=	"https://api.eveonline.com/"; 
	PhealConfig::getInstance()->api_customkeys	=	TRUE;
	PhealConfig::getInstance()->cache 		= 	new PhealFileCache($config->pheal_cache);
	$pheal						=	new Pheal($id, $key);

	# Now, the real fun begins. Call our PhealGood class...
	$phealgood					=	new PhealGood($pheal);
	
	if(($result = $phealgood->Get_Character_Info() == TRUE)) {

		$_SESSION['REGISTRATION_STEP']		=	(int) 2;

		foreach($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key => $value) {
			# Give registrant a list of characters, so they can select their main...
			echo("<a href=\"?char=".$value['id']."\">".$key."</a><br />");
		}

	} else {
		die("PhealGood ate shit. oops.");
	}

} else if($_SESSION['REGISTRATION_STEP'] == '2') {

	$char_ID	=	$_GET['char'];

	# We now have a character ID that the aplicant has nominated as their main.
	#	It is now time to reference that CORP ID (in our session varaible) against the whitelist

}
