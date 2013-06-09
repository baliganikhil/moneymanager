<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html ng-app>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>

	<title>MoneyManager: Login</title>

</head>
<body>

	

	<div class="container" ng-controller="LoginController">

		<div class="span6 well" style="margin-top: 20px;">
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
			    <label class="control-label" for="conformPassword">Confirm Password</label>
			    <div class="controls">
			      <input type="password" id="conformPassword" placeholder="Re-enter Password" ng-model="confirm_password">
			    </div>
			  </div>

			  <div class="control-group">
			  	<div class="controls">
			  		<button type="submit" class="btn btn-primary" ng-click="sign_up_user()">Sign Up</button>
			  	</div>
			  </div>
			  
			  <div class="alert alert-error" ng-show="signup_failed_msg != ''">{{signup_failed_msg}}</div>

			</form>
		</div>
	</div>

	<script type="text/javascript">
		LoginController = function($scope, $http) {
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
		}
	</script>

</body>
</html>