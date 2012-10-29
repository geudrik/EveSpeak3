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
	
	/**
	* Database related varaibles. These should be straight forward
	*
	* @param string $db_host The host of the database server
	* @param string $db_user The username for our database connection
	* @param string $db_pass The password for our user to connect to the database
	* @param string $db_name The name of the database we're going to be using
	*/
	private $db_host	=	"localhost";
	private $db_user	=	"dev_user";
	private $db_pass	=	"dev_pass";
	private $db_name	=	"evespeak";
	

	/**
	* Crypto and Security related variables
	*
	* These varaibles are the GLOBAL crypto and security schema. EG: When we're storing data in sessions, we encrypt it. When user data is written to the database, their own IV and Key are used.
	*
	* @param string $encrpytion_key The Global KEY used for encryption
	* @param string $encryption_iv The global IV used for encryption
	* @param string $encryption_ciphername The name of the cipher we want to use. For security, this should stay as Rijndael
	* @param string $encryption_ciphermode The ciphermode we want to use. For security, this should stay as CBC (Cipher-Block Chaining)
	*/
	public $encryption_key;
	public $encryption_iv;
	public $encryption_ciphername;
	public $encryption_ciphermode;
	
	public $validation_substr;

	/**
	* Teamspeak Related Public Variables. All of these variables are pulled from the database
	*
	* @param string $teamspeak_host The host address
	* @param string $teamspeak_SAName The Server-Admin username (the query user)
	* @param string $teamspeak_SAPassword The encrypted server-admin password
	* @param int $teamspeak_query_port The Port that the Query should use to connect to
	* @param int $teamspeak_client_port The port used for actually connecting to the TS server (for clients)
	* @param int $teamspeak_alliance_group The TeamSpeak Group ID that members of the parent (ruling) alliance should be put in to
	* @param int $teamspeak_whitelist_group The TeamSpeak Group ID that users found on the whitelist (renters, for example) should be added to
	* @param bool $teamspeak_ticker_format This is going to be a quickly depreciated variable, but for now lets describe it as the flag for formatting TICKERS in TeamSpeak
	*/
	public $teamspeak_host;
	public $teamspeak_SAName;
	public $teamspeak_SAPassword;
	public $teamspeak_query_port;
	public $teamspeak_client_port;
	public $teamspeak_alliance_group;
	public $teamspeak_whitelist_group;
	public $teamspeak_ticker_format;
	
	/**
	* Misc variables
	*
	* @param boolean $secure_cookie Should we set/use the secure flag when we're using cookies? If TRUE, you must be on an SSL connection, or cookies will never work
	* @param string $pheal_cache The ABSOLUTE path where the Pheal library can cache XML documents
	*/
	public $secure_cookie;

	# The path that PHEAL can use for caching
	public $pheal_cache 	= "/toolbox/GIT/EveSpeak3/phealcache/";


	/**
	* Constructor
	*
	* Magic method to populate our Config class with variables from our database.
	*
	*/
	public function __construct() {

		$m = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		if($m->connect_errno)
		{
			die("Pat fails right off the bat...".$m->connect_error);
		}

		$sql	=	"SELECT * FROM config LIMIT 1";
		$result	=	$m->query($sql);
		$array	=	$result->fetch_array(MYSQLI_ASSOC);

		mysqli_close();	

		if(!empty($array)) {

			# Begin to set our session variables
			$this->encryption_key				=	$array['crypto_general_key'];
			$this->encryption_iv				=	$array['crypto_general_iv'];
			$this->encryption_ciphername		=	$array['crypto_ciphername'];
			$this->encryption_ciphermode		=	$array['crypto_ciphermode'];
			$this->validation_substr			=	$array['validation_substr'];
			$this->teamspeak_host				=	$array['teamspeak_host'];
			$this->teamspeak_SAName				=	$array['teamspeak_SAName'];
			$this->teamspeak_SAPassword			=	$array['teamspeak_SAPassword'];
			$this->teamspeak_query_port			=	$array['teamspeak_query_port'];
			$this->teamspeak_client_port		=	$array['teamspeak_client_port'];
			$this->teamspeak_alliance_group		=	$array['teamspeak_alliance_group'];
			$this->teamspeak_whitelist_group	=	$array['teamspeak_whitelist_group'];
			$this->teamspeak_ticker_format		=	$array['teamspeak_ticker_format'];
			$this->secure_cookie				=	$array['secure_cookie'];
		} else { die("There was an error in ".__FILE__." instantiating the Config class."); }
	}



	/**
	* Quick function to make database connections easier...
	*
	* @param string $var Haven't you heard? I thought everyone had heard... Bird bird bird, the bird is the wo.. No, wait. "connect" is the only string accepted. Empty, or anything else, the connection to the db closes.
	* @return mixed $var If a connetion cannot be established, this function returns FALSE, othewise a vaild connection handle is returned.
	*/
	public function mysql($var) {
		if($var = "connect") {
			$m = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
			if($m->connect_errno)
			{
				# We were unable to initiate a connection.
				return false;
			} else {
				return $m;
			}
		} else {
			mysqli_close();
		}
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
