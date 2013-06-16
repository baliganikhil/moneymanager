<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
	require 'services/authentication_services.php';

	$a = new Authentication();
	$a->security_guard();

	$username = $a->get_username();

	echo "<script>var username = '" . $username . "';</script>";
?>


<html ng-app>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.min.css">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>

</head>
<body ng-controller="StatsController">

	<div class="navbar">
	  <div class="navbar-inner">
	    <a class="brand" href="#">Black Light</a>
	    <ul class="nav">
	      <li><a href="moneymanager.php">Home</a></li>
	      <li><a href="ious.php">IOUs</a></li>
	      <li class="active"><a href="#">Stats</a></li>
	      <li><a href="about.php">About</a></li>
	    </ul>

	    <ul class="nav pull-right">
	    	<li><a href="#">Signed in as: {{username}}</a></li>
	    	<li ng-click="logout()"><a href="#">Logout</a></li>
	    </ul>
	  </div>
	</div>

<div class="container-fluid" style="margin-top: 20px;">

	<div class="span3">

		<ul class="nav nav-tabs nav-stacked">
			<li class=""><a href="javascript: void(0);"><i class="icon-search"></i> Search</a></li>
			<li class="" ng-click="get_monthly_report()"><a href="javascript: void(0);"><i class="icon-calendar"></i> Monthly Report</a></li>
		</ul>

	</div>

	<div class="span9">
		<div class="well">

		</div>

		<div class="" id="placeholder"></div>
	</div>

</div>

<script type="text/javascript" src="flot/jquery.flot.js"></script>
<script type="text/javascript" src='js/stats.js'></script>

<style type="text/css">
#placeholder {
	height: 500px;
}
</style>

</body>
</html>