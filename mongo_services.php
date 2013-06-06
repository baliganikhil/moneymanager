<?php

const COLL_PEOPLE = 'people';
const COLL_NARRATIONS = 'narrations';

const MONTH = 'month';
const YEAR = 'year';

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