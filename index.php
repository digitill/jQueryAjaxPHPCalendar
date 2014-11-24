<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<title>jQuery Ajax PHP Calendar</title>
<style type="text/css">
#cal-show {
	width: 800px;
	height: 475px;
	margin: 0px auto;
	overflow: hidden;
	border: 1px solid #000;
}
#cal-hold {
	width: 303%;
	max-width: 2420px;
	margin: 0px auto 10px;
	overflow: hidden;
	position: relative;
	right: 100%;
}
.month {
	float: left;
	width: 800px;
}
#cal-top {
	width: 100%;
	height: 60px;
	line-height: 60px;
	background-color: #999;
	text-align: center;
	color: #FFF;
}
.cal-prev {
	width: 25%;
	float: left;
}
#month-title {
	width: 50%;
	float: left;
}
.tmonth #month-title {
	color: #FFC;
}
.cal-next {
	width: 25%;
	float: right;
}
.cal-nav a {
	color: #FFF;
	text-decoration: none;
}
.cal-nav a:hover {
	color: #FFC;
}
#daynames {
	width: 100%;
	height: 30px;
	line-height: 30px;
	text-align: center;
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
}
.fdayname {
	float: left;
	width: 14.17%;
}
.dayname {
	float: left;
	width: 14.17%;
	border-left: 1px solid #000;
}
.week {
	width: 100%;
	position: relative;
	clear: both;
}
.days {
	float: left;
	width: 90px;
	margin-top: 10px;
	margin-left: 12px;
	height: 50px;
	border: 1px solid #000;
}
.day1 {
	margin-right: 9px;
}
.day2 {
	margin-right: 11px;
}
.day3 {
	margin-right: 11px;
}
.day4 {
	margin-right: 10px;
}
.day5 {
	margin-right: 10px;
}
.day6 {
	margin-right: 10px;
}
.day7 {
	margin-right: 10px;
}
.lastm, .nextm {
	background-color: #999;
}
.tday {
	background-color: #FFC;
}
.day-num {
	margin-top: 2px;
	margin-left: 2px;
}
.day-info {
	margin: 2px;
}
</style>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
// Set jQuery variable
var j = {
	Q: jQuery,
} // End j

// Document ready functions
j.Q(document).ready(function() {
	cA.moveEnd('c');
});

// Calendar functions
var cA = {
	moveC: function(d) {
		var calh = j.Q('#cal-hold');
		var calw = parseInt(j.Q('#cal-show').css('width'), 10);
		var calhr = parseInt(j.Q('#cal-hold').css('right'), 10);
		var pos = 0;
		if (d == 'n') {
			pos = calhr + calw;
		} else {
			pos = calhr - calw;
		}
		calh.animate({
			right: pos,
		}, 1000, function() {
				cA.moveEnd(d);
		});
	},
	setActions: function() {
		j.Q('.cal-prev a').click(function() {
			cA.moveC('p');
			return false;
		});
		j.Q('.cal-next a').click(function() {
			cA.moveC('n');
			return false;
		});
	},
	moveEnd: function(d) {
		var year = '';
		var month = '';
		if (d == 'p') {
			year = j.Q('#prevyr').val();
			month = j.Q('#prevmn').val();
		} else if (d == 'n') {
			year = j.Q('#nextyr').val();
			month = j.Q('#nextmn').val();
		} else {
			var date = new Date();
			year = date.getFullYear();
			month = date.getMonth() + 1;
		}
		j.Q.post('cal.php', {yr: year, mn: month}).done(function(data) {
			j.Q('#cal-show').html(data);
			cA.setActions();
		});
	},
} // End cA
</script>
</head>
<body>
<div id="cal-show">
<noscript>
<?php
include('cal.php');
?>
</noscript>
</div><!-- #cal-show -->
</body>
</html>