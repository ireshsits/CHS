<?php

namespace App\Classes\DashboardStats;

use App\Classes\DashboardStats\BuildStatsDataSet;
use App\Classes\DashboardStats\FetchDB;
use Cache;
use Log;
use UserHelper;

class Stats {
	protected $buildDataSet;
	protected $fetchDB;
	public function __construct() {
		$this->buildDataSet = new BuildStatsDataSet();
		$this->fetchDB = new FetchDB ();
	}
	public function getStats($filters) {
		return $this->fetch ( $filters );
	}
	
	/**
	 * Cache Process
	 * 
	 * @param unknown $key        	
	 * @param unknown $content        	
	 * @param unknown $tag 
	 * Clear-Cache to identify changes in DB.     	
	 */
	private function fetch($filters) {
		$key = $this->generateCacheKey ( $filters );
		if (Cache::has ( $key ) && !Cache::has('Clear-Cache-Stats-'.$filters['mode'].'-'.UserHelper::getLoggedUserId())) {
			return $this->getFromCache ( $key );
		} else {
			Cache::forget('Clear-Cache-Stats-'.$filters['mode'].'-'.UserHelper::getLoggedUserId());
			return $this->fetchFromDB ( $filters, $key );
		}
	}
	private function fetchFromDB($filters, $key) {
		$queryData = $this->fetchDB->generateQuery ( $filters )->get ();
		$dataSet = $this->buildStats( $filters, $queryData );
		/**
		 * Put in Cache
		 */
		$this->putInCache ( $key, $dataSet, 'STATS' );
		return collect($dataSet);
	}
	private function buildStats($filters, $queryData){
		$arrCodes = ['NUM', 'STATUS', 'AWISE', 'RESOL', 'BOX'];
		foreach ($arrCodes as $code){
			$filters['code'] = ($filters['mode'] == 'month'?'M':'A').$code;
			$dataSet[$filters['code']] = $this->buildDataSet->build ( $filters, $queryData );
		}
		return $dataSet;
	}
	private function putInCache($key, $content, $tag) {
		Log::info('Put in Cache >> '.$key);
		Cache::put ( $key, json_encode ( $content ), 600); //10 minutes
	}
	private function getFromCache($key) {
	    Log::info('Fetch from Cache >> '.$key);
		return (array) json_decode ( Cache::get ( $key ) );
	}
	private function generateCacheKey($filters) {
	    return 'Stats-'.UserHelper::getLoggedUserId().'-'.($filters['type']??'').'-'.($filters['raise_by']??'').'-'.($filters['mode']??'').'-'.($filters['range']['year']??'').($filters['month']?'-'.$filters['month']:'').($filters['range']['start']?'-'.$filters['range']['start']:'').($filters['range']['end']?'-'.$filters['range']['end']:'');
	}
}