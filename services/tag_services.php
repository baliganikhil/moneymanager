<?php

require_once 'constants.php';
require_once 'mongo_services.php';
require_once 'authentication_services.php';

class tag_services {
	
	public function save_tags($data) {
		$m = new MongoWrapper();

		$username = $this->get_username();

		$coll_tags = $m->get_collection(COLL_TAGS);

		$record = array();
		$record[USERNAME] = $username;
		$record[TAGS] = $data[TAGS];


		$r = $coll_tags->update(array(USERNAME => $username), $record, array('upsert' => true));

		$response = array();

		if ($r) {
			$response['err'] = '';
			$response['tags'] = $data;
		} else {
			$response['err'] = 'Tags could not be saved';
		}

		// echo json_encode($response);

	}

	public function get_tags() {
		$username = $this->get_username();

		$m = new MongoWrapper();
		$coll_tags = $m->get_collection(COLL_TAGS);

		$record = $coll_tags->findOne(array(USERNAME => $username));

		$response = array();

		if ($record == NULL) {
			$response['err'] = '';
			$response[TAGS] = array();
		} else {
			$response['err'] = '';
			$response[TAGS] = $record[TAGS];
		}

		echo json_encode($response);		
	}

	private function get_username() {
		$a = new Authentication();
		$username = $a->get_username();
		return $username;
	}

}

?>