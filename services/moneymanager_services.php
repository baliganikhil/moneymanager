<?php
//*****************************************//
require_once 'constants.php';
require 'authentication_services.php';

$a = new Authentication();
$a->security_guard();
unset($a);
//*****************************************//

const MODE_LOGIN = 'mode_login';

const MODE_GET_NARRATIONS = 'mode_get_narrations';
const MODE_ADD_NARRATION = 'mode_add_narration';
const MODE_DELETE_NARRATION = 'mode_delete_narration';

const MODE_GET_TAGS = 'mode_get_tags';
const MODE_SAVE_TAGS = 'mode_save_tags';

const MODE_GET_MONTHLY_BUDGET = 'mode_get_monthly_budget';
const MODE_SET_MONTHLY_BUDGET = 'mode_set_monthly_budget';

const MODE_GET_MONTHLY_REPORT = 'mode_get_monthly_report';

const MODE_ADD_FRIEND = 'mode_add_friend';
const MODE_GET_FRIENDS = 'mode_get_friends';

const MODE_ADD_IOU = 'mode_add_iou';
const MODE_GET_IOUS = 'mode_get_ious';
const MODE_DELETE_IOU = 'mode_delete_iou';

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
		require_once 'narration_services.php';
		get_narrations($request_params);
		break;

	case MODE_ADD_NARRATION:
		require_once 'narration_services.php';
		require_once 'tag_services.php';
		add_narration($request_params);
		break;

	case MODE_DELETE_NARRATION:
		require_once 'narration_services.php';
		delete_narration($request_params);
		break;

	case MODE_GET_TAGS:
		require_once 'tag_services.php';
		get_tags();
		break;

	case MODE_SAVE_TAGS:
		require_once 'tag_services.php';
		save_tags($request_params);
		break;

	case MODE_SET_MONTHLY_BUDGET:
		require_once 'monthly_budget_services.php';
		set_monthly_budget($request_params);
		break;

	case MODE_GET_MONTHLY_BUDGET:
		require_once 'monthly_budget_services.php';
		get_monthly_budget($request_params);
		break;

	case MODE_GET_MONTHLY_REPORT:
		require_once 'stat_services.php';
		get_monthly_report($request_params);
		break;

	case MODE_ADD_FRIEND:
		require_once 'friend_services.php';
		add_friend($request_params);
		break;

	case MODE_GET_FRIENDS:
		require_once 'friend_services.php';
		get_friends($request_params);
		break;

	case MODE_ADD_IOU:
		require_once 'iou_services.php';
		add_iou($request_params);
		break;

	case MODE_GET_IOUS:
		require_once 'iou_services.php';
		get_ious($request_params);
		break;

	case MODE_DELETE_IOU:
		require_once 'iou_services.php';
		delete_iou($request_params);
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

function set_monthly_budget($request_params) {
	$mbs = new monthly_budget_services();
	$data = $request_params[DATA];
	$mbs->save_budget($data);
}

function get_monthly_budget($request_params) {
	$mbs = new monthly_budget_services();
	$data = json_decode($request_params[DATA], true);
	$mbs->get_budget($data, false);
}

function get_monthly_report($request_params) {
	$ss = new stat_services();
	$data = json_decode($request_params[DATA], true);
	$ss->get_monthly_report($data);
}

function add_friend($request_params) {
	$data = $request_params[DATA];
	$fs = new friend_services();
	$fs->add_friend($data);
}

function get_friends($request_params) {
	$fs = new friend_services();
	$fs->get_friends();
}

function add_iou($request_params) {
	$data = $request_params[DATA];
	$is = new iou_services();
	$is->add_iou($data);
}

function get_ious($request_params) {
	$data = json_decode($request_params[DATA], true);
	$is = new iou_services();
	$is->get_ious($data);
}

function delete_iou($request_params) {
	$is = new iou_services();
	$is->delete_iou($request_params);
}

function get_username() {
	$a = new Authentication();
	return $a->get_username();
}

?>