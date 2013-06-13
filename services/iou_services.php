<?php

require_once 'constants.php';
require_once 'mongo_services.php';
require_once 'authentication_services.php';

class iou_services {

	const GET_OWE = 'get_owe';
	const SHOULD_GET = 'should_get';
	const SHOULD_GIVE = 'should_give';
	
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

		$query = array();
		$query[USERNAME] = $username;
		$query[MONTH] = $month;
		$query[YEAR] = $year;

		if (array_key_exists(self::GET_OWE, $params)) {
			$query[self::GET_OWE] = $params[self::GET_OWE];
		}

		if (array_key_exists(self::SHOULD_GET, $params)) {
			$query['total_amount'] = array('$gt' => 0);
		}

		if (array_key_exists(self::SHOULD_GIVE, $params)) {
			$query['total_amount'] = array('$lt' => 0);
		}


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

	public function delete_iou($params) {
		$m = new MongoWrapper();
		$coll_ious = $m->get_collection(COLL_IOUS);

		$id = new MongoId($params["_id"]);

		$ret = $coll_ious->remove(array("_id" => $id), array("justOne" => true));

		$response = array();
		$response['err'] = $ret == true ? '' : 'Could not delete record';

		echo json_encode($response);

	}

	private function get_username() {
		$a = new Authentication();
		$username = $a->get_username();
		return $username;
	}

}

?>