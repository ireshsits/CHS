<?php

namespace App\Classes\DashboardStats;

use AnalysisHelper;
use DateHelper;
use App\Models\Entities\ComplaintMode;
use App\Models\Entities\Area;
// use App\Models\Entities\SubCategory;
use Log;

class BuildStatsDataSet {
	
	protected $monthList;
	public function __construct(){
	}
	
	public function build($filters, $queryData) {
		try{
			switch ($filters ['code']) {
				case 'MNUM' 	: return $this->getMNUMDataSet($filters, $queryData);break; //Monthly Number of CMPLA/CMPLI
				case 'MSTATUS' 	: return $this->getMSTATUSDataSet($filters, $queryData);break; //Monthly Status of CMPLA/CMPLI
				case 'MAWISE' 	: return $this->getMAWISEDataSet($filters, $queryData);break; //Monthly Area Wise of CMPLA/CMPLI
				case 'MRESOL' 	: return $this->getMRESOLDataSet($filters, $queryData);break; //Monthly Resolution of CMPLA/CMPLI
				case 'MBOX' 	: return $this->getMBOXDataSet($filters, $queryData);break; //Monthly Box Stats
				case 'ANUM' 	: return $this->getANUMDataSet($filters, $queryData);break; //Annual Number of CMPLA/CMPLI
				case 'ASTATUS' 	: return $this->getASTATUSDataSet($filters, $queryData);break; //Annual Status of CMPLA/CMPLI
				case 'AAWISE' 	: return $this->getAAWISEDataSet($filters, $queryData);break; //Annual Area Wise of CMPLA/CMPLI
				case 'ARESOL' 	: return $this->getARESOLDataSet($filters, $queryData);break; //Annual Resolution of CMPLA/CMPLI
				case 'ABOX' 	: return $this->getABOXDataSet($filters, $queryData);break; //Annual Box Stats
			}
		}catch (\Exception $e){
			Log::error ( '-----------Build Stats DataSet Failed---------' );
			Log::error ( 'stats---------error---------' . json_encode ( $e->getMessage () ) );
			Log::error ( 'stats---------filters---------' . json_encode ( $filters) );
			Log::error ( 'stats---------queryData---------' . json_encode ( $queryData) );
		}
	}
	
