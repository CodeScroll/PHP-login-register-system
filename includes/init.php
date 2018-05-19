<?php

require_once 'globalvars.php';

function is_session_started(){

    if ( php_sapi_name() !== 'cli' ){
        
        if( version_compare(phpversion(), '5.4.0', '>=') ){
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        }else{
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

if ( is_session_started() === FALSE ){
    sec_session_start();
}

function sec_session_start(){

    date_default_timezone_set('Europe/Athens');
    $session_hash = 'sha512';
    $session_name = $GLOBALS['cookie_welcome'];
    $secure = false;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    // Force the session to only use cookies, not URL variables.
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    if (ini_set('session.use_only_cookies', 1) === FALSE){
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }

    // Check if hash is available
    if (in_array($session_hash, hash_algos())){
      // Set the has function.
      ini_set('session.hash_function', $session_hash);
    }
    // How many bits per character of the hash.
    // The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
    ini_set('session.hash_bits_per_character', 5);
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();

    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}