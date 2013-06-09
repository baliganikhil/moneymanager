<?php

require_once 'mongo_services.php';
require_once 'constants.php';

class UserServices {
	const COLL_PEOPLE = 'people';

	public function get_password($username) {
		$m = new MongoWrapper();
		$coll_people = $m->get_collection(self::COLL_PEOPLE);

		$t = $coll_people->findone(array(USERNAME => $username), array(PASSWORD));

		return $t[PASSWORD];
	}

	public function add_user($person) {
		// Check if user exists
		$username = $person[USERNAME];

		if ($this->check_if_user_exists($username)) {
			$response = array();
			$response['err'] = "This username has already been taken";

			echo json_encode($response);
			exit();
		} else {
			$password = $person[PASSWORD];
			$timestamp = time();
			
			$person_record = array(USERNAME => $username, 
									PASSWORD => $password, 
									CREATED_BY => $username, 
									MODIFIED_BY => $username,
									CREATED_AT => $timestamp,
									MODIFIED_AT => $timestamp);

			// Insert
			$m = new MongoWrapper();
			$coll_people = $m->get_collection(self::COLL_PEOPLE);
			$r = $coll_people->insert($person_record);

			return true;
		}
	}

	private function check_if_user_exists($username) {
		$m = new MongoWrapper();
		$coll_people = $m->get_collection(self::COLL_PEOPLE);

		$t = $coll_people->findone(array(USERNAME => $username));

		if ($t == NULL) {
			return false;
		} else {
			return true;
		}
	}
}

?>