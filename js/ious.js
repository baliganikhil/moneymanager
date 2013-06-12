function ADDIOUController($scope, $http) {
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

	$scope.get_owe = '';


	$scope.ious = [];

	$scope.add_iou_to_cur_iou = function() {
		var iou = {};
		iou['amount'] = $scope.amount;
		iou['person'] = $scope.person;
		iou['paid'] = false;

		$scope.ious.push(iou);

		$scope.amount = undefined;
		$scope.person = undefined;
	}

	$scope.btn_add_iou = function() {
		show_add_narration();
	};

	$scope.btn_add_friend = function() {
		show_add_friend();
	}

	$scope.add_friend = function() {

		var data = {};
		data['name'] = $scope.friend_name;
		data['email'] = $scope.friend_email;
		data['phone'] = $scope.friend_phone;

		var params = {
						mode: 'mode_add_friend',
						data: data
					};

		$http({
			method: "POST",
			url: 'services/moneymanager_services.php',
			data: params
		}).success(function(data) {

			if (data['err'] == null) {
				// $scope.monthly_data.push(data['data']);
				// $scope.get_narrations();
				$('.modal').modal('hide');
				get_friends();
			}
		});

	}

	function get_friends() {
		var params = {};
		params['mode'] = 'mode_get_friends';

		$http({
			method: "GET",
			url: 'services/moneymanager_services.php',
			params: params
		}).success(function(data) {

			if (data['err'] == '') {
				var all_friends = data['friends'];
				var friends_names = [];

				for (var i = 0; i < all_friends.length; i++) {
					friends_names.push(all_friends[i]['name']);
				}

				console.log(friends_names);
				$('#person_name').typeahead({source: friends_names});
			}
		});
	}

	get_friends();

	$('#person_name').on('blur', function() {
		$scope.person = $(this).val();
	});

	$scope.save_iou = function() {
		var full_date = $scope.date + '/' + $scope.mt_month + '/' + $scope.mt_year;

		var data = {};

		data['narration'] = $scope.narration;
		data['date'] = parseInt($scope.date);
		data['month'] = parseInt($scope.mt_month);
		data['year'] = parseInt($scope.mt_year);
		data['full_date'] = full_date;

		data['get_owe'] = $scope.get_owe;

		data['ious'] = $scope.ious;

		var total_amount = 0;
		for (var i = 0; i < $scope.ious.length; i++) {
			if ($scope.get_owe == 'get') {
				total_amount += $scope.ious[i]['amount'];
			} else {
				total_amount -= $scope.ious[i]['amount'];
			}
		}

		data['total_amount'] = parseFloat(total_amount);

		var params = {
						mode: 'mode_add_iou',
						data: data
					};

		$http({
			method: "POST",
			url: 'services/moneymanager_services.php',
			data: params
		}).success(function(data) {

			if (data['err'] == null) {
				$('.modal').modal('hide');
			}
		});

	}

	$scope.get_ious = function() {
		var data = {};
		data['month'] = parseInt($scope.mt_month);
		data['year'] = parseInt($scope.mt_year);
		
		var params = {};
		params['mode'] = 'mode_get_ious';
		params['data'] = data;

		$http({
			method: "GET",
			url: 'services/moneymanager_services.php',
			params: params
		}).success(function(data) {

			if (data['err'] == '') {
				$scope.monthly_data = data['ious'];
			}
		});
	}

	$scope.get_ious();

	$scope.validate_add_iou = function() {
		if (nullOrEmpty($scope.narration) || nullOrEmpty($scope.date) || $scope.ious.length == 0 || nullOrEmpty($scope.get_owe)) {
			return true;
		}
	}
}

function nullOrEmpty(string) {
	if (string == undefined || string == null || string == '') {
		return true;
	} else {
		return false;
	}
}

function show_add_narration() {
	$('#add_iou').modal({background: 'static'});
	$('#add_iou').modal('show');
}

function show_add_friend() {
	$('#add_friend').modal({background: 'static'});
	$('#add_friend').modal('show');
}