<?php

require_once 'mongo_services.php';
require_once 'user_services.php';
require_once 'constants.php';

const MODE = 'mode';
const MODE_LOGIN = 'mode_login';
const MODE_AUTH = 'mode_auth';

const PASSWORD = 'password';
const AUTH_KEY = 'auth_key';

class Authentication {

	public function check_if_logged_in($params) {
		$username = $params[USERNAME];
		$auth_key = $params[AUTH_KEY];

		if ($username != '' && $auth_key != '' && $this->authenticate_user_auth_key($username, $auth_key)) {
			return true;
		} else {
			header("Location: login.php");
			// $this->clear_cookies();
			return false;
		}
	}

	public function log_user_in($username, $password) {
		$ret = NULL;

		if ($this->authenticate_user_password($username, $password)) {
			$this->save_login_cookies($username);
			$ret = array('err'=> '');			
		} else {
			$ret = array('err'=> 'Invalid username and/or password');
		}

		echo json_encode($ret);
	}

	public function sign_up_user($username, $password) {
		$ret = NULL;

		$auth_key = $this->get_encrypted($username, $password);
		$person = array(USERNAME => $username, PASSWORD => $auth_key);

		$u = new UserServices();
		$u->add_user($person);

		$this->save_login_cookies($username);

		$ret = array('err'=> '');
		echo json_encode($ret);
	}

	public function log_user_out() {
		$this->clear_cookies();

		$ret = array('err'=> '');
		echo json_encode($ret);
	}

	public function security_guard() {
		$cookie_params = array();

		$cookie_params[USERNAME] = array_key_exists(USERNAME, $_COOKIE) ? $_COOKIE[USERNAME] : '';
		$cookie_params[AUTH_KEY] = array_key_exists(AUTH_KEY, $_COOKIE) ? urldecode($_COOKIE[AUTH_KEY]) : '';

		if(!$this->check_if_logged_in($cookie_params)) {
			// header("Location: login.php");
			exit();
		}
	}

	public function get_username() {
		$username = $_COOKIE[USERNAME];

		if ($username == NULL || $username == '') {
			$this->clear_cookies();
			exit();
		}

		return $username;
		
	}

	private function save_login_cookies($username) {
		setcookie(USERNAME, $username, time()+60*60*24*30, "/");

		$auth_key = $this->get_password($username);

		setcookie(AUTH_KEY, $auth_key, time()+60*60*24*30, "/");
	}

	private function clear_cookies() {
		setcookie(USERNAME, "", time() - 3600, "/");
		setcookie(AUTH_KEY, "", time() - 3600, "/");
	}

	private function authenticate_user_auth_key($username, $auth_key) {
		$password = $this->get_password($username);

		if ($password == $auth_key) {
			return true;
		} else {
			return false;
		}
	}

	private function authenticate_user_password($username, $password) {
		$auth_key = $this->get_password($username);

		// echo "{$auth_key} <br>";
		// echo $this->get_encrypted($username, $password) . "<br>";

		if ($this->compare_username_password_authkey($username, $password, $auth_key)) {
			return true;
		} else {
			return false;
		}
	}

	private function get_password($username) {
		$u = new UserServices();
		return $u->get_password($username);
	}

	private function get_encrypted($username, $password) {
		$mix = $username . $password;

		return crypt($mix);
	}

	private function compare_username_password_authkey($username, $password, $auth_key) {
		return crypt($username . $password, $auth_key) == $auth_key;
	}
}	// End of Authentication class

?>