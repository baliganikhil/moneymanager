<?php

require_once 'constants.php';
require_once 'mongo_services.php';
require_once 'authentication_services.php';

class monthly_budget_services {
	public function save_budget($data) {
		$m = new MongoWrapper();

		$username = $this->get_username();

		$coll_monthly_budget = $m->get_collection(COLL_MONTHLY_BUDGET);

		// $record = json_decode($data, true);
		$record = $data;

		$month = $record[MONTH];
		$year = $record[YEAR];
		
		$record[USERNAME] = $username;

		$record[CREATED_AT] = time();
		$record[MODIFIED_AT] = time();
		$record[CREATED_BY] = $username;
		$record[MODIFIED_BY] = $username;

		// var_dump($record); exit();

		$r = $coll_monthly_budget->update(array(USERNAME => $username, MONTH => $month, YEAR => $year), $record, array('upsert' => true));

		$response = array();

		if ($r) {
			$response['err'] = '';
			$response['data'] = $record;
		} else {
			$response['err'] = 'Budget could not be saved';
		}

		echo json_encode($response);

	}

	public function get_budget($params, $should_return) {
		$username = $this->get_username();
		$month = $params[MONTH];
		$year = $params[YEAR];

		if (empty($should_return)) {
			$should_return = false;
		}

		$m = new MongoWrapper();
		$coll_monthly_budget = $m->get_collection(COLL_MONTHLY_BUDGET);

		$record = $coll_monthly_budget->findOne(array(USERNAME => $username, MONTH => $month, YEAR => $year));

		$response = array();

		if ($record == NULL) {
			$response['err'] = '';
			$response['data'] = array();
		} else {
			$response['err'] = '';
			$response['data'] = $record;
		}

		if ($should_return) {
			return $record;
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