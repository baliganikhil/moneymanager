<?php

require_once 'constants.php';
require_once 'mongo_services.php';
require_once 'authentication_services.php';

class iou_services {
	
	public function add_iou($data) {
		$m = new MongoWrapper();

		$username = $this->get_username();

		$coll_ious = $m->get_collection(COLL_IOUS);

		$record = $data;
		$record[USERNAME] = $username;

		$record[CREATED_AT] = time();
		$record[MODIFIED_AT] = time();
		$record[CREATED_BY] = $username;
		$record[MODIFIED_BY] = $username;

		if (!array_key_exists("_id", $record)) {
			// Insert
			$r = $coll_ious->insert($record);

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $record;

			echo json_encode($response);

		} else {
			// Update
			$id = new MongoId($record["_id"]);

			$record["_id"] = $id;
			$r = $coll_ious->update(array('_id' => $id), $record);

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $record;

			echo json_encode($response);
		}

	}

	public function get_ious($params) {
		$username = $this->get_username();

		$month = $params[MONTH];
		$year = $params[YEAR];

		$m = new MongoWrapper();
		$coll_ious = $m->get_collection(COLL_IOUS);
		$query = array(USERNAME => $username, MONTH => $month, YEAR => $year);

		// var_dump($query);

		$cursor = $coll_ious->find($query);

		$response = array();
		$response['err'] = NULL;

		$data = array();

		foreach ($cursor as $key => $value) {
			$data[] = $value;
		}
		
		$response['err'] = '';
		$response['ious'] = $data;

		echo json_encode($response);		
	}

	private function get_username() {
		$a = new Authentication();
		$username = $a->get_username();
		return $username;
	}

}

?>