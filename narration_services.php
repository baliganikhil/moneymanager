<?php

require_once 'constants.php';
require_once 'mongo_services.php';

class narration_services {

	public function add_narration($narration) {
		$m = new MongoWrapper();
		$coll_narrations = $m->get_collection(COLL_NARRATIONS);

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
		$m = new MongoWrapper();
		$coll_narrations = $m->get_collection(COLL_NARRATIONS);

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
}

?>