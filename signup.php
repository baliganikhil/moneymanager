<?php
if (isset($_COOKIE['username']) && isset($_COOKIE['auth_key'])) {
	header('Location: moneymanager.php');
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html ng-app>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.min.css">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>

	<title>Black Light: Sign Up</title>

</head>
<body>

	<div class="container" ng-controller="SignUpController">

		<div class="span6 well offset2" style="margin-top: 20px;">
			<h2>BLACK LIGHT</h2>
			<h4>Keep track of your money</h4>
			<hr>

			<form class="form-horizontal" name="SignUpForm" ng-cloak>
			  <div class="control-group">
			    <label class="control-label" for="inputEmail">Email</label>
			    <div class="controls">
			      <input type="text" id="inputEmail" placeholder="Email" ng-model="username" ng-pattern="/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/" required>
			    </div>
			  </div>
			  <div class="control-group">
			    <label class="control-label" for="inputPassword">Password</label>
			    <div class="controls">
			      <input type="password" id="inputPassword" placeholder="Password" ng-model="password" required>
			    </div>
			  </div>

			  <div class="control-group">
			    <label class="control-label" for="conformPassword">Confirm Password</label>
			    <div class="controls">
			      <input type="password" id="conformPassword" placeholder="Re-enter Password" ng-model="confirm_password" required ng-change="check_if_passwords_match()">
			    </div>
			  </div>

			  <div class="control-group">
			  	<div class="controls">
			  		<button type="submit" class="btn btn-primary" ng-click="sign_up_user()" ng-disabled="SignUpForm.$invalid">Sign Up</button>
			  		<a href="login.php" class="btn">Sign In</a>
			  	</div>
			  </div>
			  
			  <div class="alert alert-error" ng-show="signup_failed_msg != ''">{{signup_failed_msg}}</div>

			</form>
		</div>
	</div>

	<script type="text/javascript">
		SignUpController = function($scope, $http) {
			$scope.signup_failed_msg = '';
			
			$scope.sign_up_user = function() {
				if ($scope.password != $scope.confirm_password) {
					$scope.signup_failed_msg = 'Passwords don\'t match';
					return;
				}

				var data = {
					mode: 'mode_signup',
					username: $scope.username,
					password: $scope.password
				};

				$http.post('services/login_services.php', data).success(
					function(data) {
						$scope.signup_failed_msg = data['err'];

						if (data['err'] == '') {
							window.location = 'moneymanager.php';
						}
					}).error(
					function() {

					});
			}

			$scope.check_if_passwords_match = function() {
				if ($scope.password != undefined && $scope.password != $scope.confirm_password) {
					$scope.signup_failed_msg = 'Passwords don\'t match';
				} else {
					$scope.signup_failed_msg = '';
				}
			}
		}
	</script>

	<style type="text/css">
		body {

		}
	</style>

	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">

</body>
</html>