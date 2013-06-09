<?php

require_once 'constants.php';
require_once 'mongo_services.php';

class narration_services {

	public function add_narration($narration) {
		$m = new MongoWrapper();
		$a = new Authentication();

		$username = $a->get_username();
		$coll_narrations = $m->get_collection(COLL_NARRATIONS);

		$narration[MODIFIED_BY] = $username;
		$narration[MODIFIED_AT] = time();

		if (!array_key_exists("_id", $narration)) {
			// Insert
			$narration[CREATED_BY] = $username;
			$narration[CREATED_AT] = time();

			$r = $coll_narrations->insert($narration);

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $narration;

			echo json_encode($response);

		} else {
			// Update
			$id = new MongoId($narration["_id"]);

			$narration["_id"] = $id;
			$r = $coll_narrations->update(array('_id' => $id), $narration);

			echo "string";exit();

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $narration;

			echo json_encode($response);
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

	public function delete_narration($params) {
		$m = new MongoWrapper();
		$coll_narrations = $m->get_collection(COLL_NARRATIONS);

		$id = new MongoId($params["_id"]);

		$ret = $coll_narrations->remove(array("_id" => $id), array("justOne" => true));

		$response = array();
		$response['err'] = $ret == true ? '' : 'Could not delete record';

		echo json_encode($response);

	}

	public function get_montly_data($params) {

	}
}

?>