<?php
$yr = '';
$mn = '';
if (isset($_REQUEST['yr']) && preg_match('/^[0-9]{4}$/', $_REQUEST['yr'])) {
	$yr = $_REQUEST['yr'];
}
if (isset($_REQUEST['mn']) && preg_match('/^[0-9]{1,2}$/', $_REQUEST['mn'])) {
	$mn = $_REQUEST['mn'];
}
$calendar = new ajaxCalendar($yr, $mn);
class ajaxCalendar {
	public function __construct($yr, $mn) {
		$current_time = time();
		if ($yr == '') {
			$this->pyear = $this->nyear = $this->year = date('Y', $current_time);
			$this->month = date('m', $current_time);
		} else {
			$this->pyear = $this->nyear = $this->year = $yr;
			$this->month = $mn;
		}
		$this->pmonth = $this->month - 1;
		$this->nmonth = $this->month + 1;
		if ($this->pmonth == 0) {
			$this->pmonth = 12;
			$this->pyear = $this->pyear - 1;
		}
		if ($this->nmonth == 13) {
			$this->nmonth = 1;
			$this->nyear = $this->nyear + 1;
		}
		$this->tyear = date('Y', $current_time);
		$this->tday = date('d', $current_time);
		$this->tmonth = date('m', $current_time);
		$this->putCal();
	} // End __construct
	public function putCal() {
		echo '	<div id="cal-hold">
		<div id="pmonth" class="month';
		if ($this->pyear == $this->tyear && $this->pmonth == $this->tmonth) {
			echo ' tmonth';
		}
		echo '">
' . $this->getCal($this->pyear, $this->pmonth, 'p') . '
		</div><!-- #pmonth -->
		<input type="hidden" id="prevyr" value="' . $this->pyear . '">
		<input type="hidden" id="prevmn" value="' . $this->pmonth . '">
		<div id="cmonth" class="month';
		if ($this->year == $this->tyear && $this->month == $this->tmonth) {
			echo ' tmonth';
		}
		echo '">
' . $this->getCal($this->year, $this->month, 'c') . '
		</div><!-- #cmonth -->
		<div id="nmonth" class="month';
		if ($this->nyear == $this->tyear && $this->nmonth == $this->tmonth) {
			echo ' tmonth';
		}
		echo '">
' . $this->getCal($this->nyear, $this->nmonth, 'n') . '
		</div><!-- #nmonth -->
		<input type="hidden" id="nextyr" value="' . $this->nyear . '">
		<input type="hidden" id="nextmn" value="' . $this->nmonth . '">
	</div><!-- #cal-hold -->
';
	} // End putCal
	public function getCal($year, $month, $type) {
		$month_start = mktime(0,0,0, $month, 1, $year);
		$month_name = date('F', $month_start); 
		$first_day = date('D', $month_start);

		switch($first_day) {
			case "Sun":
				$offset = 0;
				break;
			case "Mon":
				$offset = 1;
				break;
			case "Tue":
				$offset = 2;
				break;
			case "Wed":
				$offset = 3;
				break;
			case "Thu":
				$offset = 4;
				break;
			case "Fri":
				$offset = 5;
				break;
			case "Sat":
				$offset = 6;
				break;
		}

		if ($month == 1) {
			$num_days_last = cal_days_in_month(CAL_GREGORIAN, 12, ($year -1));
		} else {
			$num_days_last = cal_days_in_month(CAL_GREGORIAN, ($month - 1), $year);
		}
		$num_days_current = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		for ($i = 0; $i < $num_days_current; $i++) {
			$num_days_array[] = $i + 1;
		}
		$current_num = $num_days_current + $offset;

		if ($current_num > 35) {
			$num_weeks = 6;
			$tot = 42;
			$outset = (42 - $current_num);
		} else {
			$num_weeks = 5;
			$tot = 35;
			if ($current_num < 35) {
				$outset = (35 - $current_num);
			} else if ($current_num == 35) {
				$outset = 0;
			}
		}

		$outd = 1;
		$cday = 1;
		for ($a = 1; $a <= $tot; $a++) {
			if ($a <= $offset) {
				$day['l' . $a] = $num_days_last - ($offset - $a);
			} else if ($a > $current_num) {
				$day['n' . $a] = $outd;
				$outd++;
			} else {
				if ($this->tday == $cday && $this->tmonth == $month) {
					$day['t' . $a] = $cday;
				} else {
					$day['c' . $a] = $cday;
				}
				$cday++;
			}
		}
		$weeks = array_chunk($day, 7, true);

		$pyear = $nyear = $year;
		$pmonth = $month - 1;
		$nmonth = $month + 1;
		if ($pmonth == 0) {
			$pmonth = 12;
			$pyear = $pyear - 1;
		}
		if ($nmonth == 13) {
			$nmonth = 1;
			$nyear = $nyear + 1;
		}

		$cal = '			<div id="cal-top">
				<div id="cal-' . $type . '-prev" class="cal-prev cal-nav">
					<a href="index.php?yr=' . $pyear . '&mn=' . $pmonth . '">&laquo; Prev</a>
				</div><!-- #cal-top-prev -->
				<div id="month-title">
					<b>' . $month_name . ' ' . $year . '</b>
				</div><!-- .month-title -->
				<div id="cal-' . $type . '-next" class="cal-next cal-nav">
					<a href="index.php?yr=' . $nyear . '&mn=' . $nmonth . '">Next &raquo;</a>
				</div><!-- #cal-top-next -->
			</div><!-- #cal-top -->
			<div id="daynames"> 
				<div class="fdayname">
					S
				</div><!-- .fdayname -->
				<div class="dayname">
					M
				</div><!-- .dayname -->
				<div class="dayname">
					T
				</div><!-- .dayname -->
				<div class="dayname">
					W
				</div><!-- .dayname -->
				<div class="dayname">
					T
				</div><!-- .dayname -->
				<div class="dayname">
					F
				</div><!-- .dayname -->
				<div class="dayname">
					S
				</div><!-- .dayname -->
			</div><!-- #daynames -->
';
		$dayt = '';
		foreach ($weeks as $week) {
			$cal .= '			<div class="week">
';
			$cnt = 1;
			foreach ($week as $k => $v) {
				$dayt = substr($k, 0, 1);
				if ($dayt == 'l') {
					$cal .= '				<div class="days day' . $cnt . ' lastm">
					<div class="day-num">
						' . $v . '
					</div><!-- .day-num -->
					<div class="day-info">
					</div><!-- .day-info -->
				</div><!-- .days -->
';
					$cnt++;
				} else if ($dayt == 'n') {
					$cal .= '				<div class="days day' . $cnt . ' nextm">
					<div class="day-num">
						' . $v . '
					</div><!-- .day-num -->
					<div class="day-info">
					</div><!-- .day-info -->
				</div><!-- .days -->
';
					$cnt++;
				} else if ($dayt == 'c') {
					$cal .= '				<div class="days day' . $cnt . ' thism">
					<div class="day-num">
						' . $v . '
					</div><!-- .day-num -->
					<div class="day-info">
					</div><!-- .day-info -->
				</div><!-- .days -->
';
					$cnt++;
				} else if ($dayt == 't') {
					$cal .= '				<div class="days day' . $cnt . ' thism tday">
					<div class="day-num">
						' . $v . '
					</div><!-- .day-num -->
					<div class="day-info">
					</div><!-- .day-info -->
				</div><!-- .days -->
';
					$cnt++;
				}
			}
			$cal .= '		</div><!-- .week -->
';
		}
		return $cal;
	} // End getCal
} // End ajaxCalendar
?>