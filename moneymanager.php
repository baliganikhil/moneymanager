<?php
	require 'authentication_services.php';

	$a = new Authentication();
	$a->security_guard();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html ng-app>
<head>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<script type="text/javascript" src="angular.min.js"></script>
	<script type="text/javascript" src="jquery.js"></script>

</head>
<body ng-controller="MoneyController">

	<div class="navbar">
	  <div class="navbar-inner">
	    <a class="brand" href="#">MoneyManager</a>
	    <ul class="nav">
	      <li class="active"><a href="#">Home</a></li>
	      <li><a href="#">Link</a></li>
	      <li><a href="#">Link</a></li>
	    </ul>

	    <ul class="nav pull-right">
	    	<li><a href="#">Logout</a></li>
	    </ul>
	  </div>
	</div>

<div class="container-fluid" style="margin-top: 20px;">

	<div class="span3">

		<ul class="nav nav-tabs nav-stacked">
			<li class="show_monthly_budget"><a href="javascript: void(0);">Set Monthly Budget</a></li>
		</ul>

	</div>

	<div class="span9">

		<div></div>

		<div id="monthly_table" style="border: solid 1px #eee; padding: 15px;">

			<div class="well">
				<form class="form-horizontal">
					<select class='all_months' ng-model="mt_month" ng-change="month_changed()">
						<option ng-repeat="(key, each_month) in all_months" value="{{key + 1}}">{{each_month}}</option>
					</select>

					<select class='all_years span2' ng-model="mt_year">
						<option ng-repeat="(key, each_year) in all_years" value="{{each_year}}">{{each_year}}</option>
					</select>

					<button class="btn btn-success pull-right" id="btn_add_narration"><i class="icon-plus icon-white"></i> Item</button>
				</form>
			</div>

			<div>
				<div class="alert" ng-show="monthly_budget == ''">
					You have not set up your monthly budget. <a href="javascript:void(0);" class="show_monthly_budget">Set it now</a>
				</div>

				<div class="progress progress-danger" id="budget_meter" ng-show="monthly_budget != ''">
				  <div class="bar"></div>
				</div>
			</div>

			<table class="table table-hover">
				<tr>
					<th>Sl</th>
					<th>Date</th>
					<th>Narration</th>
					<th>Amount</th>
					<th></th>
					<th></th>
				</tr>

				<tr ng-repeat="(sl, each_row) in monthly_data">
					<td>{{sl + 1}}</td>
					<td>{{each_row.full_date}}</td>
					<td>{{each_row.narration}}</td>
					<td ng-class="{label_green: each_row.inc_exp == 'inc', label_red: each_row.inc_exp == 'exp'}">{{each_row.amount}}</td>
					<td><i class="icon-pencil"></i></td>
					<td><i class="icon-remove"></i></td>
				</tr>

				<tr ng-show="monthly_data.length == 0">
					<td colspan="6" style="text-align: center;">
						No data to display
					</td>
				</tr>
			</table>
		</div>


		<div id="monthly_budget" class="row span9 modal hide">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

			<h3>Monthly Budget</h3>

			<table style="width: 75%">
				<tr>
					<td>
						<select class='all_months'>
							<option ng-repeat="(key, each_month) in all_months" value="{{key + 1}}">{{each_month}}</option>
						</select>
					</td>

					<td>
						<select class='all_years span2'>
							<option ng-repeat="(key, each_year) in all_years" value="{{key + 1}}">{{each_year}}</option>
						</select>
					</td>
				</tr>

				<tr>
					<td>I want to spend NOT more than </td>
					<td><input type="text" class="span2"></td>
				</tr>

				<tr>
					<td>I want to save at least </td>
					<td><input type="text" class="span2"></td>
				</tr>
			</table>

			<div class="btn_toolbar">
				<button class="btn">Cancel</button> 
				<button class="btn btn-primary">Save</button>
			</div>

		</div>

	</div>

	<div class="span3 well">
		<ul class="nav nav-tabs nav-stacked">
			<li><a href="javascript:void(0);" class="label_red">Expenses: {{total_expenses}}</a></li>
			<li><a href="javascript:void(0);" class="label_green">Income: {{total_income}}</a></li>
		</ul>
	</div>

</div>



<div id="add_narration" class="modal span6 offset4 row hide">
	
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

	<h3>Add Item</h3>

	<form class="form" name="FormAddNarration">

		<input placeholder="Narration" type="text" ng-model="narration" required> <span class="label_help">E.g. Dinner at McDonalds</span> <br>
		<input placeholder="Category" type="text" ng-model="category" required> <span class="label_help">E.g. Fuel, restaurant, etc.</span>

		<hr>

		<div>
			<input placeholder="Amount" type="text" class="span2" ng-model="amount" required>

			<div class="btn-group" data-toggle="buttons-radio">
				<button type="button" class="btn btn-success" ng-click="inc_exp = 'inc'">Income</button>
				<button type="button" class="btn btn-danger" ng-click="inc_exp = 'exp'">Expense</button>
			</div>
		</div>

		<div>
			<select class="span2" ng-model="narration_date" required>
				<option ng-repeat="(key, each_day) in all_days" value="{{each_day}}">{{each_day}}</option>
			</select>
			<!--input type="text" id="narration_date" placeholder="Date" class="span2" ng-model="narration_date" required--> <strong>{{all_months[mt_month - 1]}}, {{mt_year}}</strong>
			<label class="checkbox"><input type="checkbox" id="recurring" name="recurring" ng-model="add_recurring"> Recurring</label>

			<div ng-show="add_recurring">
				Repeats every <input type="text" class="span1"> 
				<select class="span2">
					<option>Days</option>
					<option>Weeks</option>
					<option>Months</option>
					<option>Years</option>
				</select>
			</div>
		</div>

		<div>
			<textarea class="span6" rows="3" placeholder="Notes" ng-model="notes"></textarea>
		</div>

		<div class="btn_toolbar">
			<button class="btn">Cancel</button>
			<button class="btn btn-primary" ng-click="add_narration()" ng-disabled="FormAddNarration.$invalid || inc_exp == ''">Add</button>
		</div>

	</form>

