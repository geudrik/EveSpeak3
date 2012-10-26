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

# When we instantiate this class, we assign it an iv, key and a cipher via the following method
#	$crypto = new crpyto($iv, $key, $cipher)
class Crypto {

	private $iv;
	private $key;
	private $cipher;
	private $cipherMode;

	private $obj_key	=	12345;
	private $obj_iv		=	abc123;

	/**
	* Magic Instantiation Method
	*
	* When we instantiate this class, we must remember to use __construct to our advantage.
	* All variables passed to __construct should be populated within a session variable, set by the instantiation of Config
	* Instantiate via $class = new Crypto($iv, $key, [optional]$cipher, [optional]$cipherMode);
	*
	* @param string $iv REQUIRED: The Initialization Vector for this session
	* @param string $key REQUIRED: The Key to be used for this session
	* @param string $cipher The Cipher to use (defaults to MRCYPT_RIJNDAEL_256)
	* @param string $cipherMode The encryption mode to use (defaults to MCRYPT_MODE_CBC)
	*
	*/
	public function __construct($iv, $key, $cipher = MCRYPT_RIJNDAEL_256, $cipherMode = MCRYPT_MODE_CBC) {

		$this->iv		=	$iv;
		$this->key		=	$key;
		$this->cipher		=	$cipher;
		$this->cipherMode	=	$cipherMode;
	}

	/**
	* Encrypt a string
	*
	* This function ecnrypts a string, based on the parameters passed to it
	* Note: This function will ALWAYS return something, even if it's not what you want. A string is the returned result. Make sure that when you instantiate this class, you set your varaibles properly!
	* Note 2: It's up to YOU to keep track of what's encrypted and what isn't. For example, if you pass this function an object, it assumes that it's not critical information and as such, uses the pre-defined strings in this class to encrypt and decrypt the data.
	* @since 1.0
	*
	* @param mixed $data The unencrypted data to encrypt
	* @param bool $base64 TRUE or FALSE - ENCODE our encrypted string before returning (DEFAULT: TRUE)
	* @param return string The string passed is now returned as an encrypted string, hopefully base64 encoded to avoid issues.
	*/
	public function Encrypt($data, $base64 = TRUE)
	{

		# If an object, assume non-essential encryption
		if(is_object($data))
		{
			$data = mcrypt_encrypt($this->cipher, $this->obj_key, json_encode($data), $this->cipherMode, $this->obj_iv)
		} else {
			$data = mcrypt_encrypt($this->cipher, $this->key, $string, $this->cipherMode, $this->iv);
		}

		if($base64)
		{
			$data = base64_encode($data);
		}

		// Return our encrypted data
		return $data;
	}


	/**
	* Decrypt a string
	*
	* This function decrypts a string, based on the parameters passed to it
	* Note: This function will ALWAYS return something, even if it's not what you want. A string is the returned result. Make sure that when you instantiate this class, you set your varaibles properly!
	*
	* @since 1.0
	*
	* @param string $string The encrypted string, which CAN be base64 encoded, to decrypt
	* @param bool $base64 TRUE or FALSE - Do we need to DECODE our scring before ecnryption (DEFAULT: TRUE)
	* @param return string The decrypted string is returned in plaintext.
	*
	*/
	public function Decrypt($data, $base64 = TRUE, $object = FALSE)
	{
		# Special Note: The IV used to decrypt the string MUST be the same as the one used to encrypt the string (it's basically a salt)

		if($object)
		{

			if($base64)
			{
				$data = base64_decode($data);
			}

			$data = json_decode(mcrypt_decrypt($this->cipher, $this->obj_key, $data, $this->mode, $this->obj_iv))
			return $data;

		} else {

			if($base64) 
			{
				$data = base64_decode($data);
			}

			# Torment our processor (Decrypt our string)
			$data = mcrypt_decrypt($this->cipher, $this->key, $data, $this->cipherMode, $this->iv );
			return rtrim($data)V
		}

		return FALSE;
	}


}
