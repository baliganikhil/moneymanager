<?php

require_once 'constants.php';

class MongoWrapper {

	private function get_db() {
		$m = new MongoClient();
		$db = $m->test;

		return $db;
	}

	public function get_collection($collection_name) {
		$db = $this->get_db();

		switch ($collection_name) {
			case COLL_PEOPLE:
				return $db->people;
				break;

			case COLL_NARRATIONS:
				return $db->narrations;
				break;

			case COLL_TAGS:
				return $db->tags;
				break;

			case COLL_MONTHLY_BUDGET:
				return $db->monthly_budget;
				break;

			case COLL_FRIENDS:
				return $db->friends;
				break;

			case COLL_IOUS:
				return $db->ious;
				break;
			
			default:
				# code...
				break;
		}
	}
}

?>