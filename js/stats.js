function StatsController($scope, $http) {
		$scope.all_months = [
						{'index': '1', 'month': 'January'},
						{'index': '2', 'month': 'February'},
						{'index': '3', 'month': 'March'},
						{'index': '4', 'month': 'April'},
						{'index': '5', 'month': 'May'},
						{'index': '6', 'month': 'June'},
						{'index': '7', 'month': 'July'},
						{'index': '8', 'month': 'August'},
						{'index': '9', 'month': 'September'},
						{'index': '10', 'month': 'October'},
						{'index': '11', 'month': 'November'},
	 					{'index': '12', 'month': 'December'}
	 					];


	$scope.all_years = [{index: '2013', year: '2013'}, {index: '2014', year: '2014'}];
	$scope.username = username;

	var d = new Date();
	var cur_month = d.getMonth() + 1;

	$scope.mt_month = String(cur_month);
	$scope.mt_year = "2013";

	

	$scope.get_monthly_report = function() {
		var params = {};
		params['mode'] = 'mode_get_monthly_report';
		params['month'] = '6';
		params['year'] = '2013';

		$http({
			method: 'GET',
			url: 'services/moneymanager_services.php',
			params: params
		}).success(function(data) {
			var flot_data = convert_monthly_report_for_flot(data);
			draw_monthly_report(flot_data);

			console.log(flot_data);
		});
	}

	function convert_monthly_report_for_flot(data) {
		var total_income = [];
		var total_expenses = [];

		data = sort_by_date(data);

		$(data).each(function(key, cur_record) {
			var cur_income = [];
			var cur_expense = [];

			var date = cur_record['date'];
			var inc = cur_record['inc'];
			var exp = cur_record['exp'];

			cur_income.push(date);
			cur_income.push(inc);

			cur_expense.push(date);
			cur_expense.push(exp);

			total_income.push(cur_income);
			total_expenses.push(cur_expense);
		});

		var flot_data = [
			{label: "Income", data: total_income},
			{label: "Expenses", data: total_expenses},
		];

		return flot_data;
	}

	function sort_by_date(data) {
		function compare(a, b) {
			if (a.date < b.date)
		    	return -1;
			
			if (a.date > b.date)
		    	return 1;
			
			return 0;
		}

		data.sort(compare);

		return data;
	}

	function draw_monthly_report(flot_data) {
		$('#placeholder').plot(flot_data);
	}
}