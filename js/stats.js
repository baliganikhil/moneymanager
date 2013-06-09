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
}