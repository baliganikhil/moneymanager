<?php
error_reporting(E_ALL | E_WARNING | E_NOTICE); ini_set('display_errors', 'On');

require 'authentication_services.php';

const mode_login = 'mode_login';
const MODE_GET_NARRATIONS = 'mode_get_narrations';
const MODE_ADD_NARRATION = 'mode_add_narration';

const DATA_narration = 'narration';
const DATA_params = 'params';
const MODIFIED_BY = 'modified_by';

$request_params = NULL;
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$request_params = file_get_contents('php://input');
	$request_params = json_decode($request_params, true);
	// $request_params = json_encode($request_params);	
} else {
	$request_params = $_REQUEST;	
}

if (!array_key_exists(MODE, $request_params)) {
	exit();
}

$mode = $request_params[MODE];

if ($mode == mode_login) {
	log_user_in($request_params);
}

function log_user_in($request_params) {
	$username = $request_params[USERNAME];
	$password = $request_params[PASSWORD];

	$a = new Authentication();
	$a->log_user_in($username, $password);
}

?>