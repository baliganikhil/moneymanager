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

<body ng-controller="ADDIOUController">

	<div class="navbar" ng-cloak>
	  <div class="navbar-inner">
	    <a class="brand" href="#">Black Light</a>
	    <ul class="nav">
	      <li><a href="moneymanager.php">Home</a></li>
	      <li class="active"><a href="#">IOUs</a></li>
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
			<li class="add_friend" ng-click="btn_add_friend()"><a href="javascript: void(0);"><i class="icon-user"></i> Add Friend</a></li>
		</ul>
	</div>

	<div class="span7">
		<div id="monthly_ious" style="border: solid 1px #eee; padding: 15px;">
			<div class="well hide">
				<button class="btn"><i class="icon-chevron-left"></i> Go Back</button> 
				<button class="btn btn-success">People who owe me</button> 
				<button class="btn btn-danger">People whom I owe</button> 
				<hr>
				<form class="form-search">
				  <div class="input-append">
				    <input type="text" class="span5 search-query" placeholder="Search">
				    <button type="submit" class="btn">Search</button>
				  </div>
				</form>
			</div>

		</div>

		<div id="monthly_ious" style="border: solid 1px #eee; padding: 15px;">
			<div class="well">
				<form class="form-horizontal">
					<select class='all_months' ng-model="mt_month" ng-change="month_changed()" ng-options="m.index as m.month for m in all_months">
					</select>

					<select class='all_years span2' ng-model="mt_year" ng-options="y.index as y.year for y in all_years">
					</select>

					<button class="btn btn-success pull-right" id="btn_add_iou" ng-click="btn_add_iou()"><i class="icon-plus icon-white"></i> IOU</button>
				</form>
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

				<tr ng-repeat="(sl, each_row) in monthly_data" ng-cloak ng-class="{struck: each_row.total_amount == 0}">
					<td>{{sl + 1}}</td>
					<td>{{each_row.full_date}}</td>
					<td>{{each_row.narration}}</td>
					<td ng-class="{label_green: each_row.total_amount > 0, label_red: each_row.total_amount < 0}">{{each_row.total_amount}}</td>
					<td><i class="icon-pencil" ng-click="edit_iou(sl)"></i></td>
					<td><i class="icon-remove" ng-click="delete_iou(sl)"></i></td>
				</tr>

				<tr ng-show="monthly_data.length == 0">
					<td colspan="6" style="text-align: center;">
						No data to display
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="span2 well">
		<ul class="nav nav-tabs nav-stacked">
			<li><a href="javascript:void(0);" class="label_red">Expenses</a></li>
			<li><a href="javascript:void(0);" class="label_green">Income</a></li>
			<li><a href="javascript:void(0);">Monthly Budget</a></li>
		</ul>
	</div>

</div>


<div id="add_iou" class="modal hide">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

	<h3>Add IOU</h3>

	<form name="AddIOU">

		<ul class="nav nav-tabs" id="tabs_add_narration">
		  <li class="active"><a href="#basic">Basic</a></li>
		  <li><a href="#tab_tags">Tags</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="basic">

				<table class="table">
					<tr>
						<td>
							<div class="btn-group" data-toggle="buttons-radio">
								<button type="button" class="btn btn-success" ng-click="get_owe = 'get'" ng-class="{active: get_owe == 'get'}">I Have To Get Money</button>
								<button type="button" class="btn btn-danger" ng-click="get_owe = 'owe'" ng-class="{active: get_owe == 'owe'}">I Owe Money</button>
							</div>
						</td>

						<td>
							<select class="span1" ng-model="date" required ng-options="d.index as d.date for d in all_days">
							</select>
							<strong>{{all_months[mt_month - 1]['month']}}, {{mt_year}}</strong>
						</td>
					</tr>
				</table>

				

				<input class="span7" type="text" placeholder="Narration" ng-model="narration">

				<div class="form-horizontal">
					<form>
						<input type="text" placeholder="Person" class="span3" ng-model="person" id="person_name"> 
						<input type="text" placeholder="Amount" class="span2" ng-model="amount" ng-pattern="/^[0-9]+$/"> 
						<button type="submit" class="btn btn-success" ng-click="add_iou_to_cur_iou()" ng-disabled="person == '' || ng-amount == ''"><i class="icon-white icon-plus"></i></button>
					</form>

					<div style="height: 150px; overflow-y: scroll;">
						<div class="row" ng-repeat="(key, each_iou) in ious" style="margin-top: 10px;">
							<div class="span3" ng-class="{struck: true == each_iou.paid}">{{each_iou.person}}</div>
							<div class="span2" ng-class="{struck: true == each_iou.paid}">{{each_iou.amount}}</div>
							<button class="btn" ng-show="each_iou.paid == false" ng-click="each_iou.paid = true">Paid</button>
							<button class="btn" ng-show="each_iou.paid == true" ng-click="each_iou.paid = false"><i class="icon-repeat"></i></button>
						</div>
					</div>

				</div>

			</div>

			<div class="tab-pane" id="tab_tags">
				<h4>Tags help in searching better</h4>
			  	<span class="label_help">Ex: 'pizza' - to see all IOUs with your friends for pizza</span>

			  	<div>
			  		
		  			<div class="input-append">
			  			<input type="text" ng-model="new_tag" name="new_tag" id="new_tag" placeholder="Enter tag"> 
			  			<button type="submit" class="btn btn-success" ng-click="add_new_tag()" ng-disabled="new_tag == undefined"><i class="icon-plus icon-white"></i></button>
			  		</div>
		  		

			  		<ul>
			  			<li ng-repeat="(key, tag) in iou_tags">{{tag}} <i class="icon-remove" ng-click="remove_narration_tag(key)"></i></li>
			  		</ul>
			  	</div>
			</div>

		</div>

	</form>

	<div class="btn_toolbar">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" ng-click="save_iou()" ng-disabled="validate_add_iou()">Add</button>
	</div>


</div>

<div id="add_friend" class="modal hide">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

	<h3>Add Friend</h3>

	<input type="text" placeholder="Friend's Name" ng-model="friend_name"> <span class="label_help">Helps add IOUs faster</span> <br>
	<input type="email" placeholder="Email Address" ng-model="friend_email"> <span class="label_help">Send reminders and summaries</span> <br>
	<input type="text" placeholder="Phone Number" ng-model="friend_phone">

	<div class="btn_toolbar">
		<button class="btn" data-dismiss="modal">Cancel</button>
		<button class="btn btn-primary" ng-click="add_friend()"><i class="icon-user icon-white"></i> Add</button>
	</div>
</div>

<style type="text/css">
	#add_iou, #add_friend {
		border: solid 1px #EEE;
		padding: 20px;
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

	.struck {
		text-decoration: line-through;
	}
</style>

<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/ious.js"></script>

<script type="text/javascript">
	$(function () {
	    $('#tabs_add_narration a').click(function (e) {
	    	e.preventDefault();
				$(this).tab('show');
	  });
	});
</script>

</body>
</html>
