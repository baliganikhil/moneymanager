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

	<title>MoneyManager: Login</title>

</head>
<body>

	<div class="container" ng-controller="LoginController">

		<div class="span6 well offset3" style="margin-top: 20px;">
			<h2>BLACK LIGHT</h2>
			<h4>Keep track of your money</h4>
			<hr>

			<form class="form-horizontal">
			  <div class="control-group">
			    <label class="control-label" for="inputEmail">Email</label>
			    <div class="controls">
			      <input type="text" id="inputEmail" placeholder="Email" ng-model="username">
			    </div>
			  </div>
			  <div class="control-group">
			    <label class="control-label" for="inputPassword">Password</label>
			    <div class="controls">
			      <input type="password" id="inputPassword" placeholder="Password" ng-model="password">
			    </div>
			  </div>
			  <div class="control-group">
			    <div class="controls">
			      <label class="checkbox">
			        <input type="checkbox" ng-model="remember_me"> Remember me
			      </label>
			      <button type="submit" class="btn btn-primary" ng-click="log_user_in()">Sign in</button>
			      <a href="signup.php" class='btn'>Sign Up</a>
			    </div>
			  </div>
			  <div class="alert alert-error" ng-show="login_failed_msg != ''">{{login_failed_msg}}</div>

			</form>
		</div>
	</div>

	<script type="text/javascript">
		LoginController = function($scope, $http) {
			$scope.login_failed_msg = '';
			
			$scope.log_user_in = function() {
				var data = {
					mode: 'mode_login',
					username: $scope.username,
					password: $scope.password,
					remember_me: $scope.remember_me
				};

				$http.post('services/login_services.php', data).success(
					function(data) {
						// console.log(data); return;
						$scope.login_failed_msg = data['err'];

						if (data['err'] == '') {
							window.location = 'moneymanager.php';
						}
					}).error(
					function() {

					});
			}
		}
	</script>

</body>
</html>