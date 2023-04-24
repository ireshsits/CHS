<?php

namespace App\Helpers;

use \Carbon\Carbon;
use \Carbon\CarbonPeriod;

class DateHelper {
	public static function getNow() {
		return Carbon::now ();
	}
	public static function getDate($days = 0) {
		$now = self::getNow ();
		if ($days > 0)
			$now->addDays ( $days );
		else if ($days < 0)
			$now->subDays ( abs ( $days ) );
		return $now->toDateString ();
	}
	public static function getMonth($leadingZero = true) {
		if($leadingZero)
			return Carbon::now ()->format('m');
		else
			return Carbon::now ()->month;
	}
	public static function getMonthLongFormat($month = null) {
		if($month)
			return Carbon::createFromDate ( self::getYear (), $month, 1 )->format('F');
		return Carbon::now ()->format('F');
	}
	public static function getYear() {
	    return Carbon::now ()->year;
	}
	public static function formatToDateString($date) {
		if ($date)
			return Carbon::parse ( $date )->toFormattedDateString ();
		return null;
	}
	public static function getLongFormatDateString($time=false, $date=null) {
	    if($date)
	        $date = self::formatToDate($date);
	    else
		  $date = self::getNow ();
		if ($time)
		    return $date->format ( 'l jS \\of F Y h:i:s A' );
		else
		    return $date->format ( 'l jS \\of F Y' );
	}
	public static function formatToDate($date) {
		if ($date)
			return Carbon::parse ( $date );
		return null;
	}
	public static function getUnixTimeStamp() {
		return now ()->timestamp;
	}
	public static function getDMY() {
		return date ( 'dmy' );
	}
	public static function checkDate($dateString, $format = 'Y-m-d') {
		return self::getNow ()->toDateString () == self::getDateByString ( $dateString, $format )->toDateString ();
	}
	public static function getDateByString($dateString, $format = 'Y-m-d') {
		return Carbon::createFromFormat ( $format, $dateString );
	}
	public static function getDateDiff($openDate, $completedDate) {
		$openDate = Carbon::parse ( $openDate );
		$completedDate = Carbon::parse ( $completedDate);

		return $completedDate->diffInDays ( $openDate );
	}
	public static function getReferenceFormatDate() {
		$now = self::getNow ();
// 		return $now->year . '|' . $now->month . '|' . $now->day;
// 		return $now->format('y') . $now->format('m');
		return $now->format('m') . $now->format('y');
	}
	public static function getMonthList($year = null, $mode='list', $format='F') {
		$year = $year ?? self::getYear ();
		$startOfYear = Carbon::createFromDate ( $year, 1, 1 );
		if (( int ) $year !== self::getYear ())
			$endOfYear = $startOfYear->copy ()->endOfYear ();
		else
			$endOfYear = self::getNow ();
		
		foreach ( CarbonPeriod::create ( $startOfYear, '1 month', $endOfYear ) as $month ) {
			if($mode == 'list')
				$months [] = $month->format ($format);
			else
				$months [] = array(
					'value' => $month->format ( 'm' ),
					'text' => $month->format ($format),
				);
		}
		
		return $months ?? [ ];
	}
	public static function getRangeStartEnd($params=[], $sReturn='STR'){
		$year = $params['year']?? self::getYear ();
		if($params['mode'] == 'month'){
			$month = $params['month']?? self::getMonth(true);
			$date = Carbon::createFromDate ( $year, $month, 1 );
			$start = $date->copy()->startOfMonth();
			$end = $date->copy()->endOfMonth();
		}else{
			$date = Carbon::createFromDate ( $year, 1, 1 );
			$start = $date->copy()->startOfYear();
			$end = $date->copy()->endOfYear();
		}
		
		if($sReturn == 'STR')
		return array(
				'start' => $start->toDateString(),
				'end' => $end->toDateString(),
				'year' => $year
		);
		else
			return array(
					'start' => $start,
					'end' => $end,
					'year' => $year
			);
	}
	public static function getCurrentYear() {
	    return Carbon::now ()->year;
	}
}