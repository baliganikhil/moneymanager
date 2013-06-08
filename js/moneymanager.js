MoneyController = function($scope, $http) {
	$scope.all_months = {
						'1': 'January',
						'2': 'February',
						'3': 'March',
						'4': 'April',
						'5': 'May',
						'6': 'June',
						'7': 'July',
						'8': 'August',
						'9': 'September',
						'10': 'October',
						'11': 'November',
	 					'12': 'December'
	 					};


	$scope.all_years = ['2013', '2014'];
	$scope.username = username;

	var d = new Date();
	var cur_month = d.getMonth() + 1;

	$scope.mt_month = String(cur_month);
	$scope.mt_year = "2013";

	$scope.monthly_budget = 10000;
	$scope.narration_id = '';

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
							is_avoidable: $scope.is_avoidable,
							notes: $scope.notes
						};

		if ($scope.narration_id != '') {
			narration['_id'] = $scope.narration_id['$id'];
		}

		// console.log(narration);return;

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
			url: 'login_services.php',
			data: data
		}).success(function(data) {

			if (data['err'] == null) {
				window.location = 'login.php';
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
				url: 'moneymanager_services.php',
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
		$scope.narration_date = '';

		$scope.inc_exp = '';
		$scope.amount = '';
		$scope.is_avoidable = false;
		$scope.notes = '';
		$scope.inc_exp = '';

		show_add_narration();
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