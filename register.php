<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          register.php
#####	@ File Location:	/register.php
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

# This is more of a masquerade for phealgood.php
# TODO: Turn phealgood.php into register.functions.php

session_start();

# Load up our includes...
include_once();


switch($_GET['action'])
{
	# Totally vanilla user looking to create an account. Dump API Registration form
	case "register":

		
	break;

	# API Info Grabbing has succeeded. Form submits to this case. Select primary character
	case "select_character":


	break;

	# Character verification (sanitization) has succeeded. Proceed to collecting username and password.
	case "new_user":


	break;

	
	# Nothing matches. Dump API Registration form (assume register)
	default:
	
}

# There should be ZERO html in here.
