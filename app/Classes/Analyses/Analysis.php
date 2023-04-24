<?php

namespace App\Classes\Analyses;

use App\Classes\Analyses\BuildAnalysisDataSet;
use App\Classes\Analyses\FetchDB;
use Cache;
use Log;
use UserHelper;

class Analysis {
	protected $buildDataSet;
	protected $fetchDB;
	public function __construct() {
		$this->buildDataSet = new BuildAnalysisDataSet ();
		$this->fetchDB = new FetchDB ();
	}
	public function getAnalysis($filters) {
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
		if (Cache::has ( $key ) && !Cache::has('Clear-Cache-Analyse-'.UserHelper::getLoggedUserId())) {
			return $this->getFromCache ( $key );
		} else {
			Cache::forget('Clear-Cache-Analyse-'.UserHelper::getLoggedUserId());
			return $this->fetchFromDB ( $filters, $key );
		}
	}
	private function fetchFromDB($filters, $key) {
		$queryData = $this->fetchDB->generateQuery ( $filters )->get ();
		$dataSet = $this->buildDataSet->build ( $filters, $queryData );
		/**
		 * Put in Cache
		 */
		$this->putInCache ( $key, $dataSet, 'ANALYSES' );
		return collect($dataSet);
	}
	private function putInCache($key, $content, $tag) {
		Log::info('Put in Cache >> '.$key);
		Cache::put ( $key, json_encode ( $content ), 600 ); //10 minutes
	}
	private function getFromCache($key) {
	    Log::info('Fetch from Cache >> '.$key);
		return (array) json_decode ( Cache::get ( $key ) );
	}
	private function generateCacheKey($filters) {
	    return 'Analyse-'.($filters['code']??'').'-'.($filters['type']??'').'-'.($filters['year']??'').'-'.($filters['status']??'').($filters['area_id_fk']?'-'.$filters['area_id_fk']:'').($filters['category_id_fk']?'-'.$filters['category_id_fk']:'');
	}
}