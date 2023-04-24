<?php

namespace App\Helpers;

use DateHelper;
use RoleHelper;
use App\Models\Entities\ComplaintMode;

class TableHeaderHelper{
	
	public static function getHeadersAndColumns($filters, $mode='ALL'){
		switch($filters['code']){
			case 'MWA' 		: return self::getMWAColumns($filters,$mode); //done
// 			case 'CRTTM' 	: return self::getCRTTMColumns($filters,$mode);
// 			case 'CRTCCHL' 	: return self::getCRTCCHLColumns($filters,$mode);
// 			case 'DACRTCCHL': return self::getCRTCCHLDAColumns($filters,$mode);
			case 'AWA' 	    : return self::getAWAWColumns($filters,$mode); //done
			case 'DAWA' 	: return self::getDAWAColumns($filters,$mode); //done
// 			case 'AWODA' 	: return self::getAWODAColumns($filters,$mode);
			case 'CWA' 		: return self::getCWAColumns($filters,$mode); // done
			case 'DCWA' 	: return self::getDCWAColumns($filters,$mode,2); //done
			case 'DSCWA' 	: return self::getDCWAColumns($filters,$mode,3); //done
			case 'IDSCWA' 	: return self::getDCWAColumns($filters,$mode,4); //pending
// 			case 'BSL' 		: return self::getBSLColumns($filters,$mode);
// 			case 'BSLZR' 	: return self::getBSLZRColumns($filters,$mode);
// 			case 'CC' 		: return self::getCCColumns($filters,$mode);
			case 'RA' 		: return self::getRAColumns($filters,$mode); //done
			case 'TFCR'     : return self::getTFCRColumns($filters, $mode); //done
			case 'MG'       : return self::getMG($filters, $mode); //done
			case 'CG'       : return self::getCG($filters, $mode); //done
// 			case 'TE' 		: return self::getTEColumns($filters,$mode);
// 			case 'TCR' 		: return self::getTCRColumns($filters,$mode);
		}
	}
	
	private static function getMWAColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Mode','name' => 'Mode','title' => 'Mode')];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Mode'];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getCRTTMColumns($filters, $mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Name','name' => 'Name','title' => 'Name')];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
		}else{
			$array =['Name'];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
		}
		return $array;
	}
	
	private static function getCRTCCHLColumns(){
		$array =['Name'];
		foreach (DateHelper::getMonthList($filters['year']) as $month){
			$array[] = $month;
		}
		$array[] = 'Total';
		return $array;
	}
	
	private static function getCRTCCHLDAColumns(){
		$array =['Mode'];
		foreach (DateHelper::getMonthList($filters['year']) as $month){
			$array[] = $month;
		}
		$array[] = 'Total';
		return $array;
	}
	
	private static function getAWAWColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Area','name' => 'Area','title' => 'Area')];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Area'];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getDAWAColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Sub_Category','name' => 'Sub_Category','title' => 'Sub_Category')];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Sub_Category'];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getAWODAColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Area','name' => 'Area','title' => 'Area')];
			$array[] = array('data' => 'Sub_Category','name' => 'Sub_Category','title' => 'Sub_Category');
// 			foreach (DateHelper::getMonthList($filters['year']) as $month){
// 				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
// 			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
			$array[] = array('data' => 'Area_Total', 'name'=> 'Area_Total', 'title' => 'Area_Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Area'];
			$array[] = 'Sub_Category';
