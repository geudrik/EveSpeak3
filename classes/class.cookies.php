<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          functions.cookies.php
#####	@ File Location:	/functions.cookies.php
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

# Special Note: OpoenSSL is still buggy as of PHP 5.3.x so we're going to be using the built-in mcrypt modules. This should be updated once OpenSSL support is better documented and implemented.

session_start();

function set_remember_me($username, $usertoken, $secure_cookie = FALSE, $cookie_expiry = 30) {
# This function sets a cookie for "remember me" (automatic login). Passed variables  MUST already be encrypted!
	
	# Create cookie that indicates "Remember Me"
	setcookie("RememberMe", TRUE, ((time()+60*60*24)*$cookie_expiry), $secure_cookie);

	# Set username and authtoken cookies
	setcookie("Username", $username, ((time()+60*60*24*30)*$cookie_expiry), $secure_cookie);
	setcookie("Token", $usertoken, ((time()+60*60*24*30)*$cookie_expiry), $secure_cookie);
}

function validate_remember_me($key) {
# This function attempts to validate a cookie for automagic login. We must already have our crypto class set up.

	

	
		
}
