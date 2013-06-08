<?php

require_once 'constants.php';
require 'authentication_services.php';
require 'narration_services.php';
require_once 'tag_services.php';

$a = new Authentication();
$a->security_guard();
unset($a);

const MODE_LOGIN = 'mode_login';

const MODE_GET_NARRATIONS = 'mode_get_narrations';
const MODE_ADD_NARRATION = 'mode_add_narration';
const MODE_DELETE_NARRATION = 'mode_delete_narration';

const MODE_GET_TAGS = 'mode_get_tags';
const MODE_SAVE_TAGS = 'mode_save_tags';

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
	// case MODE_LOGIN:
	// 	log_user_in($request_params);
	// 	break;

	case MODE_GET_NARRATIONS:
		get_narrations($request_params);
		break;

	case MODE_ADD_NARRATION:
		add_narration($request_params);
		break;

	case MODE_DELETE_NARRATION:
		delete_narration($request_params);
		break;

	case MODE_GET_TAGS:
		get_tags();
		break;

	case MODE_SAVE_TAGS:
		save_tags($request_params);
		break;
	
	default:
		echo "No Mode, No Action";
		break;
}

function get_narrations($request_params) {
	$params = json_decode($request_params[DATA_params], true);
	$params[USERNAME] = get_username();

	$n = new narration_services();

	$n->get_narrations($params);
}

function add_narration($request_params) {
	$narration = $request_params[DATA_narration];

	$username = get_username();

	$narration[USERNAME] = $username;

	$n = new narration_services();

	$n->add_narration($narration);

	save_tags($request_params);
}

function delete_narration($request_params) {
	$n = new narration_services();
	$n->delete_narration($request_params);
}

function get_tags() {
	$t = new tag_services();
	$t->get_tags();
}

function save_tags($request_params) {
	$t = new tag_services();
	$tags = $request_params[TAGS];
	$t->save_tags($tags);
}

function get_username() {
	$a = new Authentication();
	return $a->get_username();
}

?>