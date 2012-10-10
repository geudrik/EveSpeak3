<?php

###########################################################################################################
#####   
#####   @ Site:                 arcti.cc
#####	@ Project:		EvE-Speak
#####	@ Alias:		
#####   @ Script Name:          functions.cookies.php
#####	@ File Location:	/functions.cookies.php
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

# Special Note: OpoenSSL is still buggy as of PHP 5.3.x so we're going to be using the built-in mcrypt modules. This should be updated once OpenSSL support is better documented and implemented.

session_start();

function set_remember_me($username, $usertoken, $cipher, $key, $iv, $secure_cookie) {
# This function sets a cookie for "remember me" (automatic login)
	
	# Encrypt our authentication token and the username
	$username	=	mcrypt_encrypt($cipher, $key, $username, NULL, $iv);
	$token		=	mcrypt_encrypt($cipher, $key, $usertoken, NULL, $iv);

	# Create the cookie
	
 public function mcryptEncryptString( $stringToEncrypt, $base64encoded = true )
    {
        // Set the initialization vector
            $iv_size      = mcrypt_get_iv_size( self::MY_MCRYPT_CIPHER, self::MY_MCRYPT_MODE );
            $iv           = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
            $this->lastIv = $iv;

        // Encrypt the data
            $encryptedData = mcrypt_encrypt( self::MY_MCRYPT_CIPHER, self::MY_MCRYPT_KEY_STRING, $stringToEncrypt , self::MY_MCRYPT_MODE , $iv );

        // Data may need to be passed through a non-binary safe medium so base64_encode it if necessary. (makes data about 33% larger)
            if ( $base64encoded ) {
                $encryptedData = base64_encode( $encryptedData );
                $this->lastIv  = base64_encode( $iv );
            } else {
                $this->lastIv = $iv;
            }

        // Return the encrypted data
            return $encryptedData;
    }	
}

function validate_remember_me($key) {
# This function attempts to validate a cookie for automagic login. If successful, session variables become set and TRUE is returned. FALSE otherwise

	
		
}
