<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          config.php
#####	@ File Location:	config.php
#####
#####   @ Script Version:       Version 1.0
#####   @ License:              GPL-3.0  ::  GNU General Public License version 3.0
#####                                   http://www.gnu.org/licenses/gpl-3.0.txt
#####
#####	@ Author:		geudrik
#####   @ Contributors:         geudrik
#####   @ Date:                 Q3 2012 (August)
#####
#####	@ Description		The global config file for Project Eve-Speak
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

class Config {
	
	# IMPORTANT - Change these values to reflect the same pattern as the example provided. Once changed, NEVER ALTER THEM or EVERYTHING will break.
	# Better yet, TODO: Make install script generate the key and iv, and make the admin update this file accordingly.
	public $encryption_key				=	"&WPVsh<@nubWK!%U3S6eg7h5hE2hgV#Z";
	public $encryption_iv				=	"i2xLQ,=X:Z~3P&8Bf8F&#kds8nla8390";
	public $encryption_ciphername			=	MCRYPT_RIJNDAEL_256;
	public $encrpytion_ciphermode			=	MCRYPT_MODE_CBC;

	# Teamspeak 3 Server Query Information (REQUIRED)
	public $tshost = "localhost";	# TeamSpeak Address for Server Query
	public $tsname = "serveradmin"; # ServerQuery UserName
	public $tspass = "theSApassword";	# ServerQuery Password
	public $tsport = "10011";	# TeamSpeak3 ServerQuery Port
	public $tscport = "9987";	# TeamSpeak client port
	
	# Teamspeak 3 group for alliance/corp members
	public $group = 16;
	
	# Teamspeak 3 group for people on the whitelist
	public $whitelist = 0;
	
	# TeamSpeak3 UserName format.
	# 1 = [SHOP] Librarat
	# 2 = SHOP | Librarat
	public $ticker_format = 1;

	# The path that PHEAL can use for caching
	public $pheal_cache = "/toolbox/GIT/EveSpeak3/phealcache/";





	
	# phpBB Stuff
	# This should be pretty straight forward... 
	public $corp_group_ids = array(

		#corpID 	=>	phpBB Group ID for that corp
    
		164893220 	=> 	8,	# Repo Industries CORP
		98052336 	=> 	9,	# Wormhold Exploration Crew CORP
		536016894 	=> 	10,	# XERCORE CORP
		583396001 	=> 	11,	# United Systems Commonwealth CORP
		890720884 	=> 	12,	# Mom 'n' Pop Ammo Shoppe CORP
		1313553624 	=> 	13,	# Top Snipes School of Engineering CORP
		1405531951 	=> 	14,	# Deep Thoughts INC CORP
		1452329886 	=> 	15,	# Cosmic Cowboys CORP
		98097265 	=> 	16,	# Imprisoned Chaos CORP
		98127028 	=> 	17,	# Straight Up Lazy CORP
		98133741 	=> 	18,	# Phoenix of the Black Sun CORP
	);




	### This section contains information for debugging purposes.

	# Debug Mode? (true/false)
	public $verbose 	= 	false;
	
}
