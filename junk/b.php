<?php


$m = new MongoClient();

$db = $m->test;

$collection = $db->people;

// add a record
$data = array("name" => "Nikhil");

$collection->insert($data);

?>
