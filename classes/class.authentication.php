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

class Authentication {
	
	# This function attempts to register a new user, based on API information to our LOCAL ONLY database


	# This function returns an array of cookie information on success, FALSE on failure
	public function phpBB_is_logged_in() {

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

}