</div>


<script type="text/javascript">

	// $('#add_narration #narration_date').datepicker({
	// 	format: 'dd/mm/yyyy'
	// });


	MoneyController = function($scope, $http) {
		$scope.all_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November','December'];
		$scope.all_years = ['2013', '2014'];

		var d = new Date();
		var cur_month = d.getMonth() + 1;

		$scope.mt_month = String(cur_month);
		$scope.mt_year = "2013";

		$scope.monthly_budget = 10000;

		// Populate date dropdown
		$scope.populate_days_dd = function() {
			var no_of_days;
			var months_31 = ["1", "3", "5", "7", "8", "10", "12"];
			if (months_31.indexOf($scope.mt_month) != -1) {
				no_of_days = 31;
			} else if (cur_month == "2") {
				no_of_days = 28;
			} else {
				no_of_days = 30;
			}

			$scope.all_days = [];
			for (var i = 1; i <= no_of_days; i++) {
				$scope.all_days.push(i);
			}
		}

		$scope.populate_days_dd();

		$scope.inc_exp = '';

		$scope.month_changed = function() {
			$scope.get_narrations();
			$scope.populate_days_dd();			
		}

		$scope.get_narrations = function() {
			var data = {};
			data['month'] = $scope.mt_month;
			data['year'] = $scope.mt_year;

			var params = {};
			params['mode'] = 'mode_get_narrations';
			params['params'] = data;

			$http({
				method: 'GET',
				url: 'moneymanager_services.php',
				params: params
			}).success(function(data) {

				if (data['err'] == null) {
					$scope.monthly_data = data['data'];
					evaluate_totals_budget();
				}

			});
		}

		function evaluate_totals_budget() {
			$scope.total_income = 0;
			$scope.total_expenses = 0;

			$($scope.monthly_data).each(function(key, cur_month_data) {
				var amount = parseFloat(cur_month_data['amount'], 10);

				if (cur_month_data['inc_exp'] == 'inc') {
					$scope.total_income += amount;
				} else {
					$scope.total_expenses += amount;
				}
			});

			console.log("Income: " + $scope.total_income + "  Expense: " + $scope.total_expenses);

			if ($scope.total_expenses <= $scope.monthly_budget) {
				var width = ($scope.total_expenses * 100) / $scope.monthly_budget;
				width += '%';
				$('#budget_meter > .bar').css('width', width);
			} else {
				// Budget overshot
			}
		}

		$scope.get_narrations();

		$scope.add_narration = function() {
			var date = $scope.narration_date;
			var month = $scope.mt_month
			var year = $scope.mt_year;

			var full_date = date + '/' + month + '/' + year;

			var narration = {
								username: '',
								full_date: full_date,
								date: date,
								month: month,
								year: year,
								narration: $scope.narration,
								category: $scope.category,
								amount: $scope.amount,
								inc_exp: $scope.inc_exp,
								notes: $scope.notes
							};

			var data = {
							mode: 'mode_add_narration',
							narration: narration
						};

			$http({
				method: "POST",
				url: 'moneymanager_services.php',
				data: data
			}).success(function(data) {

				if (data['err'] == null) {
					$scope.monthly_data.push(data['data']);
					$('.modal').modal('hide');
				}
			});
		}

	};

	

	function show_add_narration() {
		$('#add_narration').modal({background: 'static'});
	}

	function show_monthly_budget() {
		$('#monthly_budget').modal({background: 'static'});
	}

	$('#btn_add_narration').on('click', function() {
		show_add_narration();
	});

	$('.show_monthly_budget').on('click', function() {
		show_monthly_budget();
	});


	AddNarrationController = function($scope, $http) {


		// $scope.add_narration = function() {
		// 	console.log($scope.mt_year);
		// 	return;

		// 	var full_date = $scope.full_date;
		// 	split_date = full_date.split('/');

		// 	date = split_date[0];
		// 	month = split_date[1];
		// 	year = split_date[2];

		// 	var narration = {
		// 						username: '',
		// 						full_date: $scope.full_date,
		// 						date: date,
		// 						month: month,
		// 						year: year,
		// 						narration: $scope.narration,
		// 						category: $scope.category,
		// 						amount: $scope.amount,
		// 						inc_exp: $scope.inc_exp,
		// 						notes: $scope.notes
		// 					};

		// 	var data = {
		// 					mode: 'mode_add_narration',
		// 					narration: narration
		// 				};

		// 	$http({
		// 		method: "POST",
		// 		url: 'moneymanager_services.php',
		// 		data: data
		// 	}).success(function(data) {

		// 		if (data['err'] == null) {
		// 			$scope.monthly_data.push(data['data']);
		// 			$('.modal').modal('hide');
		// 		}
		// 	});
		// }
	}
</script>

	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

	
	<style type="text/css">
	#add_narration {
		border: solid 1px #EEE;
		padding: 20px;
	}

	#monthly_budget {
		border: solid 1px #EEE;
		padding: 15px;
	}

	.btn_toolbar {
		margin-top: 15px;
		text-align: right;
	}

	.label_help {
		color: #666 !important;
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