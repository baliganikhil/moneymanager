<?php

require 'mongo_services.php';

const MODE = 'mode';
const MODE_LOGIN = 'mode_login';
const MODE_AUTH = 'mode_auth';

const USERNAME = 'username';
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
		return $_COOKIE[USERNAME];
	}

	private function save_login_cookies($username) {
		setcookie(USERNAME, $username);

		$auth_key = $this->get_password($username);

		// var_dump($auth_key);exit();

		setcookie(AUTH_KEY, $auth_key);
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
		$m = new MongoWrapper();
		return $m->get_password($username);
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