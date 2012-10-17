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
	public $encryption_key;
	public $encryption_iv;
	public $encryption_ciphername;
	public $encryption_ciphermode;
	
	public $validation_substr;

	# Teamspeak 3 Server Query Information (REQUIRED)
	public $teamspeak_host;
	public $teamspeak_SAName;
	public $teamspeak_SAPassword;
	public $teamspeak_query_port;
	public $teamspeak_client_port;
	public $teamspeak_alliance_group;
	public $teamspeak_whitelist_group;
	public $teamspeak_ticker_format;
	
	public $secure_cookie;

	# The path that PHEAL can use for caching
	public $pheal_cache 	= "/toolbox/GIT/EveSpeak3/phealcache/";

	private $db_host	=	"localhost";
	private $db_user	=	"dev_user";
	private $db_pass	=	"dev_pass";
	private $db_name	=	"evespeak";

	/**
	* Constructor
	*
	* Magic method to populate our Config class with variables from our database.
	*
	*/
	public function __construct() {

		mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die("Fatal error right off the bat. Pat fails.<br />".mysql_error());
		mysql_select_db($this->db_name);

		$sql	=	"SELECT * FROM config LIMIT 1";
		$result	=	mysql_query($sql) or die("Pat fails... error: ".mysql_error());
		$array	=	mysql_fetch_array($result);

		mysql_close();	

		if(!empty($array)) {

			# Begin to set our session variables
			$this->encryption_key			=	$array['crypto_general_key'];
			$this->encryption_iv			=	$array['crypto_general_iv'];
			$this->encryption_ciphername		=	$array['crypto_ciphername'];
			$this->encryption_ciphermode		=	$array['crypto_ciphermode'];
			$this->validation_substr		=	$array['validation_substr'];
			$this->teamspeak_host			=	$array['teamspeak_host'];
			$this->teamspeak_SAName			=	$array['teamspeak_SAName'];
			$this->teamspeak_SAPassword		=	$array['teamspeak_SAPassword'];
			$this->teamspeak_query_port		=	$array['teamspeak_query_port'];
			$this->teamspeak_client_port		=	$array['teamspeak_client_port'];
			$this->teamspeak_alliance_group		=	$array['teamspeak_alliance_group'];
			$this->teamspeak_whitelist_group	=	$array['teamspeak_whitelist_group'];
			$this->teamspeak_ticker_format		=	$array['teamspeak_ticker_format'];
			$this->secure_cookie			=	$array['secure_cookie'];
		} else { die("There was an error in ".__FILE__." instantiating the Config class."); }
	}


	
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
