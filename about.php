<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html ng-app>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-responsive.min.css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css" rel="stylesheet">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>

	<div class="navbar" ng-cloak>
	  <div class="navbar-inner">
	    <a class="brand" href="#">Black Light</a>
	    <ul class="nav">
	      <li><a href="moneymanager.php">Home</a></li>
	      <li><a href="#">IOUs</a></li>
	      <li><a href="stats.php">Stats</a></li>
	      <li class="active"><a href="#">About</a></li>
	    </ul>

	  </div>
	</div>

	<div class="container">
		<h3>Black Light</h3>

		<p>The project was started mainly because I keep losing track of where my money keeps disappearing and 
		I thought it would be great if there was a nice tool for that.</p>

		<p>Now I am very well aware of the many many tools that are out there, including GNUCash and other online 
		ones, but I thought of writing one myself</p>

		<div class="well" style="text-align: center;">
			<a class="btn btn-primary btn-large" href="signup.php">Give it a shot</a>
		</div>

		<p>The current objectives of Black Light is to keep track of money and IOUs, draw graphs and identify how much 
			money is spent where, how much of it is wasteful, how much is avoidable, how many are recurring, keep track of 
			expenses that could come in the future, send out reminders, keep track of investments etc.</p>

		<p>The project can later be improved by using learning and prediction stuff? I don't know, just a wild thought</p>

		<p>The project is still in development, so some things may not work, some things may look redundant or duplicate, 
			but they will go as the project matures</p>

		<hr>
		<h4>Contribute Code</h4>
		<p>
			Writing the whole thing takes a lot of time, and I would definitely appreciate it if some of you would contribute
			some lines of code :) As less as 20 lines of code is appreciated. If you are interested, let me know at 
			<span class="label">baliganikhil@gmail.com</span> and I shall get in touch with you. <br>
			Platform: HTML, CSS (Bootstrap + custom), Javascript (AngularJS, jQuery - because bootstrap needs it?), PHP, 
			MongoDB, nginx
		</p>

		<hr>
		Black Light is basically UV rays used to trace money - It's used to detect counterfeit notes (not very relevant, 
		yeah I know)
	</div>

</body>
</html>