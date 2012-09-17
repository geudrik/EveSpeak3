<?php
/*
========== * EVE ONLINE TEAMSPEAK BY MJ MAVERICK * ==========
*/
class Config {
	// Administrators character
	public $admin = "Librarat";
	// Database ID of the ROOT admin (from "admins" table), this admin cannot be deleted by a rogue twat. Make it your ID.
	public $adminID = 1;
	// Teamspeak 3 Server Query Information (REQUIRED)
	public $tshost = "localhost";
	public $tsname = "serveradmin";
	public $tspass = "EKlCvnnW";
	public $tsport = "10011"; //ServerQuery Port
	public $tscport = "9987"; //TeamSpeak client port
	// Database Information (REQUIRED)
	public $db_host = "localhost";
	public $db_user = "phpbb_user";
	public $db_pass = "o8826n5q";
	public $db_name = "phpbb";
	// API Connection Testing Information (REQUIRED) - TESTID and TESTname MUST be the characterID and characterName of the SAME character respectively
	public $TESTID = 935338328;
	public $TESTname = "MJ Maverick";
	// Your alliance/corp name
	public $ourname = "R.E.P.O.";
	// Debug Mode? (true/false)
	public $verbose = false;
	// Teamspeak 3 group for alliance/corp members
	public $group = 16;
	// Teamspeak 3 group for people on the whitelist but not in your alliance/corp
	public $bluegroup = 0;
	// Banner Image
	public $banner = "images/banner.jpg";
	// Optional ticker spacers. - Example: For "IRNP | MJ Maverick" use "|".
	public $spacer = "|";
	
	public $phpbb_prefix = "phpbb_";
	
	public $corp_group_ids = array(
									164893220 => 8,       # Repo Industries CORP
									98052336 => 9,        # Wormhold Exploration Crew CORP
									536016894 => 10,      # XERCORE CORP
									583396001 => 11,      # United Systems Commonwealth CORP
									890720884 => 12,      # Mom 'n' Pop Ammo Shoppe CORP
									1313553624 => 13,     # Top Snipes School of Engineering CORP
									1405531951 => 14,     # Deep Thoughts INC CORP
									1452329886 => 15,     # Cosmic Cowboys CORP
									98097265 => 16,       # Imprisoned Chaos CORP
									98127028 => 17,       # Straight Up Lazy CORP
									98133741 => 18,       # Phoenix of the Black Sun CORP
									);

}
?>
