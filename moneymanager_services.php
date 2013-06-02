<?php

require 'authentication_services.php';

$a = new Authentication();
$a->security_guard();
unset($a);

const MODE_GET_NARRATIONS = 'mode_get_narrations';
const MODE_ADD_NARRATION = 'mode_add_narration';
const DATA_narration = 'narration';
const DATA_params = 'params';

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

switch ($mode) {
	case MODE_GET_NARRATIONS:
		get_narrations($request_params);
		break;

	case MODE_ADD_NARRATION:
		add_narration($request_params);
		break;
	
	default:
		echo "No Mode, No Action";
		break;
}

function get_narrations($request_params) {
	$params = json_decode($request_params[DATA_params], true);
	$params[USERNAME] = get_username();

	$m = new MongoWrapper();

	$m->get_narrations($params);
}

function add_narration($request_params) {
	$narration = $request_params[DATA_narration];
	$narration[USERNAME] = get_username();

	$m = new MongoWrapper();

	$m->add_narration($narration);
}

function get_username() {
	$a = new Authentication();
	return $a->get_username();
}

?>