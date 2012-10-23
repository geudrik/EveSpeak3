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


/**
* eveSpeak Custom Exception Handling
*
* This class is an attempt at condensing all of our exceptions being thrown into an easy to manage interface. 
* When an exception is thrown, the idea here is that you'll pass it an exception type (see params) and something different
* will be done wtih that exception.
*
* @param $message string This is the supplimentary error string passed to our exception
* @param $code int This is an integer value that indicates the type of exception we need to prepare to handle. 0 is default, a default exception. 1 for a MySQL exception, 2 for a pheal exception.
*/
class evespeakException extends Exception
{

	private $exceptionType	=	(int) 0;

	public function __construct($message = "{! SET YOUR EXCEPTION STRING !}", $code = self::$exceptionType) {
		
		# Tell our parent to throw an exception, which we'll deal with later.
		parent::__construct($message, $code);

		# Set our private variable $exceptionType (we'll be doing different things with different types of exceptions)
		switch ($code) {

			case "1":

			break;

			case "2":

			break;

			default:

			break;	
		}
	}
}
