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

	private $base64_encode;

	# Magic method to initialize the crypto parameters we require
	public function __construct($iv, $key, $cipher = MCRYPT_RIJNDAEL_256, $cipherMode = MCRYPT_MODE_CBC, $base64_encode = FALSE) {

		# When we create a new instance of this class, we need our config IV for base decyprtion
		$this->iv		=	$iv;
		$this->key		=	$key;
		$this->cipher		=	$cipher;
		$this->cipherMode	=	$cipherMode;

		$this->base64_encode	=	$base64_encode;
	}


	# Encrypt a string using the variables we set
	public function Encrypt_String($string)
	{

		# Lets torture our processor (encrypt the string)
		$string = mcrypt_encrypt($this->cipher, $this->key, $string, $this->cipherMode, $this->iv);

		# Do we need a non-binary safe return?
		if ($this->base64_encode)
		{
			$string = base64_encode($encryptedData);
		}

		// Return our encrypted data
		return $string;
	}


	# Decrypt a string using the variables we set
	public function Decrypt_String($string)
	{
		# Special Note: The IV used to decrypt the string MUST be the same as the one used to encrypt the string (it's basically a salt)

		# If we're passing a base64 encoded string, we need to decode it first...
		if ($this->base64_encode) 
		{
			$string = base64_decode($string);
		}

		# Torment our processor (Decrypt our string)
		$string= mcrypt_decrypt($this->cipher, $this->key, $string, $this->cipherMode, $this->iv );

		# Return the decrypted data. We need to trim() it to remove padding added during encryption
		return rtrim($string);
	}

} # Close our class