	private function getMNUMDataSet($filters, $queryData){
// 		$modeList = ComplaintMode::all ();
		$modeList = ComplaintMode::where('status','ACT')->get();
		$chartData['title'] = DateHelper::getMonthLongFormat($filters['month']). ' '.DateHelper::getYear();
		$array = array ();
		foreach ( $modeList as $mode ) {
			$array [$mode->name] = 0;
		}
		$modeGrouped = $queryData->groupBy ( [
				function ($complaint) {
					return $complaint->complaintMode->name;
				}
		] );
		foreach ( $modeGrouped as $mode => $items) {
				$array [$mode] = ( int ) $items->count ();
		}
		foreach ( $modeList as $mode ) {
			$chartData['backgroundColor'][] = $mode->color;
			$chartData['labels'][] = $mode->short_name;
			$chartData['data'][] = $array[$mode->name];
		}
		return $chartData;
	}
	private function getMSTATUSDataSet($filters, $queryData){
		return $this->getSTATUSData($filters, $queryData);
	}
	private function getMAWISEDataSet($filters, $queryData){
// 		$areaList = Area::all ();
		$areaList = Area::where('status','ACT')->get();
		$array = array ();
		$chartData['title'] = DateHelper::getMonthLongFormat($filters['month']). ' '.DateHelper::getYear();
		foreach ( $areaList as $area ) {
			$array [$area->name] = 0;
		}
		$areaGrouped = $queryData->groupBy ( [
				function ($complaint) {
//Removed in CR3			    
// 					if(isset($complaint->SubCategory->area))
// 						return $complaint->SubCategory->area->name;
                    if(isset($complaint->area))
                        return $complaint->area->name;
				}
		] );
		foreach ( $areaGrouped as $area => $items ) {
				$array [$area] = ( int ) $items->count ();
		}
		foreach ( $areaList as $area ) {
			$chartData['backgroundColor'][] = $area->color;
			$chartData['labels'][] = $area->short_name;
			$chartData['data'][] = $array[$area->name];
		}
		return $chartData;
	}
	private function getMRESOLDataSet($filters, $queryData){
// 		$currentMonth = DateHelper::getMonthLongFormat($filters['month']);
		$array = array();
		$array ['PEN'] = $array ['INP'] = $array ['ESC'] = $array ['REP'] = $array ['COM'] = $array ['CLO'] = $array ['REJ'] = 0;
		$statusGrouped = $queryData->groupBy ( [
			function ($complaint) {
				return $complaint->status;
			}
		] );
		$tableData = [
				array('description' => 'Total Complaints','month' => 0), //0 
				array('description' => 'Resolved','month' => 0), //1 
				array('description' => 'Unresolved','month' => 0), //2 
				array('description' => 'Resolved within 3 days','month' => 0), //3 
				array('description' => 'Within one week','month' => 0), //4
				array('description' => 'Within 2 weeks','month' => 0), //5
				array('description' => 'More than 2 weeks','month' => 0) //6
		];
		/**
		 * Total Complaints = PEN + INP + ESC + REP + COM + CLO + REJ
		 * Resolved = CLO
		 * Unresolved = PEN + INP + ESC + REP + COM + REJ
		 */
		foreach ($statusGrouped as $status => $items ) {
			$array [$status] = ( int ) $items->count ();
			$tableData[0]['month'] = AnalysisHelper::generateSum ( $tableData[0]['month']??0, $array [$status] ?? 0);
			if($status == 'CLO'){
				foreach ($items as $item){
					$diffInDays = (int) DateHelper::getDateDiff($item->open_date, $item->close_date);
					if($diffInDays > 14)
						$tableData[6]['month'] = ++$tableData[6]['month'];
					else if($diffInDays <= 14 && $diffInDays > 7 )
						$tableData[5]['month'] = ++$tableData[5]['month'];
					else if($diffInDays <= 7 && $diffInDays > 4 )
						$tableData[4]['month'] = ++$tableData[4]['month'];
					else if($diffInDays <= 3 )
						$tableData[3]['month'] = ++$tableData[3]['month'];
				}
			}
		}
		$tableData[2]['month'] = (int) $array['PEN'] + (int) $array['INP'] + (int) $array['ESC'] + (int) $array['REP'] + (int) $array['COM'] + (int) $array['REJ'];
		$tableData[1]['month'] = (int) $array['CLO']; // + (int) $array['COM'];
		return $tableData;
	}
	private function getMBOXDataSet($filters, $queryData){
		return $this->getBOXStatsData($filters, $queryData);
	}
	private function getANUMDataSet($filters, $queryData){
// 		$modeList = ComplaintMode::all ();
		$modeList = ComplaintMode::where('status','ACT')->get();
		$this->monthList = DateHelper::getMonthList ($filters['range']['year'],'list','M');
		$chartData['title'] = $filters['range']['year'];
		$array = $colorArr = array ();
		foreach ( $modeList as $mode) {
			foreach ( $this->monthList as $month ) {
				$array[$mode->name][$month] = 0;
				$colorArr[$mode->name] = $mode->color;
			}
		}
		$modeGrouped = $queryData->groupBy ( [				
				function ($complaint) {
					return $complaint->complaintMode->name;
				},
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'M' );
				}
		] );
		foreach ( $modeGrouped as $mode => $monthList) {
			foreach ( $monthList as $month => $items ) {
				$array [$mode][$month] = ( int ) $items->count ();
			}
		}
		$chartData['labels'] = $this->monthList;
		foreach ( $array as $mode => $monthList ) {
			$set = array();
			$set['label'] = $mode;
			$set['borderWidth'] = 1;
			$set['backgroundColor'] = $colorArr[$mode];
			foreach ($monthList as $month => $value){
				$set['data'][] = $value;
			}
			$chartData['datasets'][] = $set;
		}
		return $chartData;
	}
	private function getASTATUSDataSet($filters, $queryData){
		return $this->getSTATUSData($filters, $queryData);
	}
	private function getAAWISEDataSet($filters, $queryData){
// 		$areaList = Area::all ();
		$areaList = Area::where('status','ACT')->get();
		$this->monthList = DateHelper::getMonthList ($filters['range']['year'],'list','M');
		$chartData['title'] = $filters['range']['year'];
		$array = $colorArr = array ();
		foreach ( $areaList as $area ) {
			foreach ( $this->monthList as $month ) {
				$array [$area->name] [$month] = 0;
				$colorArr[$area->name] = $area->color;
			}
		}
		$areaGrouped = $queryData->groupBy ( [
		    function ($complaint){
//Removed in CR3
// 					if(isset($complaint->SubCategory->area))
// 						return $complaint->SubCategory->area->name;
					if(isset($complaint->area))
						return $complaint->area->name;
				},
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'M' );
				}
				] );
		foreach ( $areaGrouped as $area => $monthList ) {
			foreach ( $monthList as $month => $items ) {
				$array [$area] [$month] = ( int ) $items->count ();
			}
		}
		$chartData['labels'] = $this->monthList;
		foreach ( $array as $area => $monthList ) {
			$set = array();
			$set['label'] = $area;
			$set['borderWidth'] = 1;
			$set['backgroundColor'] = $colorArr[$area];
			foreach ($monthList as $month => $value){
				$set['data'][] = $value;
			}
			$chartData['datasets'][] = $set;
		}
		return $chartData;
	}
	private function getARESOLDataSet($filters, $queryData){
		$this->monthList = DateHelper::getMonthList ($filters['range']['year']);
		$chartData['title'] = $filters['range']['year'];
		$array = array ();
		foreach ( $this->monthList as $month ) {
			$array [$month]['month'] = $month;
			$array [$month]['PEN'] = $array [$month]['INP'] = $array [$month]['ESC'] = $array [$month]['REP'] = $array [$month]['COM'] = $array [$month]['CLO'] = $array [$month]['REJ'] = 0;
		}		
		$monthGrouped = $queryData->groupBy ( [
			function ($complaint) {
				return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
			},
			function ($complaint) {
				return $complaint->status;
			}
		] );
		foreach ($monthGrouped as $month => $statusList){
			foreach ( $statusList as $status => $items ) {
				$array [$month][$status] = ( int ) $items->count ();
			}
		}
		$tableData = array();
		/**
		 * complaint_received = PEN + INP + ESC + REP + COM + CLO + REJ
		 * attended = INP + ESC + REP + COM + CLO + REJ
		 * resolved = CLO
		 * unresolved = PEN + INP + ESC + REP + COM + REJ
		 */
		foreach ($array as $month => $dataList){
			$tableData[] = array(
				'month' => $dataList['month'],
			    'complaint_received' => (int) $dataList['PEN'] + (int) $dataList['INP'] + (int) $dataList['ESC'] + (int) $dataList['REP'] + (int) $dataList['COM'] + (int) $dataList['CLO'] + (int) $dataList['REJ'],
			    'attended' => (int) $dataList['INP'] + (int) $dataList['ESC'] + (int) $dataList['REP'] + (int) $dataList['COM'] + (int) $dataList['CLO'] + (int) $dataList['REJ'],
			    'resolved' => (int) $dataList['CLO'], //+ (int) $dataList['COM'],
			    'unresolved' => (int) $dataList['PEN'] + (int) $dataList['INP'] + (int) $dataList['ESC'] + (int) $dataList['REP'] + (int) $dataList['COM'] + (int) $dataList['REJ'],
			);
		}
		return $tableData;
	}
	private function getABOXDataSet($filters, $queryData){
		return $this->getBOXStatsData($filters, $queryData);
	}
	private function getSTATUSData($filters, $queryData){
		$statusArr = ['PEN','INP','ESC','REP','COM','CLO','REJ'];
		$arr = array();
		$chartData['labels'] = ['Resolved', 'Unresolved'];
		$chartSum = array('Resolved' => 0, 'Unresolved' => 0);
		if ($filters ['mode'] == 'month')
			$chartData ['title'] = DateHelper::getMonthLongFormat ($filters['month']) . ' ' . DateHelper::getYear ();
		else
			$chartData ['title'] = DateHelper::getYear ();
		foreach ( $statusArr as $status ) {
			$arr [$status] = 0;
		}
		$statusGrouped = $queryData->groupBy ( [ 
				function ($complaint) {
					return $complaint->status;
				} 
		] );
		$sum = 0;
		foreach ( $statusGrouped as $status => $items ) {
			$array [$status] = ( int ) $items->count ();
			$sum = AnalysisHelper::generateSum ( $sum, $array [$status] );
		}
		foreach ( $statusArr as $status ) {
		    if ($status == 'CLO') // || $status == 'COM'
		        $chartSum ['Resolved'] = AnalysisHelper::generateSum ( $chartSum ['Resolved'], $array [$status] ?? 0);
			else
				$chartSum ['Unresolved'] = AnalysisHelper::generateSum ( $chartSum ['Unresolved'], $array [$status] ?? 0);
		}
		foreach ( $chartData ['labels'] as $label ) {
			$chartData ['data'] [] = AnalysisHelper::generateWeightage ( $sum, $chartSum [$label] );
		}
		return $chartData;
	}
	private function getBOXStatsData($filters, $queryData){
		$statusArr = ['PEN','INP','ESC','REP','COM','CLO','REJ'];
		if ($filters ['mode'] == 'month')
			$chartData ['title'] = DateHelper::getMonthLongFormat ($filters['month']);
		else
			$chartData ['title'] = DateHelper::getYear ();
		foreach ( $statusArr as $status ) {
			$array [$status] = 0;
		}
		$statusGrouped = $queryData->groupBy ( [
				function ($complaint) {
					return $complaint->status;
				}
		] );
		foreach ( $statusGrouped as $status => $items ) {
			$array [$status] = ( int ) $items->count ();
		}
		foreach ( $statusArr as $status ) {
		    if($status == 'CLO')
				$chartData['closed'] = $array [$status] ?? 0;
			if($status == 'COM')
			    $chartData['completed'] = $array [$status] ?? 0;
			if($status == 'REP')
			   $chartData['replied'] = $array [$status] ?? 0;
		    if($status == 'INP' || $status == 'ESC')
		        $chartData['inprogress'] = AnalysisHelper::generateSum ( $chartData ['inprogress']??0, $array [$status] ?? 0);
			if($status == 'PEN')
				$chartData['pending'] = $array [$status] ?? 0;
			
			$chartData['total'] = AnalysisHelper::generateSum ( $chartData['total']??0, $array [$status] ?? 0);
		}
		return $chartData;
	}
}