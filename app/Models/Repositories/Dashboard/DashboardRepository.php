<?php

namespace App\Models\Repositories\Dashboard;

use App\Classes\DashboardStats\Stats;
use DateHelper;

class DashboardRepository implements DashboardInterface {
	
	protected $stats;
	public function __construct(){
		$this->stats = new Stats ();
	}
	
	public function getMonthlyComplaintStats($params){
		$params['mode'] = 'month';
		/**
		 * Complaints only for compliments CMPLI
		 */
		$params['raise_by'] = $params['raise_by']??null;
// 		$params['type'] = 'CMPLA';
		$params['range'] = DateHelper::getRangeStartEnd($params);
		return $this->stats->getStats($params);
	}
	
	public function getAnnualComplaintStats($params){
		$params['mode'] = 'annual';		
		/**
		* Complaints only for compliments CMPLI
		*/
		$params['raise_by'] = $params['raise_by']??null;
// 		$params['type'] = 'CMPLA';
		$params['range'] = DateHelper::getRangeStartEnd($params);
		return $this->stats->getStats($params);
	}
	
}