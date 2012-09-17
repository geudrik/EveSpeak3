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

# First things first, lets load up our objects..
include_once("config.php");
include_once("version.php");
include_once("classes/class.errors.php");
include_once("classes/class.authentication.php");
include_once("classes/class.html.php");

# Initialze our objects
$error		=	new Error;
$auth		=	new Authentication;
$config		=	new Config;
$version	=	new Version;
$html		=	new HTML;

# Start dumping our page..
$html->dump_header();

# Are we performing phpBB authentication as well?
if($config->use_phpbb == TRUE) {

	# User must be logged in on the forums to access this page.
	$err	=	$error->$errors['AUTH_0002'];
	
	# Dump the html of our error
	$html->dump_error($err);

} else {

	$html->dump_apiForm();

}





# Dump our footer
$html->dump_footer();
?>