// 			foreach (DateHelper::getMonthList($filters['year']) as $month){
// 				$array[] = $month;
// 			}
			$array[] = 'Total';
			$array[] = 'Area_Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getCWAColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Category','name' => 'Category','title' => 'Category')];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Category'];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getDCWAColumns($filters,$mode, $level=2){
		if($mode == 'ALL'){
			$array =[array('data' => 'Sub_Category','name' => 'Sub_Category','title' => 'Category_level_'.$level)];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Category_level_'.$level];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getBSLZRColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Zone','name' => 'Zone','title' => 'Zone')];
			$array[] = array('data' => 'Region','name' => 'Region','title' => 'Region');
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Regional_Total', 'name'=> 'Regional_Total', 'title' => 'Regional_Total');
			$array[] = array('data' => 'Zonal_Total', 'name'=> 'Zonal_Total', 'title' => 'Zonal_Total');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Zone'];
			$array[] ='Region';
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Regional_Total';
			$array[] = 'Zonal_Total';
			$array[] = 'Weightage';
		}
		return $array;
	}
	private static function getRAColumns($filters, $mode){
	    $roleList=explode('|',RoleHelper::getResolvedByRoles('RAW_NAMES'));
	    foreach ($roleList as $k=>$role){
	        $roleList[$k] = strtoupper($role);
	    };
         if($mode == 'ALL'){
             $array =[array('data' => 'Owner','name' => 'Owner','title' => 'Owner Category')];
             foreach ($roleList as $role){
                 $array[] = array('data' => $role, 'name'=>$role, 'title' => $role);
             }
             $array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
         }else{
             $array =['Owner'];
             foreach ($roleList as $role){
                 $array[] = $role;
             }
             $array[] = 'Total';
         }
         return $array;
	}
	
	private static function getTFCRColumns($filters, $mode){
	    $modeList = ComplaintMode::where('status','ACT')->get();
	    if($mode == 'ALL'){
	        $array =[array('data' => 'Description','name' => 'Description','title' => 'Description')];
	        foreach ($modeList as $mode){
	            $array[] = array('data' => $mode->name, 'name'=>$mode->name, 'title' => $mode->name);
	        }
	        $array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
	        $array[] = array('data' => 'Percentage', 'name'=> 'Percentage', 'title' => 'Resolution percentage(%)');
	    }else{
	        $array =['Description'];
	        foreach ($modeList as $mode){
	            $array[] = $mode->name;
	        }
	        $array[] = 'Total';
	        $array[] = 'Resolution percentage(%)';
	    }
	    return $array;
	}
	
	private static function getTEColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Name','name' => 'Name','title' => 'Name')];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = array('data' => $month, 'name'=>$month, 'title' => $month);
			}
			$array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
		}else{
			$array =['Name'];
			foreach (DateHelper::getMonthList($filters['year']) as $month){
				$array[] = $month;
			}
			$array[] = 'Total';
		}
		return $array;
	}
	
	private static function getTCRColumns($filters,$mode){
		if($mode == 'ALL'){
			$array =[array('data' => 'Name','name' => 'Name','title' => 'Name')];
			$array[] = array('data' => 'Total_Ratings', 'name'=> 'Total_Ratings', 'title' => 'Total_Ratings');
			$array[] = array('data' => 'Excellent_Ratings', 'name'=> 'Excellent_Ratings', 'title' => 'Excellent_Ratings');
			$array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
		}else{
			$array =['Name'];
			$array[] = 'Total_Ratings';
			$array[] = 'Excellent_Ratings';
			$array[] = 'Weightage';
		}
		return $array;
	}
	
	private static function getMG($filters, $mode){
	    if($mode == 'ALL'){
	        $array =[array('data' => 'Mode','name' => 'Mode','title' => 'Mode')];
	        $array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
	        $array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
	    }else{
	        $array =['Mode'];
	        $array[] = 'Total';
	        $array[] = 'Weightage';
	    }
	    return $array;
	}
	
	private static function getCG($filters, $mode){
	    if($mode == 'ALL'){
	        $array =[array('data' => 'Category','name' => 'Category','title' => 'Category')];
	        $array[] = array('data' => 'Total', 'name'=> 'Total', 'title' => 'Total');
	        $array[] = array('data' => 'Weightage', 'name'=> 'Weightage', 'title' => 'Weightage(%)');
	    }else{
    	    $array = ['Category'];
    	    $array[] = 'Total';
    	    $array[] = 'Weightage';
	    }
	    return $array;
	}
}