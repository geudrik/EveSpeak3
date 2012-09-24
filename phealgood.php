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

# This page cannot be access directly. Redirect to the login form..
if(!isset($_POST['register_submit'])) { header("Location: index.php?action=register"); }

# Make sure both fields were submitted and that there were no spaces in either of the text boxes..
$ID	=	$_POST['apiID'];
$Token	=	$_POST['apiToken'];

if(strpos(trim($ID), " ")) { die("Your IP either contained spaces or was submitted blank. Try again"); }
if(strpos(trim($Token), " ")) { die("Your Auth Key contained spaces or was submitted blank. Try again"); }
if(empty($ID) && empty($Token)) { die("Your ID and Key must not be blank"); }

# This page will begin by looking for data being posted to it (as well as use sessions to keep track of where it's at, internally).
session_start();

# Include and initialize Pheal...
include("config.php");
include("pheal/Pheal.php");
sql_autoload_register("Pheal::classload");
PhealConfig::getInstance()->api_base 		=	"https://api.eveonline.com/"; 
PhealConfig::getInstance()->api_customkeys	=	TRUE;

# Create a new pheal that holds the players API info in stasis...
$pheal						=	new Pheal(, NULL);
$c						=	new Config;

# Now, let's see if we can do some stuff with that players API info
try {

	# Grab the scope (Account, or just Character) of the API info
	$apiScope				=	$pheal->accountScope->APIKeyInfo();
	$apiAccountType				=	$apiScope->key->type;
	$apiAccountExpires			=	$apiScope->key->expires;

} catch (PhealAPIException $e) {

	
