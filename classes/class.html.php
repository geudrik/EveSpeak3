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

class HTML {


	# Dump our header..
	public function dump_header() {

		include_once("html/header.inc.html");

	}


	# Dump our footer...
	public function dump_footer() {
	
		include_once("html/footer.inc.html");

	}


	# Dump an error out...
	public function dump_error($error = "An unkown error occured") {

		echo("<div class=\"error\">".$error."</div>");
	}


	# Dump generic flavored text
	public function dump_text($string = "Some generic text! Why are you, as user, seeing this? You should probably contact your admin..") {

		echo("<div class=\"text\">".$string."</div>");
	}

	
	# Dump our API form
	public function dump_apiForm() {

		echo("Our API Form would show up here");

	}




}
