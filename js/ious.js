function ADDIOUController($scope, $http) {
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


	$scope.all_years = ['2013', '2014'];
	$scope.username = username;

	var d = new Date();
	var cur_month = d.getMonth() + 1;

	$scope.mt_month = String(cur_month);
	$scope.mt_year = "2013";




	$scope.cur_iou = [
		{
			amount: 1000,
			person: 'Nikhil Baliga',
			paid: false
		},
		{
			amount: 500,
			person: 'Amod Pandey',
			paid: true
		},
		{
			amount: 1500,
			person: 'Niyaz',
			paid: false
		}
	];

	$scope.add_iou_to_cur_iou = function() {
		var iou = {};
		iou['amount'] = $scope.amount;
		iou['person'] = $scope.person;
		iou['paid'] = false;

		$scope.cur_iou.push(iou);

		$scope.amount = undefined;
		$scope.person = undefined;
	}

	$scope.btn_add_iou = function() {
		show_add_narration();
	};

	$scope.btn_add_friend = function() {
		show_add_friend();
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