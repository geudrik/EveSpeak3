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
* @param $previous obj This is an optional (suggested) pre-existing exception to be passed (and parsed and logged) so we can backtrace through the stack
*/
class EvespeakException extends Exception
{

	protected $message	= 'YOU FORGOT TO SET AN EXCEPTION MESSAGE';
	protected $code		= 0;
	protected $previous	= NULL;

	/**
	* Magic Method
	*
	* Our magic method to actually make our exception 
	*/
	public function __construct($message, $code, $previous) 
	{

		$this->message	=	$message;
		$this->code		=	$code;
		$this->previous	=	$previous;
		
		# Tell our parent to throw an exception, which we'll deal with later.
		parent::__construct($this->message, $this->previous);

	}

	/**
	* Function __toString
	*
	* Convert our object to a string, we want to see everything that happened
	*/
	public function __toString() 
	{

		return __CLASS__ . ": [{$this->exceptionType}]: {$this->message}\n";
	}

	/** 
	* Function logException
	*
	* We want to log all exceptions as well as display them, so we can go back through stacks and see what's breaking, where and why (hopefully)
	* Note: We need to make sure that /var/log/evespeak/error.log is writable by our PHP installation.
	*
	* @param $type string The textual representation of what our exception is about. eg: Pheal, PhealAPI, PhealGood, MySQL etc
	* @param return bool TRUE if the file was written into successfully, FALSE if the file could be written to, or opened for that matter.
	*/
	public function logException($type)
	{
		if($handle = fopen("/var/log/evespeak/error.log", rw))
		{
			$exception	=	"\n[".date("Y-m-d H:i:s")."] ".$type." Exception thrown -> \n".$this->__toString();
			if(fwrite($handle, $exception)) { 
				fclose($handle);
				return TRUE; 
			} else {
				fclose($handle);
				return FALSE;
			};
		} else {
			return FALSE;
		}	
	}

	/**
	* Function mysqlException
	*
	* Our custom exception to do something with mysql issues
	*
	* @param object $exception This variable is the exception the parent class threw
	*/
	public function mysqlException($exception)
	{

	}

	/**
	* Function phealgoodException
	*
	* Our custom exception to do something when phealgood pukes.
	*/
	public function phealgoodException()
	{

	}

	/** 
	* Function phealException
	* 
	* Custom function do deal with PHEAL when it pukes
	*/
	public function phealException()
	{

	}

	/**
	* Function phealAPIException
	*
	* Custom function deal with phealAPI issues (eg: bad api info)
	*/
	public function phealAPIException()
	{

	}
	
}

