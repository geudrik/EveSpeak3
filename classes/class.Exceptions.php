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

class evespeakException extends Exception
{

	public function __construct($message, $type = 0, )
	{

	}



}

40 class MySQLException extends Exception
 41 {
 42 
 43         private $exceptionType;
 44 
 45         public function __construct($message,$code=0){
 46 
 47                 // call parent of Exception class
 48                 parent::__construct($message,$code);
 49 
 50                 if($code==1){
 51 
 52                         $this->exceptionType='MySQLException';
 53 
 54                 }
 55 
 56                 elseif($code==2){
 57 
 58                         $this->exceptionType='ResultException';
 59 
 60                 }
 61 
 62                 else{
 63 
 64                         $this->exceptionType='Unknown Exception';
 65 
 66                 }
 67         }
 68 
 69         public function showMySQLExceptionInfo(){
 70                 return 'Catching '.$this->exceptionType.'...<br />Exception message: '.$this->getMessage().'<br />Source filename of exception: '.$this->getFile().'<br />Source line of exception: '.$this->getLine();
 71         }
 72 }

