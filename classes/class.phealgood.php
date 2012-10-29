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

class PhealGood {


	/**
	* @param object $pheal_holder A persistent, empty pheal instance, as this class requires the pheal instance to be set and unset several times
	* @param int $id The ID of a players API key
	* @param string $key The KEY that matches a players API ID
	* @param obj $pheal An empty, but initialized pheal object
	*/
	private static $pheal, $pheal_holder;

	/**
	* Our magic method to ensure a valid pheal object is being passed
	* @param object $pheal The Pheal object being passed
	* @return mixed EXCEPTION is a valid pheal object isn't passed to the constructor, otherwise the object is instantiated.
	*/
	public function __construct($pheal) {

		# Just a runthrough to ensure that we're actually being passed a valid Pheal object.
		try {
			if(!isset($pheal) || !is_object($pheal))
			{
				throw new excetion("PhealGood cannot instantiate. A valid Pheal object was no passed.");
			} else {
				$this->pheal_holder = $pheal;
			}
			
		} catch (Exception $e) {
			return $e;
		}
	}

	/**
	* Validate the submitted API information
	*
	* This function takes no direct parameters. It relies on the magic method having checked for a valid pheal object being passed in
	*
	* @return multi EXCEPTION on failure, TRUE on success
	*/	
	private function Validate_KeyPair() {
			
		# Now, let's see if we can do some stuff with that players API info
		try {

			# Grab the scope (Account, or just Character) of the API info, for now
			$this->pheal				=	$this->pheal_holder;
			$apiScope					=	$this->pheal->accountScope->APIKeyInfo();
			$apiAccountType				=	$apiScope->key->type;
			$apiAccountExpires			=	$apiScope->key->expires;

		} catch (PhealAPIException $e) {
			# return ("Error: ".$e->getCode()." || ".$e->getMessage()." || You probably failed to provide a matching id/key pair. [".__FILE__.":".__LINE__."]");
			return $e;

		} catch (PhealException $e) {
			# return ("Error: Couldn't get your API details from the CCP Server. Error: ".$e->getMessage()." [".__FILE__.":".__LINE__."]");
			return $e;

		}

		try {

			# Ensure that their API Info is of the "Account" type, and not just for a specific character.
			if($apiAccountType !== "Account") {
				throw new exception("Error: Your key isn't an Account key. Please remedy this issue and come back here");

			# Ensure that their API information isn't set to expire
			} else if($apiAccountExpires !== ""){
				throw new exception("Error: Your API information expires. Please ensure that it's set to never expire and try again");

			}

		} catch (Exception $e) {
			return $e;

		}


		# Memory managemnt
		unset($this->pheal);
		
		# We've now ensured that the API Info is a) valid b) has no expiry and c) is an account-wide keypair
		return TRUE;
	}
	
	/**
	* Actually grab the players information from the EvE API servers
	*
	* This function takes no parameters. It's used in beginning to populate an array of character(s) information stored as session data. Exception handling needs to be worked on.
	*
	* @return multi TRUE on success (session data has been populated), Exception upon failure.
	*/	
	public function Get_Character_Info() {

		# Create array of character names.
		try {
			$this->pheal		=	$this->pheal_holder;
			$result				=	$this->pheal->Characters();
		} catch (PhealAPIException $e) {
			# return ("Error: PHEAL Puked. There was an API Issue with the Key/ID Pair. ".$e->getMessage().__FILE__.":".__LINE__."]");
			return $e;
		} catch (PhealException $e) {
			# return ("Couldn't get API Details from server. Error: ".$e->getMessage()."[".__FILE__.":".__LINE__."]");
			return $e;
		}

		# Define a session var that holds a small array (no larger than 3 pairs) of character names...
		$_SESSION['CHARACTERS_ON_ACCOUNT'] = array();

		# For now, let's just make sure we can dump all of the characters tied to the account
		foreach ($result->characters as $apiCharacter) {
			$character	=	$apiCharacter->name;
			$_SESSION['CHARACTERS_ON_ACCOUNT'][$character]	=	array();
		}
		
		# Memory management...
		unset($pheal);
		
		# Create array of character names with the addition of eve related ID's
		try {

			$pheal			=	$this->pheal_holder;
			$pheal->scope	=	"eve";

			foreach ($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key => $value) {
		
				$result = 	$pheal->CharacterID(array("names" => $key));
				$id		=	$result->characters[0]->characterID;
				
				# Create new keypair in our array of characters
				$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['id']	=	(int) $id;
			}

		} catch(PhealHTTPException $e) {
			# echo("Pheal Puked. Error: ".$e->getMessage()."[".__FILE__.":".__LINE__."]");
			return $e;
		} catch(PhealException $e) {
			# echo("Pheal Puked. Error: ".$e->getMessage()."[".__FILE__.":".__LINE__."]");
			return $e;
		}
		
		# Now that we've got a useable array of character names, let's go ahead and populate it with Corp and Alliance ID's
		try {
			foreach ($_SESSION['CHARACTERS_ON_ACCOUNT'] as $key => $value) {

				$result		=	$pheal->eveScope->CharacterInfo(array('characterID' => $value['id']));
				$corp		=	$result->corporation;
				$corpID		=	$result->corporationID;
				$alliance	=	$result->alliance;
				$allianceID	=	$result->allianceID;

				# Populate our array....
				$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['corporation']		=	(string) $corp;
				$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['corporationID']	=	(int) $corpID;
				$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['alliance']		=	(string) $alliance;
				$_SESSION['CHARACTERS_ON_ACCOUNT'][$key]['allianceID']		=	(int) $allianceID;
			}

		} catch(PhealHTTPException $e) {
			# return("Pheal Puked. Error: ".$e->getMessage()."[".__FILE__.":".__LINE__."]");
			return $e;
		} catch(PhealException $e) {
			# return("Pheal Puked. Error: ".$e->getMessage()."[".__FILE__.":".__LINE__."]");
			return $e;
		}
		
		# Memory management and conclusion...
		unset($this->pheal, $this->pheal_holder);
		return TRUE;
	} 
}	

?>
