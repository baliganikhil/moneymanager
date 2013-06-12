<?php

require_once 'constants.php';
require_once 'mongo_services.php';
require_once 'authentication_services.php';

class friend_services {
	
	public function add_friend($data) {
		$m = new MongoWrapper();

		$username = $this->get_username();

		$coll_friends = $m->get_collection(COLL_FRIENDS);

		$record = $data;
		$record[USERNAME] = $username;

		$record[CREATED_AT] = time();
		$record[MODIFIED_AT] = time();
		$record[CREATED_BY] = $username;
		$record[MODIFIED_BY] = $username;

		if (!array_key_exists("_id", $record)) {
			// Insert
			$r = $coll_friends->insert($record);

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $record;

			echo json_encode($response);

		} else {
			// Update
			$id = new MongoId($record["_id"]);

			$record["_id"] = $id;
			$r = $coll_friends->update(array('_id' => $id), $record);

			$response = array();
			$response['err'] = $r['err'];
			$response['data'] = $record;

			echo json_encode($response);
		}

	}

	public function get_friends() {
		$username = $this->get_username();

		$m = new MongoWrapper();
		$coll_friends = $m->get_collection(COLL_FRIENDS);
		$query = array(USERNAME => $username);

		$cursor = $coll_friends->find($query);

		$response = array();
		$response['err'] = NULL;

		$data = array();

		foreach ($cursor as $key => $value) {
			$data[] = $value;
		}
		
		$response['err'] = '';
		$response['friends'] = $data;

		echo json_encode($response);		
	}

	private function get_username() {
		$a = new Authentication();
		$username = $a->get_username();
		return $username;
	}

}

?>