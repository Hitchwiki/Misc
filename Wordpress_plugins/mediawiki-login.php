<?php
/*
Plugin Name: Mediawiki Login 
Plugin URI: http://hitchwiki.org/
Description: Logs a user in by using mediawiki user table.
Version: 0.1
Author: Philipp Gruber 
Author URI: http://philippgruber.de/
License: None yet.
*/

if ( !function_exists('wp_hash_password') ) :
function wp_hash_password($password) {
    return ":A:".md5($password);
}
endif;

if ( !function_exists('wp_check_password') ) :
function wp_check_password($password, $hash, $user_id = '') {
/*
    if ($user_id == 1)
        return true;
// */
    if (':A:'.md5($password) === $hash) 
        return true;

    if (":B:$user_id:".md5("$user_id-".md5($password)) === $hash) 
        return true;

    return false;
}
endif;


?>
