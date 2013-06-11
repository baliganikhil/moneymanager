MoneyController = function($scope, $http) {
	$scope.all_months = [
						{'index': 1, 'month': 'January'},
						{'index': 2, 'month': 'February'},
						{'index': 3, 'month': 'March'},
						{'index': 4, 'month': 'April'},
						{'index': 5, 'month': 'May'},
						{'index': 6, 'month': 'June'},
						{'index': 7, 'month': 'July'},
						{'index': 8, 'month': 'August'},
						{'index': 9, 'month': 'September'},
						{'index': 10, 'month': 'October'},
						{'index': 11, 'month': 'November'},
	 					{'index': 12, 'month': 'December'}
	 					];


	$scope.all_years = [{index: 2013, year: '2013'}, {index: 2014, year: '2014'}];
	$scope.username = username;

	var d = new Date();
	var cur_month = d.getMonth() + 1;

	$scope.mt_month = cur_month;
	$scope.mt_year = 2013;

	$scope.budget_month = $scope.mt_month;
	$scope.budget_year = $scope.mt_year;

	$scope.monthly_budget = undefined;
	$scope.narration_id = '';

	$scope.all_tags = [];
	$scope.narration_tags = [];
	$scope.new_tag = undefined;

	$scope.mt_sort_param = 'date';
	$scope.mt_sort_reverse = false;

	// Populate date dropdown
	$scope.populate_days_dd = function() {
		var no_of_days;
		var months_31 = [1, 3, 5, 7, 8, 10, 12];
		if (months_31.indexOf($scope.mt_month) != -1) {
			no_of_days = 31;
		} else if (cur_month == 2) {
			no_of_days = 28;
		} else {
			no_of_days = 30;
		}

		$scope.all_days = [];
		for (var i = 1; i <= no_of_days; i++) {
			var date = {};
			date['index'] = i;
			date['date'] = i;
			$scope.all_days.push(date);
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
		data['month'] = parseInt($scope.mt_month);
		data['year'] = parseInt($scope.mt_year);

		var params = {};
		params['mode'] = 'mode_get_narrations';
		params['params'] = data;

		$http({
			method: 'GET',
			url: 'services/moneymanager_services.php',
			params: params
		}).success(function(data) {

			if (data['err'] == null) {
				$scope.monthly_data = data['narrations'];

				var budget = data['budget'];
				if (budget == null) {
					$scope.monthly_budget = '';
					$scope.budget_amount = undefined;
					$scope.budget_warning_amount = undefined;
					$scope.savings_lower_limit = undefined;
				} else {
					$scope.monthly_budget = budget['budget_amount'];
					$scope.budget_amount = budget['budget_amount'];
					$scope.budget_warning_amount = budget['budget_warning_amount'];
					$scope.savings_lower_limit = budget['savings_lower_limit'];
				}

				$scope.budget_month = $scope.mt_month;
				$scope.budget_year = $scope.mt_year;

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
		var date = parseInt($scope.narration_date);
		var month = parseInt($scope.mt_month);
		var year = parseInt($scope.mt_year);

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
							is_avoidable: $scope.is_avoidable,
							notes: $scope.notes
						};

		if ($scope.narration_id != '') {
			narration['_id'] = $scope.narration_id['$id'];
		}

		// console.log(narration);return;

		var data = {
						mode: 'mode_add_narration',
						narration: narration,
						tags: $scope.all_tags
					};

		$http({
			method: "POST",
			url: 'services/moneymanager_services.php',
			data: data
		}).success(function(data) {

			if (data['err'] == null) {
				// $scope.monthly_data.push(data['data']);
				$scope.get_narrations();
				$('.modal').modal('hide');
			}
		});
	}

	$scope.logout = function() {
		var data = {
						mode: 'mode_logout'
					};

		$http({
			method: "POST",
			url: 'services/login_services.php',
			data: data
		}).success(function(data) {

			if (data['err'] == null) {
				window.location = '../login.php';
			}
		});
	}

	$scope.edit_narration = function(index) {
		var cur_narration = $scope.monthly_data[index];

		console.log(cur_narration);

		$scope.narration_id = cur_narration['_id'];
		$scope.narration = cur_narration['narration'];
		$scope.category = cur_narration['category'];

		$scope.mt_year = cur_narration['year'];
		$scope.mt_month = cur_narration['month'];
		$scope.narration_date = cur_narration['date'];

		$scope.amount = cur_narration['amount'];
		$scope.notes = cur_narration['notes'];
		$scope.inc_exp = cur_narration['inc_exp'];
		$scope.is_avoidable = cur_narration['is_avoidable'];

		show_add_narration();
	}

	$scope.delete_narration = function(index) {
		if (confirm("Are you sure?")) {
			var _id = $scope.monthly_data[index]['_id']['$id'];

			var data = {};
			data['mode'] = 'mode_delete_narration';
			data['_id'] = _id;

			// console.log($scope.monthly_data);return;

			$http({
				method: "POST",
				url: 'services/moneymanager_services.php',
				data: data
			}).success(function(data) {

				if (data['err'] == '') {
					console.log($scope.monthly_data);
					console.log(index);
					$scope.monthly_data.splice(index, 1);
				}
			});
			
		}
	}

	$scope.btn_add_narration = function() {
		$scope.narration_id = '';

		$scope.narration = '';
		$scope.category = '';

		var d = new Date();
		$scope.narration_date = d.getDate();

		$scope.inc_exp = '';
		$scope.amount = '';
		$scope.is_avoidable = false;
		$scope.notes = '';
		$scope.inc_exp = '';

		show_add_narration();
	}


	function get_tags() {
		var params = {mode: 'mode_get_tags'}
		$http({
			method: 'GET',
			url: 'services/moneymanager_services.php',
			params: params
		}).success(function(data) {

			if (data['err'] == '') {
				$scope.all_tags = data['tags'];
				console.log(data['tags'])
				$('#new_tag').typeahead({source: $scope.all_tags});
			}

		});
	}

	get_tags();

	$scope.add_new_tag = function () {
		var tag = $('#new_tag').val();
		$scope.narration_tags.push(tag);

		if ($scope.all_tags.indexOf(tag) == -1) {
			$scope.all_tags.push(tag);
		}

		$scope.new_tag = undefined;
	}

	$('#narration_category').on('blur', function() {
		$scope.category = $(this).val();
	});

	$scope.remove_narration_tag = function (index) {
		$scope.narration_tags.splice(index, 1);
	}


	$scope.set_monthly_budget = function() {
		var budget = {};
		budget['month'] = $scope.budget_month;
		budget['year'] = $scope.budget_year;
		budget['budget_amount'] = $scope.budget_amount;
		budget['budget_warning_amount'] = $scope.budget_warning_amount;
		budget['savings_lower_limit'] = $scope.savings_lower_limit;

		var params = {};
		params['mode'] = 'mode_set_monthly_budget';
		params['data'] = budget;

		$http({
			method: 'POST',
			url: 'services/moneymanager_services.php',
			data: params
		}).success(function(data) {

			if (data['err'] == '') {
				$('#monthly_budget').modal('hide');

				data = data['data'];

				if ($scope.budget_month == $scope.mt_month && $scope.budget_year == $scope.mt_year) {
					$scope.monthly_budget = data['budget_amount'];
					evaluate_totals_budget();
				}
			} else {
				alert(data['err']);
			}
			

		});
	}

	$scope.get_monthly_budget = function() {
		var data = {};
		data['month'] = parseInt($scope.budget_month);
		data['year'] = parseInt($scope.budget_year);

		var params = {};
		params['mode'] = 'mode_get_monthly_budget';
		params['data'] = data;

		$http({
			method: 'GET',
			url: 'services/moneymanager_services.php',
			params: params
		}).success(function(data) {

			if (data['err'] == '') {
				var budget = data['data'];
				$scope.budget_amount = budget['budget_amount'];
				$scope.budget_warning_amount = budget['budget_warning_amount'];
				$scope.savings_lower_limit = budget['savings_lower_limit'];
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

// $('#btn_add_narration').on('click', function() {
// 	$scope.narration_id = '';
// 	show_add_narration();
// });

$('.show_monthly_budget').on('click', function() {
	show_monthly_budget();
});

var all_categories = ["bike loan", "books", "car loan", "clothes", "college", "electricity", "electronics", "emi", "fitness and gym", "fuel", "games", "gas", "gift", "house loan", "house rent", "internet bill", "medical", "mobile", "mobile recharge", "movies", "music", "other", "party", "personal loan", "repair and maintenance", "restaurant", "school", "shoes", "shopping", "software", "tax", "telephone bill", "vehicle", "watch", "water"];
$('#narration_category').typeahead({source: all_categories});