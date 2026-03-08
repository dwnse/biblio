<?php

// This file provides a simple hashing utility for the application.

// Function to hash a string using bcrypt
function hashString($string) {
    return password_hash($string, PASSWORD_BCRYPT);
}

// Function to verify a string against a hash
function verifyHash($string, $hash) {
    return password_verify($string, $hash);
}

?>
<?php
echo hashString('admin123');
?>