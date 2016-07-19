<?php
require_once 'functions.php';

global $db;

$action = sanitize_string( $_POST['action'] );


if( $action == 'save_message' ) {
	$room_id = sanitize_int( $_POST['rid'] );
	$user_id = sanitize_int( $_POST['uid'] );
	$message = sanitize_string( $_POST['msg'] );

	$data = array(
						'room_id' => $room_id,
						'user_id' => $user_id,
						'message' => $message
					);

	if( $db->insert( 'chat_messages', $data ) ) {
		echo json_encode( array( 'action' => 'success' ) );
	} else {
		echo json_encode( array( 'action' => 'error' ) );
	}

	exit();
}
