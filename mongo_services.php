<?php

const COLL_PEOPLE = 'people';
const COLL_NARRATIONS = 'narrations';

const MONTH = 'month';
const YEAR = 'year';

const CREATED_AT = 'created_at';
const MODIFIED_AT = 'modified_at';
const CREATED_BY = 'created_by';
const MODIFIED_BY = 'modified_by';

class MongoWrapper {

	public function get_password($username) {
		$coll_people = $this->get_collection(COLL_PEOPLE);

		$t = $coll_people->findone(array(USERNAME => $username), array(PASSWORD));

		return $t[PASSWORD];
	}

	public function add_narration($narration) {
		$coll_narrations = $this->get_collection(COLL_NARRATIONS);

		if (!array_key_exists("_id", $narration)) {
			// Insert
			$r = $coll_narrations->insert($narration);

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $narration;

			echo json_encode($response);

		} else {
			// Update
		}

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
			$coll_people = $this->get_collection(COLL_PEOPLE);
			$r = $coll_people->insert($person_record);

			return true;
		}
	}

	private function check_if_user_exists($username) {
		$coll_people = $this->get_collection(COLL_PEOPLE);

		$t = $coll_people->findone(array(USERNAME => $username));

		if ($t == NULL) {
			return false;
		} else {
			return true;
		}
	}

	public function get_narrations($params) {
		$coll_narrations = $this->get_collection(COLL_NARRATIONS);

		$query = array(USERNAME => $params[USERNAME], MONTH => $params[MONTH], YEAR => $params[YEAR]);

		$cursor = $coll_narrations->find($query);

		$response = array();
		$response['err'] = NULL;

		$data = array();

		foreach ($cursor as $key => $value) {
			$data[] = $value;
		}
		
		$response['data'] = $data;

		echo json_encode($response);
		
	}

	public function get_montly_data($params) {

	}

	private function get_db() {
		$m = new MongoClient();
		$db = $m->test;

		return $db;
	}

	private function get_collection($collection_name) {
		$db = $this->get_db();

		switch ($collection_name) {
			case COLL_PEOPLE:
				return $db->people;
				break;

			case COLL_NARRATIONS:
				return $db->narrations;
				break;
			
			default:
				# code...
				break;
		}
	}
}

?>