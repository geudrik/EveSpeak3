<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          index.php
#####	@ File Location:	index.php
#####
#####   @ Script Version:       Version 1.0
#####   @ License:              GPL-3.0  ::  GNU General Public License version 3.0
#####                                   http://www.gnu.org/licenses/gpl-3.0.txt
#####
#####	@ Author:		geudrik
#####   @ Contributors:         geudrik
#####   @ Date:                 Q3 2012 (August)
#####
#####	@ Description		Our index page
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

ini_set('error_reporting', E_ALL);
session_start();

if(isset($_SESSION['SESSION_VALID'])) {

	header("Location: usercp.php");
}

# First things first, lets load up our objects..
include_once("config.php");
include_once("version.php");
include_once("classes/class.html.php");
include_once("classes/class.cookies.php");
include_once("classes/class.phealgood.php");

# Initialze our objects
$config		=	new Config;
$version	=	new Version;
$html		=	new HTML;

# Use of phpBB as the user base has been scrapped. We will be using our own backend for user authentication.
#	This allows us much more versatility. Coding in crons to register/deregister is the way to go (allows for flexabiltiy, too)

# If our session isn't valid eg: user hasn't logged in...
if(!isset($_SESSION['SESSION_VALID'])) {
	
	#	Cookie information is as follows...
	#	{name}, {value}, {expire, unix timestamp time()+seconds before expiry, 0 for never}, NULL, NULL, {secure (bool)}, TRUE
	if($_COOKIE['_RESUME_SESSION_'] === TRUE) { # Cookies array has a cookie named _RESUME_SESSION_ whos value is TRUE...
		
		# Cookie found to resume session, lets read their special info from the cookies
		$cookie_user	=	$_COOKIE['_USER_'];
		$cookie_token	=	$_COOKIE['_TOKEN_'];
		
		# Check cookie expiry. If expired, go to login. Otherwise, proceed...
		# Implement database calls to validate cookies and proceed to user panel.
		# Upon validation against the database (hashed auth string read from cookie) forward to UCP.

		# Look for our unique string.. helps with validation that the cookie isn't forged
		if( 
			(substr($cookie_user, 0, 6) != $config->validation_substr) && 
			(substr($cookie_token, 0, 6) != $config->validation_substr) 
		) {

			# We've just checked to make sure that the cookie values contain our secret substring... now lets do cool things with them
			

		} else { header("Location: index.php?action=register"); }

			
	}

	
	# We need to check our action, to see if we've got any special pages to show...
	$action	=	$_GET['action'];

	if($action === "register") {
		$html->dump_header();
		# We need to display the registration form. This form will submit to /phealsgood.php
		$html->dump_registration_form();
		$html->dump_footer();
	} else {
		$html->dump_header();
		# When all else fails, display the login form
		$html->dump_login_form();
		$html->dump_footer();
	}
}	
?>
