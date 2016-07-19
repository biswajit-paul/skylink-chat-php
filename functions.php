<?php
define( 'ABS_PATH', dirname( __FILE__ ) );

// Include DB library
require_once ABS_PATH . '/lib/dbclass/MysqliDb.php';
$db = new MysqliDb ('localhost', 'root', 'b3net', 'db_test');


// Starting Session
if ( is_session_started() === FALSE ) {
	session_start();
}


/**
 * Site URL
 *
 * @return String
 */
function site_url() {
	return 'http://localhost/samples/html/chat1';
}


/**
 * Sets current user
 *
 * @return null
 */
if( isset( $_SESSION['current_user'] ) ) {
	$current_user = $_SESSION['current_user'];
}


/**
 * Check if session is started or not
 *
 * @return bool
 */
function is_session_started() {
	if ( php_sapi_name() !== 'cli' ) {
		if ( version_compare(phpversion(), '5.4.0', '>=') ) {
			return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		} else {
			return session_id() === '' ? FALSE : TRUE;
		}
	}

	return FALSE;
}


/**
 * Function to log user in
 *
 * return bool
 */
function signin_user( $username, $password ) {
	global $db;

	$user = $db->ObjectBuilder()->where('username', $username)->where('password', $password)->getOne('users', 'user_id, username, user_pic');

	if( $db->count > 0 ) {
		$_SESSION['current_user'] = $user;
		$_SESSION['logged_in'] = true;
		return true;
	} else {
		return false;
	}
}


/**
 * Check if user is logged in or not
 *
 * @return bool
 */
function is_user_logged_in() {
	global $db;

	$username = isset( $_SESSION['current_user']->username ) ? $_SESSION['current_user']->username : '';
	$db->where('username', $username)->getOne('users');

	if( $db->count > 0 ) {
		return true;
	} else {
		return false;
	}
}



/**
 * Check if user is logged in or not
 *
 * @return bool
 */
function get_user( $user_id ) {
	global $db;

	$uid = sanitize_int( $user_id );
	$user = $db->ObjectBuilder()->where('user_id', $uid)->getOne('users', 'user_id, username, user_pic');

	return $user;
}




/**
 * Sanitize String
 *
 * @return String
 */
function sanitize_string( $string ) {
	return filter_var( $string, FILTER_SANITIZE_STRIPPED );
}



/**
 * Sanitize Integer
 *
 * @return Integer
 */
function sanitize_int( $string ) {
	return filter_var( $string, FILTER_SANITIZE_NUMBER_INT );
}