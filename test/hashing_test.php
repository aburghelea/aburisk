<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

$username = 'Admin';
$password = '1234';

// A higher "cost" is more secure but consumes more processing power
$cost = 10;

// Create a random salt
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
echo "Initial salt\n";
echo $salt."\n";
// Prefix information about the hash so PHP knows how to verify it later.
// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
$salt = sprintf('$2a$%02d', $cost) . $salt;
echo "Blowfish salt\n";
echo $salt."\n";
// Value:
// $2a$10$VTKNMQysF8cFIgq5XC80VQ==

// Hash the password with the salt

$hash = crypt($password, $salt);
echo "Hashed password\n";
echo $salt."\n";

$rehashed = crypt($password, $hash);

if ( $hash == $hash ) {
    echo "Se pare ca nu e nevoie sa retii salt-ul\n";
} else {
    echo "Nu ai facut ce trebuie \n";
}

?>