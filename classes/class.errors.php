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

class Error {

	public $errors	=	array(

#		error_code	=>	"error text",
		
		# Generic Errors
		"ERROR_001"	=>	"An unknown error occured.",
		"ERROR_002"	=>	"Some required fields were not filled out. Please go back and try again.",
		"ERROR_003"	=>	"You entered an illegal character. Please double check your input and try again.",

		# SQL Errors
		"SQL_0001"	=>	"Could not connect to the database.",
		"SQL_0002"	=>	"The query that was just run died in a fire and the server puked. We're not sure what happened but... Try again?",

		# Authentication error codes
		"AUTH_0001"	=>	"Some phpBB Cookie Information could not be read. Please ensure that you have cookies enabled and try logging back in on the forums.",
		"AUTH_0002"	=>	"You're not very good at following directions. You were instructed to LOG IN once you registered before coming to this page. So, go do that then come back here.",

		# PHEAL related errors
		"PHEAL_000"	=>	"We were unable to get your API information from the server for an unknown reason. Sorry. Please try again later.",
		"PHEAL_001"	=>	"A connection could not be established to the EvE API server. This is either be cause the API server is offline or your admin has a bad test ID/Key. If this problem persists, contact your admin",
		"PHEAL_002"	=>	"Your Authentication code has a space in it somewhere. Please correct this issue and resubmit your request.",
		"PHEAL_003"	=>	"Your Key ID has a space in it somewhere. Please correct this issue and resubmit your request.",
		"PHEAL_004"	=>	"An error was encountered trying to access your API Information. The most likely cause is that your verification code doesn't match your Key ID. Please reverify your information.",
		"PHEAL_005"	=>	"The API Information your entered is for a character, but an Account API is required. Please go make one and try again.",
		"PHEAL_006"	=>	"Your key has an expiration date, but that's not allowed. Please make sure your key doesn't expire and try again.",
		"PHEAL_007"	=>	"According to the API server, the character you're after doesn't exist. Please check the spelling.",
		"PHEAL_008"	=>	"An error occured. Please check the spelling of your character and that your API information is correct.",
		"PHEAL_009"	=>	"The API server couldn't find your account. This probably means your API information was entered wrong. Please try again.",
		"PHEAL_010"	=>	"",

		# Whitelist Errors
		"WHITE_001"	=>	"You character is banned from using the Web Services because you do not show up any whitelists. Fuck off.",
		
	);

}
