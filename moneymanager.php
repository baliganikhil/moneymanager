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
<body ng-controller="MoneyController">

	<div class="navbar" ng-cloak>
	  <div class="navbar-inner">
	    <a class="brand" href="#">Black Light</a>
	    <ul class="nav">
	      <li class="active"><a href="#">Home</a></li>
	      <li><a href="ious.php">IOUs</a></li>
	      <li><a href="stats.php">Stats</a></li>
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
			<li ng-click="narration_mode = 'monthly_budget'"><a href="javascript: void(0);"><i class="icon-certificate"></i> Set Monthly Budget</a></li>
		</ul>

	</div>

	<div class="span7">

		<div id="monthly_table" style="border: solid 1px #eee; padding: 15px;" ng-show="narration_mode == 'monthly_table'">

			<div class="well">
				<form class="form-horizontal">
					<select class='all_months' ng-model="mt_month" ng-change="month_changed()" ng-options="m.index as m.month for m in all_months">
					</select>

					<select class='all_years span2' ng-model="mt_year" ng-options="y.index as y.year for y in all_years" ng-change="year_changed()">
					</select>

					<button class="btn btn-success pull-right" id="btn_add_narration" ng-click="btn_add_narration()"><i class="icon-plus icon-white"></i> Item</button>
				</form>
			</div>

			<div>
				<div class="alert" ng-show="monthly_budget == ''">
					You have not set up your monthly budget. <a href="javascript:void(0);" ng-click="narration_mode = 'monthly_budget'">Set it now</a>
				</div>

				<div class="progress progress-danger" id="budget_meter" ng-show="monthly_budget != ''">
				  <div class="bar"></div>
				</div>
			</div>

			<table class="table table-hover" ng-cloak>
				<tr>
					<th>Sl</th>
					<th><a href="javascript:void(0);" ng-click="mt_sort_param = 'date'; mt_sort_reverse = !mt_sort_reverse">Date</a></th>
					<th><a href="javascript:void(0);" ng-click="mt_sort_param = 'narration'; mt_sort_reverse = !mt_sort_reverse">Narration</a></th>
					<th><a href="javascript:void(0);" ng-click="mt_sort_param = 'amount'; mt_sort_reverse = !mt_sort_reverse">Amount</a></th>
					<th></th>
					<th></th>
				</tr>
 <!-- | orderBy:mt_sort_param:mt_sort_reverse -->
				<tr ng-repeat="(sl, each_row) in monthly_data">
					<td>{{sl + 1}}</td>
					<td>{{each_row.full_date}}</td>
					<td>{{each_row.narration}}</td>
					<td ng-class="{label_green: each_row.inc_exp == 'inc', label_red: each_row.inc_exp == 'exp'}">{{each_row.amount}}</td>
					<td><i class="icon-pencil" ng-click="edit_narration(sl)"></i></td>
					<td><i class="icon-remove" ng-click="delete_narration(sl)"></i></td>
				</tr>

				<tr ng-show="monthly_data.length == 0">
					<td colspan="6" style="text-align: center;">
						No data to display
					</td>
				</tr>
			</table>
		</div>

		<div id="monthly_budget" ng-show="narration_mode == 'monthly_budget'" ng-cloak>
			<button type="button" class="close" aria-hidden="true" ng-click="narration_mode = 'monthly_table'">&times;</button>

			<h3>Monthly Budget</h3>

			<table style="width: 75%">
				<tr>
					<td>
						<select class='all_months' ng-options="m.index as m.month for m in all_months" ng-model="budget_month" ng-change="get_monthly_budget()">
						</select>
					</td>

					<td>
						<select class='all_years span2' ng-options="y.index as y.year for y in all_years" ng-model="budget_year" ng-change="get_monthly_budget()">
						</select>
					</td>
				</tr>

				<tr>
					<td>I want to spend NOT more than </td>
					<td><input type="text" class="span2" ng-model="budget_amount"></td>
				</tr>

				<tr>
					<td>Warn me when expenses reach </td>
					<td><input type="text" class="span2" ng-model="budget_warning_amount"></td>
				</tr>

				<tr>
					<td>I want to save at least </td>
					<td><input type="text" class="span2" ng-model="savings_lower_limit"></td>
				</tr>
			</table>

			<div class="btn_toolbar">
				<button class="btn"  ng-click="narration_mode = 'monthly_table'">Cancel</button> 
				<button class="btn btn-primary" ng-click="set_monthly_budget()">Save</button>
			</div>

		</div>

		<div id="add_narration" ng-show="narration_mode == 'add_narration'" ng-cloak>
			<button type="button" class="close" ng-click="narration_mode = 'monthly_table'" aria-hidden="true">&times;</button>

			<h3>Add Item</h3>

			<form class="form" name="FormAddNarration">


				<ul class="nav nav-tabs" id="tabs_add_narration">
				  <li class="active"><a href="#basic">Basic</a></li>
				  <li ng-show="inc_exp == 'exp'"><a href="#tab_investment">Investment</a></li>

				  <li><a href="#tab_tags">Tags</a></li>
				  
				  <li><a href="#more_info">More</a></li>
				</ul>
				 
				<div class="tab-content">
				  <div class="tab-pane active" id="basic">

				  	<input placeholder="Narration" type="text" ng-model="narration" required> <span class="label_help">E.g. Dinner at McDonalds</span> <br>
					<input placeholder="Category" type="text" ng-model="category" required id="narration_category"> <span class="label_help">E.g. Fuel, restaurant, etc.</span>

					<hr>

					<div class="form-horizontal">
						<input placeholder="Amount" type="text" class="span2" ng-model="amount" required>

						<div class="btn-group" data-toggle="buttons-radio">
							<button type="button" class="btn btn-success" ng-click="inc_exp = 'inc'" ng-class="{active: inc_exp == 'inc'}">Income</button>
							<button type="button" class="btn btn-danger" ng-click="inc_exp = 'exp'" ng-class="{active: inc_exp == 'exp'}">Expense</button>
						</div>

						<div ng-show="inc_exp == 'exp'">
							<label class="checkbox"><input type="checkbox" id="avoidable" name="avoidable" ng-model="is_avoidable" value="true"> Avoidable Expense?</label>
						</div>
					</div>

					<hr>

					<div>
						<select class="span2" ng-model="narration_date" required ng-options="d.index as d.date for d in all_days">
						</select>
						<strong>{{all_months[mt_month - 1]['month']}}, {{mt_year}}</strong>
						
						<hr>
						
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

				  </div>
				  
				  <div class="tab-pane" id="tab_investment" ng-show="inc_exp == 'exp'">
				  	<label class="checkbox"><input type="checkbox" id="investment" name="investment" ng-model="is_investment" value="true"> Is Investment?</label>
				  </div>

				  <div class="tab-pane" id="tab_tags">
				  	<h4>Tags help in searching better</h4>
				  	<span class="label_help">Ex: 'child' - to see all expenses on child</span>

				  	<div>
				  		
			  			<div class="input-append">
				  			<input type="text" ng-model="new_tag" name="new_tag" id="new_tag" placeholder="Enter tag"> 
				  			<button type="submit" class="btn btn-success" ng-click="add_new_tag()" ng-disabled="new_tag == undefined"><i class="icon-plus icon-white"></i></button>
				  		</div>
			  		

				  		<ul style="list-style-type: none;">
				  			<li ng-repeat="(key, tag) in narration_tags"><span class="label label-inverse">{{tag}} <i class="icon-remove icon-white" ng-click="remove_narration_tag(key)"></i> </span></li>
				  		</ul>
				  	</div>
				  </div>
				  
				  <div class="tab-pane" id="more_info">
				  	<div>
						<textarea class="span5" rows="3" placeholder="Notes" ng-model="notes"></textarea>
					</div>
				  </div>
				</div>

				<div class="btn_toolbar">
					<button class="btn" ng-click="narration_mode = 'monthly_table'">Cancel</button>
					<button class="btn btn-primary" ng-click="add_narration()" ng-disabled="FormAddNarration.$invalid || inc_exp == ''">Add</button>
				</div>

			</form>

		</div>

	</div>

	<div class="span2 well" ng-cloak>
		<ul class="nav nav-tabs nav-stacked">
			<li><a href="javascript:void(0);" class="label_red">Expenses: {{total_expenses}}</a></li>
			<li><a href="javascript:void(0);" class="label_green">Income: {{total_income}}</a></li>
			<li><a href="javascript:void(0);">Monthly Budget: {{monthly_budget}}</a></li>
		</ul>
	</div>

</div>




	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/moneymanager.js"></script>

	<script type="text/javascript">
		$(function () {
		    $('#tabs_add_narration a').click(function (e) {
		    	e.preventDefault();
  				$(this).tab('show');
		  });
		});
	</script>

	
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