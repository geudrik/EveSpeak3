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
	*
	* @since 1.0
	*
	* @param string $string The unencrypted string to encrypt
	* @param bool $base64 TRUE or FALSE - ENCODE our encrypted string before returning (DEFAULT: TRUE)
	*
	*/
	public function Encrypt_String($string, $base64 = TRUE)
	{

		# Lets torture our processor (encrypt the string)
		$string = mcrypt_encrypt($this->cipher, $this->key, $string, $this->cipherMode, $this->iv);

		# Do we need a non-binary safe return?
		if($base64)
		{
			$string = base64_encode($string);
		}

		// Return our encrypted data
		return $string;
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
	*
	*/
	public function Decrypt_String($string, $base64 = TRUE)
	{
		# Special Note: The IV used to decrypt the string MUST be the same as the one used to encrypt the string (it's basically a salt)

		# If we're passing a base64 encoded string, we need to decode it first...
		if($base64) 
		{
			$string = base64_decode($string);
		}

		# Torment our processor (Decrypt our string)
		$string= mcrypt_decrypt($this->cipher, $this->key, $string, $this->cipherMode, $this->iv );

		# Return the decrypted data. We need to trim() it to remove padding added during encryption
		return rtrim($string);
	}

}
