<?php
require_once 'constants.php';
require_once 'mongo_services.php';
require_once 'authentication_services.php';

class stat_services {

	public function get_monthly_report($params) {
		$m = new MongoWrapper();
		$coll_narrations = $m->get_collection(COLL_NARRATIONS);

		$username = $this->get_username();
		$month = $params[MONTH];
		$year = $params[YEAR];

		$initial = array("inc" => 0, "exp" => 0);
		$keys = array("date" => 1);
		$condition = array(USERNAME => $username, MONTH => $month, YEAR => $year);

		$reduce = <<<EOD
			function(curr, result) {
				if (curr.inc_exp == 'inc') {
					result.inc += parseFloat(curr.amount);
				} else if (curr.inc_exp == 'exp') {
					result.exp += parseFloat(curr.amount);
				}
			};
EOD;

		$g = $coll_narrations->group($keys, $initial, $reduce, $condition);

		$response = $g["retval"];

		echo json_encode($response);
	}

	private function get_username() {
		$a = new Authentication();
		$username = $a->get_username();
		return $username;
	}
	
}

?>