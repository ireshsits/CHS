<?php

namespace App\Helpers;
use App\Models\Entities\Category;
use App\Models\Entities\Area;

class ExcelTitleHelper {
	
	public static function getTitle($filters){
		switch($filters['code']){
			case 'MWA' 		: return 'MODE WISE ANALYSIS'; //done
// 			case 'CRTTM' 	: return 'COMPLAINTS RECEIVED THROUGH TOP MANAGMENT';
// 			case 'CRTCCHL' 	: return 'COMPLAINTS RECEIVED THROUGH CARD CENTER HOT LINES - (Handle by CCC)';
// 			case 'DACRTCCHL': return 'DETAILED ANALYSIS OF COMPLAINTS RECEIVED THROUGH CARD CENTER HOTLINES';
			case 'AWA' 	    : return 'AREA WISE ANALYSIS'; //done
			case 'DAWA' 	: return 'AREA WISE DETAILED ANALYSIS - '.self::getSubTitle($filters['area_id_fk'], 'Area'); //done
// 			case 'AWODA' 	: return 'AREA WISE OVERALL DETAILED ANALYSIS';
			case 'CWA' 		: return 'CATEGORY WISE ANALYSIS'; //done
			case 'DCWA' 	: return 'DETAILED CATEGORY WISE ANALYSIS - '.self::getSubTitle($filters['category_id_fk'], 'Category'); //done
			case 'DSCWA' 	: return 'DETAILED SUB CATEGORY WISE ANALYSIS - '.self::getSubTitle($filters['category_id_fk'], 'Category'); //done
			case 'IDSCWA'   : return 'IN DETAILED SUB CATEGORY WISE ANALYSIS - '.self::getSubTitle($filters['category_id_fk'], 'Category'); // pending
// 			case 'BSL' 		: return 'BRANCH SERVICE LAPSES';
// 			case 'BSLZR' 	: return 'BRANCH SERVICE LAPSES - Zonal wise/ Region wise Monthly Comparison';
// 			case 'CC' 		: return 'CARD CENTRE';
			case 'RA' 		: return 'RESOLUTION AUTHORITY'; //done
			case 'TFCR' 	: return 'TIME FRAME OF COMPLAINT RESOLUTION'; //done
			case 'MG'       : return 'MODE GRAPH'; //done
			case 'CG'       : return 'CATEGORY GRAPH'; //done
// 			case 'TE' 		: return 'THROUGH EMAIL';
// 			case 'TCR' 		: return 'THROUGH CSAT RATINGS';
		}
	}
	
	private static function getSubTitle($id, $Entity){
		switch($Entity){
			case 'Area'		: return strtoupper(Area::where('area_id_pk',$id)->value('name'));
			case 'Category'	: return strtoupper(Category::where('category_id_pk',$id)->value('name'));
		}
	}
}