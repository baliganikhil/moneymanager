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
<body ng-controller="MyAccountController">

	<div class="navbar" ng-cloak>
	  <div class="navbar-inner">
	    <a class="brand" href="#">Black Light</a>
	    <ul class="nav">
	      <li><a href="moneymanager.php">Home</a></li>
	      <li><a href="ious.php">IOUs</a></li>
	      <li><a href="stats.php">Stats</a></li>
	      <li class="active"><a href="#">My Account</a></li>
	    </ul>

	    <ul class="nav pull-right">
	    	<li><a href="#">Signed in as: {{username}}</a></li>
	    	<li ng-click="logout()"><a href="#">Logout</a></li>
	    </ul>
	  </div>
	</div>

	<div class="container">

		<div>
			<h3>Security</h3>

			<div>
				<button class="btn" ng-click="show_change_password = true" ng-hide="show_change_password">Change Password</button>
			</div>

			<div style="margin-top: 10px;" ng-cloak ng-show="show_change_password">
				<form name="ChangePasswordForm">
					<input type="password" placeholder="Current Password" required ng-model="old_password"> <br>
					<input type="password" placeholder="New Password" required ng-model="new_password"> <br>
					<input type="password" placeholder="Reenter New Password" required ng-model="rpt_new_password" ng-change="are_passwords_same()"> <span class="label_help label_red">{{err_msg}}</span> <br>

					<button class="btn">Cancel</button>
					<button class="btn btn-success" type="submit" ng-disabled="ChangePasswordForm.$invalid || new_password == undefined || new_password != rpt_new_password">Save</button>
				</form>
			</div>
		</div>

	</div>

	<script type="text/javascript">
		function MyAccountController($scope) {

			$scope.are_passwords_same = function() {
				if ($scope.new_password != undefined && $scope.rpt_new_password != $scope.new_password) {
					$scope.err_msg = "Passwords don't match";
				} else {
					$scope.err_msg = undefined;
				}
			}

		}
	</script>

	<style type="text/css">
	.label_help {
		color: #666;
		font-size: 11px;
	}

	.label_green {
		color: green;
		font-weight: bold;
	}

	.label_red {
		color: red;
		font-weight: bold;
	}
	</style>

</body>
</html>