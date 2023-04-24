<?php

namespace App\Classes\Analyses;

use AnalysisHelper;
use DateHelper;
use TableHeaderHelper;
use RoleHelper;
use App\Models\Entities\ComplaintMode;
use App\Models\Entities\Area;
use App\Models\Entities\Zone;
use App\Models\Entities\Region;
use App\Models\Entities\Category;
use App\Models\Entities\SubCategory;
use App\Models\Entities\Setting;
use Log;

class BuildAnalysisDataSet {
	
	protected $monthList;
	protected $headerRow;
	protected $chartDataRow;
	protected $categoryLevels;
	public function __construct(){
	    $this->categoryLevels = Setting::getCategorySettings()->CATEGORY_LEVELS;
	    $this->excelSetting = Setting::getExcelSettings();
	    $this->chartDataRow = $this->excelSetting->CHART_DATA_ROW;
	    $this->startingRow = $this->excelSetting->STARTING_ROW;
	}
	
	public function build($filters, $queryData) {
		try{
			$this->monthList = DateHelper::getMonthList ( $filters ['year'] );
			$this->headerRow = TableHeaderHelper::getHeadersAndColumns($filters, $mode='NAME');
			
			switch ($filters ['code']) {
				case 'MWA' 		 : return $this->getMWADataSet($filters, $queryData);break; //done
// 				case 'CRTTM' 	 : return $this->getCRTTMDataSet($filters, $queryData);break;
// 				case 'CRTCCHL' 	 : return $this->getCRTCCHLDataSet($filters, $queryData);break;
// 				case 'DACRTCCHL' : return $this->getCRTCCHLDADataSet($filters, $queryData);break;
				case 'AWA' 	     : return $this->getAWADataSet($filters, $queryData);break; //done
				case 'DAWA' 	 : return $this->getDAWADataSet($filters, $queryData);break; //done
// 				case 'AWODA' 	 : return $this->getAWODADataSet($filters, $queryData);break;
				case 'CWA' 		 : return $this->getCWADataSet($filters, $queryData);break; //done
				case 'DCWA' 	 : return $this->getDCWADataSet($filters, $queryData,2);break; //done
				case 'DSCWA' 	 : return $this->getDCWADataSet($filters, $queryData,3);break; //done
				case 'IDSCWA' 	 : return $this->getDCWADataSet($filters, $queryData,4);break; //done
// 				case 'BSL' 		 : return $this->getBSLDataSet($filters, $queryData);break;
// 				case 'BSLZR' 	 : return $this->getBSLZRDataSet($filters, $queryData);break;
// 				case 'CC' 		 : return $this->getCCDataSet($filters, $queryData);break;
				case 'RA' 		 : return $this->getRADataSet($filters, $queryData);break;
				case 'TFCR'      : return $this->getTFCRDataSet($filters, $queryData);break;// inprogress
// 				case 'TE' 		 : return $this->getTEDataSet($filters, $queryData);break;
// 				case 'TCR' 		 : return $this->getTCRDataSet($filters, $queryData);break;
				case 'MG'        : return $this->getMG($filters, $queryData);break;
				case 'CG'        : return $this->getCG($filters, $queryData);break;
			}
		}catch (\Exception $e){
			Log::error ( '-----------Build Analysing DataSet Failed---------' );
			Log::error ( 'analyse---------error---------' . json_encode ( $e->getMessage () ) );
			Log::error ( 'analyse---------filters---------' . json_encode ( $filters) );
			Log::error ( 'analyse---------queryData---------' . json_encode ( $queryData) );
		}
	}
	
	private function getMWADataSet($filters, $queryData){
		$modeList = ComplaintMode::where('status','ACT')->get();
		$array = $Weightage = array ();
		foreach ( $modeList as $mode ) {
			$array [$mode->name] ['Mode'] = $mode->name;
			foreach ( $this->monthList as $month ) {
				$array [$mode->name] [$month] = 0;
			}
			$array [$mode->name] ['Total'] = 0;
			$array [$mode->name] ['Weightage'] = 0;
		}
		/**
		 * Get Total row
		 */
		$array['Total']['Mode'] = 'Total';
		foreach ( $this->monthList as $month ) {
		    $array ['Total'] [$month] = 0;
		}
		$array ['Total'] ['Total'] = 0;
		$array ['Total'] ['Weightage'] = 0;
		
		$modeGrouped = $queryData->groupBy ( [ 
				function ($complaint) {
					return $complaint->complaintMode->name;
				},
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
				}
		] );
		foreach ( $modeGrouped as $mode => $monthList ) {
			foreach ( $monthList as $month => $items) {
				$array [$mode] [$month] = ( int ) $items->count ();
				$array [$mode] ['Total'] = AnalysisHelper::generateSum ( $array [$mode] ['Total'], $array [$mode] [$month] );
				/**
				 * Get Total row
				 */
				$array ['Total'] [$month] = AnalysisHelper::generateSum ( $array ['Total'] [$month], $array [$mode] [$month] );
				$array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$mode] ['Total'] );
			}
		}
        /**
         * Weightage Calculate
         */
        $sum = 0;
        foreach ($array as $mode => $dataList) {
            if ($mode !== 'Total')
                $sum = AnalysisHelper::generateSum($sum, $dataList['Total']);
        }
        foreach ($array as $mode => $dataList) {
            if ($mode !== 'Total') {
                $percentage = AnalysisHelper::generateWeightage($sum, $dataList['Total']);
                $array[$mode]['Weightage'] = $percentage;
                $Weightage[] = $percentage;
                /**
                 * Get Total row
                 */
                $array['Total']['Weightage'] = AnalysisHelper::generateSum($array['Total']['Weightage'], $array[$mode]['Weightage']);
            }
        }
        /**
         * tableDataSet
         */
        foreach ($array as $mode => $dataList) {
            $tableDataSet[] = $dataList;
        }
        /**
         * chartDataSet
         */
        $chartDataSet['data'] = $Weightage;
        /**
         * ChartLabels
         */
        foreach ($modeList as $mode) {
            $chartLabels[] = $mode->name;
            $chartColors[] = $mode->color;
        }

        return array(
            'headerRow' => $this->headerRow,
            'tableDataSet' => $tableDataSet ?? [],
            'chartDataSet' => $chartDataSet ?? [],
            'chartLabels' => $chartLabels ?? [ ],
			'chartColors' => $chartColors ?? [ ]
		);
	}
	
	private function getCRTTMDataSet($filters, $queryData){
		$array ['Name'] = 'Corporate Management';
		foreach ( $this->monthList as $month ) {
			$array [$month] = 0;
		}
		$array ['Total'] = 0;
		$monthGrouped = $queryData->groupBy ( [
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
				}
		] );
		foreach ($monthGrouped as $month => $items ) {
				$array [$month] = ( int ) $items->count ();
				$array ['Total'] = AnalysisHelper::generateSum ( $array ['Total'], $array [$month] );
		}		
		/**
		* tableDataSet
		*/
		$tableDataSet[0] = $array;
		
		/**
		 * chartDataSet - not in use
		 */
		$chartDataSet['data'] = [];
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
		        'chartDataSet' => $chartDataSet ?? [],
				'chartLabels' => [ ],
				'chartColors' => [ ]
		);
	}
	
	private function getCRTCCHLDataSet(){
		
	}
	
	private function getCRTCCHLDADataSet(){
		
	}
	
	private function getAWADataSet($filters, $queryData){
		$areaList = Area::where('status','ACT')->get();
		$array = $Weightage = array ();
		foreach ( $areaList as $area ) {
			$array [$area->name] ['Area'] = $area->name;
			foreach ( $this->monthList as $month ) {
				$array [$area->name] [$month] = 0;
			}
			$array [$area->name] ['Total'] = 0;
			$array [$area->name] ['Weightage'] = 0;
		}
        /**
         * Get Total row
         */
        $array['Total']['Area'] = 'Total';
        foreach ($this->monthList as $month) {
            $array['Total'][$month] = 0;
        }
        $array['Total']['Total'] = 0;
        $array['Total']['Weightage'] = 0;

        $areaGrouped = $queryData->groupBy([
            function ($complaint) {
                /**
                 * Changed in CR3
                 */
                // return $complaint->SubCategory->area->name;
                return $complaint->area->name;
            },
            function ($complaint) {
                return DateHelper::formatToDate($complaint->open_date)->format('F');
            }
        ]);
        foreach ($areaGrouped as $area => $monthList) {
            foreach ($monthList as $month => $items ) {
				$array [$area] [$month] = ( int ) $items->count ();
				$array [$area] ['Total'] = AnalysisHelper::generateSum ( $array [$area] ['Total'], $array [$area] [$month] );
				/**
				 * Get Total row
				 */
				$array ['Total'] [$month] = AnalysisHelper::generateSum ( $array ['Total'] [$month], $array [$area] [$month] );
				$array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$area] ['Total'] );
			}
		}		
		/**
		* Weightage Calculate
		*/
		$sum = 0;
		foreach ( $array as $area => $dataList ) {		    
		    if($area !== 'Total')
			 $sum = AnalysisHelper::generateSum ( $sum, $dataList ['Total'] );
		}
		foreach ( $array as $area => $dataList ) {
		    if($area !== 'Total'){
		        $percentage = AnalysisHelper::generateWeightage ( $sum, $dataList ['Total'] );
		        $array [$area] ['Weightage'] = $percentage;
		        $Weightage [] = $percentage;
    			/**
    			 * Get Total row
    			 */
    			$array ['Total'] ['Weightage'] = AnalysisHelper::generateSum ( $array ['Total'] ['Weightage'], $array [$area] ['Weightage'] );
		    }
		}
		/**
		 * tableDataSet
		 */
		foreach ( $array as $area => $dataList ) {
			$tableDataSet [] = $dataList;
		}
		/**
		 * chartDataSet
		 */
		$chartDataSet ['data'] = $Weightage;
		/**
		 * ChartLabels
		 */
		foreach ( $areaList as $area ) {
			$chartLabels [] = $area->name;
			$chartColors [] = $area->color;
		}
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => $chartDataSet ?? [ ],
				'chartLabels' => $chartLabels ?? [ ],
				'chartColors' => $chartColors ?? [ ]
		);
	}
	
	private function getDAWADataSet($filters, $queryData){
	    /**
	     * Changed in CR3
	     * @var unknown $subCategoryList
	     */
// 		$subCategoryList = SubCategory::where('area_id_fk',$filters['area_id_fk'])->where('status','ACT')->get();
	    $subCategoryList = Category::where('category_level', 2)->where('status','ACT')->get();
        $array = $Weightage = array();
        foreach ($subCategoryList as $subCategory) {
            $array[$subCategory->name] ['Sub_Category'] = $subCategory->name;
			foreach ( $this->monthList as $month ) {
				$array [$subCategory->name] [$month] = 0;
            }
            $array[$subCategory->name]['Total'] = 0;
            $array[$subCategory->name]['Weightage'] = 0;
		}
		/**
		 * Get Total row
		 */
		$array['Total']['Sub_Category'] = 'Total';
		foreach ( $this->monthList as $month ) {
		    $array ['Total'] [$month] = 0;
		}
		$array ['Total'] ['Total'] = 0;
		$array ['Total'] ['Weightage'] = 0;
		
        $subCategoryGrouped = $queryData->groupBy([
            function ($complaint) {
                /**
                 * Changed in CR3
                 * Get the mapping related to category level 2
                 */
                // return $complaint->subCategory->name;
                if ($this->categoryLevels == 3)
                    return $complaint->category->parentCategory->name;
                if ($this->categoryLevels == 4)
                    return $complaint->category->parentCategory->parentCategory->name;
                if ($this->categoryLevels == 5)
				    return $complaint->category->parentCategory->parentCategory->parentCategory->name;
				if ($this->categoryLevels == 6)
				    return $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->name;
				},
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
				}
		] );
		foreach ( $subCategoryGrouped as $subCategory => $monthList ) {
			foreach ( $monthList as $month => $items ) {
				$array [$subCategory] [$month] = ( int ) $items->count ();
				$array [$subCategory] ['Total'] = AnalysisHelper::generateSum ( $array [$subCategory] ['Total'], $array [$subCategory] [$month] );				
				/**
				* Get Total row
				*/
				$array ['Total'] [$month] = AnalysisHelper::generateSum ( $array ['Total'] [$month], $array [$subCategory] [$month] );
				$array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$subCategory] ['Total'] );
			}
		}
		/**
		 * Weightage Calculate
		 */
		$sum = 0;
		foreach ( $array as $subCategory => $dataList ) {		    
		    if($subCategory !== 'Total')
			 $sum = AnalysisHelper::generateSum ( $sum, $dataList ['Total'] );
		}
		foreach ( $array as $subCategory => $dataList ) {
		    if($subCategory !== 'Total'){
		        $percentage = AnalysisHelper::generateWeightage ( $sum, $dataList ['Total'] );
		        /**
		         * if Condition Added in CR3
		         * Remove all with weightage 0
		         */
		        if($percentage > 0){
		            $array [$subCategory] ['Weightage'] = $percentage;
		            $Weightage [] = $percentage;
		            /**
		             *  Added in CR3 in support of filtering categories with weightage > 0
		             * @var Ambiguous $selectedCategoryList
		             */
		            $selectedCategoryList[] = $subCategory;
		            /**
		             * Get Total row
		             */
		            $array ['Total'] ['Weightage'] = AnalysisHelper::generateSum ( $array ['Total'] ['Weightage'], $array [$subCategory] ['Weightage'] );
		        } else {
		            unset($array[$subCategory]);
		        }
		    }
        }
        /**
         * tableDataSet
         */
        foreach ($array as $subCategory => $dataList) {
            $tableDataSet[] = $dataList;
        }
        /**
         * chartDataSet
         */
        $chartDataSet['data'] = $Weightage;
        /**
         * ChartLabels
         */
        foreach ($subCategoryList as $subCategory) {
            /**
             * if Condition Added in CR3
             * Remove all with weightage 0, so the chart labels also removed
             */
            if (isset($selectedCategoryList) && in_array($subCategory->name, $selectedCategoryList)) {
                $chartLabels[] = $subCategory->name;
                $chartColors [] = $subCategory->color;
		    }
		}
		
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => $chartDataSet ?? [ ],
				'chartLabels' => $chartLabels ?? [ ],
				'chartColors' => $chartColors ?? [ ]
		);
	}
	
	private function getAWODADataSet($filters, $queryData){
		$areaList = Area::where('status','ACT')->get();
		$array = $Weightage = array ();
		foreach ($areaList as $area){
			$areaWiseSubCategories[$area->name] = $areaSubCategoryList  = SubCategory::where('area_id_fk',$area->area_id_pk)->get();
			foreach ( $areaSubCategoryList as $subCategory ) {
				$array[$area->name][$subCategory->name] ['Area'] = $area->name;
				$array[$area->name][$subCategory->name] ['Sub_Category'] = $subCategory->name;
// 				foreach ( $this->monthList as $month ) {
// 					$array [$subCategory->name] [$month] = 0;
// 				}
				$array[$area->name][$subCategory->name] ['Total'] = 0;
				$array[$area->name][$subCategory->name] ['Area_Total'] = 0;
				$array[$area->name][$subCategory->name] ['Weightage'] = 0;
			}
		}
		$areaSubCategoryGrouped = $queryData->groupBy ( [
				function ($complaint) {
					return $complaint->subCategory->area->name;
				},
				function ($complaint) {
					return $complaint->subCategory->name;
				}
// ,
// 				function ($complaint) {
// 					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
// 				}
		] );
		$weightageSum = 0;
		foreach ( $areaSubCategoryGrouped as $area => $subCategoryList ) {
			$areaSum = 0;
			foreach ( $subCategoryList as $subCategory => $items) {
				$array [$area][$subCategory] ['Total'] = ( int ) $items->count ();
				$areaSum = AnalysisHelper::generateSum ( $areaSum , ( int ) $items->count ());
			}
			/**
			 * Used total area subcategoryList to set common Area total.
			 */
			foreach ( $areaWiseSubCategories[$area] as $subCategory) {
				$array [$area][$subCategory->name] ['Area_Total'] = $areaSum;
			}
			$weightageSum = AnalysisHelper::generateSum ( $weightageSum, ( int ) $areaSum);
		}
		/**
		 * Weightage Calculate
		 * $startBy|$endBy for the excel mergecell reference.
		 */
		
		$startBy = (int) $this->chartDataRow;
		$endBy = 0;
		foreach ( $array as $area => $subCategoryList ) {
			$i = 0;
			foreach ($subCategoryList as $subCategory => $dataList){
				$percentage = AnalysisHelper::generateWeightage ( $weightageSum, $dataList ['Area_Total'] );
				$array [$area][$subCategory] ['Weightage'] = $percentage;
				/**
				 * Area weightage is same for all the categories. so take only 1st Instance.
				 */
				if($i == 0)
					$Weightage [] = $percentage;
				$i++;
			}
			
			/**
			 * For the use of merge cell generation.
			 * Add for export adding merge cell references
			 * Excel -> Column A - Area | Column D - Area_Total | Column E - Weightage
			 * count($subCategoryList) -1 to get accurate row count with the startBy row.
			 */
			$ExcelColumns = ['A','D','E'];
			$endBy = (int)$startBy + (int)count($subCategoryList) -1;	
			foreach ($ExcelColumns as $key=>$column){
				$mergeCells[] = ''.$column.$startBy.':'.$column.$endBy.'';
			}
			$startBy = $endBy+1;
			
		}
		/**
		 * tableDataSet
		 */
		foreach ( $array as $area => $subCategoryList ) {
			foreach ($subCategoryList as $subCategory => $dataList){
				$tableDataSet [] = $dataList;
			}
		}
		/**
		 * chartDataSet
		 */
		$chartDataSet ['data'] = $Weightage;
		/**
		 * ChartLabels
		 */
		foreach ( $areaList as $area ) {
			$chartLabels [] = $area->name;
			$chartColors [] = $area->color;
		}
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => $chartDataSet ?? [ ],
				'chartLabels' => $chartLabels ?? [ ],
				'chartColors' => $chartColors ?? [ ],
				'mergeCells' => $mergeCells ?? []
		);
	}
	
	private function getCWADataSet($filters, $queryData){
		$categoryList = Category::where('category_level', 1)->where('status','ACT')->get();
		$array = $Weightage = array ();
		foreach ( $categoryList as $category ) {
			$array [$category->name] ['Category'] = $category->name;
			foreach ( $this->monthList as $month ) {
				$array [$category->name] [$month] = 0;
			}
			$array [$category->name] ['Total'] = 0;
			$array [$category->name] ['Weightage'] = 0;
		}
		/**
		 * Get Total row
		 */
		$array['Total']['Category'] = 'Total';
		foreach ( $this->monthList as $month ) {
		    $array ['Total'] [$month] = 0;
		}
		$array ['Total'] ['Total'] = 0;
		$array ['Total'] ['Weightage'] = 0;
		
		$categoryGrouped = $queryData->groupBy ( [
				function ($complaint) {
				/**
				 * Removed with CR3
				 */
// 					return $complaint->category->name;
			    /**
			     * Added in CR3
			     * Get the mapping related to category level 1
			     * @var unknown $categoryGrouped
			     */
                if ($this->categoryLevels == 3)
                    return $complaint->category->parentCategory->parentCategory->name;
                if ($this->categoryLevels == 4)
                    return $complaint->category->parentCategory->parentCategory->parentCategory->name;
                if ($this->categoryLevels == 5)
                    return $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->name;
                if ($this->categoryLevels == 6)
                    return $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->parentCategory->name;
				},
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
				}
			] );
		foreach ( $categoryGrouped as $category => $monthList ) {
			foreach ( $monthList as $month => $items ) {
				$array [$category] [$month] = ( int ) $items->count ();
				$array [$category] ['Total'] = AnalysisHelper::generateSum ( $array [$category] ['Total'], $array [$category] [$month] );				/**
				* Get Total row
				*/
				$array ['Total'] [$month] = AnalysisHelper::generateSum ( $array ['Total'] [$month], $array [$category] [$month] );
				$array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$category] ['Total'] );
			}
		}
		/**
		 * Weightage Calculate
		 */
		$sum = 0;
		foreach ( $array as $category => $dataList ) {
		    if($category !== 'Total')
			 $sum = AnalysisHelper::generateSum ( $sum, $dataList ['Total'] );
		}
		foreach ( $array as $category => $dataList ) {
		    if($category !== 'Total'){
    			$percentage = AnalysisHelper::generateWeightage ( $sum, $dataList ['Total'] );
    			$array [$category] ['Weightage'] = $percentage;
    			$Weightage [] = $percentage;
    			/**
    			 * Get Total row
    			 */
    			$array ['Total'] ['Weightage'] = AnalysisHelper::generateSum ( $array ['Total'] ['Weightage'], $array [$category] ['Weightage'] );
		    }
		}
		/**
		 * tableDataSet
		 */
		foreach ( $array as $category => $dataList ) {
			$tableDataSet [] = $dataList;
		}
		/**
		 * chartDataSet
		 */
		$chartDataSet ['data'] = $Weightage;
		/**
		 * ChartLabels
		 */
		foreach ( $categoryList as $category ) {
			$chartLabels [] = $category->name;
			$chartColors [] = $category->color;
		}
		
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => $chartDataSet ?? [ ],
				'chartLabels' => $chartLabels ?? [ ],
				'chartColors' => $chartColors ?? [ ],
		);
	}
	
	private function getDCWADataSet($filters, $queryData, $level = 2){
	    /**
	     * Changed in CR3
	     * @var unknown $subCategoryList
	     */
// 		$subCategoryList = SubCategory::where('category_id_fk',$filters['category_id_fk'])->where('status','ACT')->get();
	    $subCategoryList = Category::where('parent_category_id',$filters['category_id_fk'])->where('status','ACT')->get();
		$array = $Weightage = array ();
		foreach ( $subCategoryList as $subCategory ) {
			$array [$subCategory->name] ['Sub_Category'] = $subCategory->name;
            foreach ($this->monthList as $month) {
                $array[$subCategory->name][$month] = 0;
            }
            $array[$subCategory->name]['Total'] = 0;
            $array[$subCategory->name]['Weightage'] = 0;
		}
		/**
		 * Get Total row
		 */
		$array['Total']['Sub_Category'] = 'Total';
		foreach ( $this->monthList as $month ) {
		    $array ['Total'] [$month] = 0;
		}
		$array ['Total'] ['Total'] = 0;
		$array ['Total'] ['Weightage'] = 0;
        $subCategoryGrouped = $queryData->groupBy([
            function ($complaint) use($level) {
                /**
                 * Changed in CR3
                 * Refer to the 2nd level/ 3rd level of category
                 */
                // return $complaint->subCategory->name;
                if ($level == 2) {
                    if ($this->categoryLevels == 3)
                        return $complaint->category->parentCategory->name;
                    if ($this->categoryLevels == 4)
                        return $complaint->category->parentCategory->parentCategory->name;
                    if ($this->categoryLevels == 5)
                        return $complaint->category->parentCategory->parentCategory->parentCategory->name;
                    if ($this->categoryLevels == 6)
                        return $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->name;
                } else if ($level == 3) {
                    if ($this->categoryLevels == 3)
                        return $complaint->category->name;
                    if ($this->categoryLevels == 4)
                        return $complaint->category->parentCategory->name;
                    if ($this->categoryLevels == 5)
                        return $complaint->category->parentCategory->parentCategory->name;
                    if ($this->categoryLevels == 6)
                        return $complaint->category->parentCategory->parentCategory->parentCategory->name;
                }
			},
			function ($complaint) {
				return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
			}
		] );
		foreach ( $subCategoryGrouped as $subCategory => $monthList ) {
			foreach ( $monthList as $month => $items ) {
				$array [$subCategory] [$month] = ( int ) $items->count ();
				$array [$subCategory] ['Total'] = AnalysisHelper::generateSum ( $array [$subCategory] ['Total'], $array [$subCategory] [$month] );
				/**
				 * Get Total row
				 */
				$array ['Total'] [$month] = AnalysisHelper::generateSum ( $array ['Total'] [$month], $array [$subCategory] [$month] );
				$array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$subCategory] ['Total'] );
			}
		}
		/**
		 * Weightage Calculate
		 */
		$sum = 0;
		foreach ( $array as $subCategory => $dataList ) {
		    if($subCategory !== 'Total')
			 $sum = AnalysisHelper::generateSum ( $sum, $dataList ['Total'] );
		}
		foreach ( $array as $subCategory => $dataList ) {
		    if($subCategory !== 'Total'){
    			$percentage = AnalysisHelper::generateWeightage ( $sum, $dataList ['Total'] );
    			$array [$subCategory] ['Weightage'] = $percentage;
    			$Weightage [] = $percentage;		            
    			/**
    			* Get Total row
    			*/
    			$array ['Total'] ['Weightage'] = AnalysisHelper::generateSum ( $array ['Total'] ['Weightage'], $array [$subCategory] ['Weightage'] );
		    }
		}
		/**
		 * tableDataSet
		 */
		foreach ( $array as $subCategory => $dataList ) {
			$tableDataSet [] = $dataList;
		}
		/**
		 * chartDataSet
		 */
		$chartDataSet ['data'] = $Weightage;
		/**
		 * ChartLabels
		 */
		foreach ( $subCategoryList as $subCategory ) {
			$chartLabels [] = $subCategory->name;
			$chartColors [] = $subCategory->color;
		}
		
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => $chartDataSet ?? [ ],
				'chartLabels' => $chartLabels ?? [ ],
				'chartColors' => $chartColors ?? [ ],
		);
	}
	
	private function getBSLDataSet(){
		
	}
	
	private function getBSLZRDataSet($filters, $queryData){
		$zones = Zone::all();
		$array = $Weightage = array();
		foreach ($zones as $zone){
			$regions = Region::where('zone_id_fk',$zone->zone_id_pk)->get();
			foreach ($regions as $region){
				$array[$zone->name][$region->name]['Zone'] = $zone->name;
				$array[$zone->name][$region->name]['Region'] = $region->name;
				foreach ( $this->monthList as $month ) {
					$array[$zone->name][$region->name][$month] = 0;
				}
				$array[$zone->name][$region->name]['Regional_Total'] = 0;
				$array[$zone->name][$region->name]['Zonal_Total'] = 0;
				$array[$zone->name][$region->name]['Weightage'] = 0;
			}
		}
		$zoneGrouped = $queryData->groupBy ( [
				function ($complaint) {
					return $complaint->department->region->zone->name;
				},
				function ($complaint) {
					return $complaint->department->region->name;
				},
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
				}
		] );
		$allZoneSum = 0;
		foreach ( $zoneGrouped as $zone => $regionList ) {
			foreach ( $regionList as $region => $monthList) {
				foreach ( $monthList as $month => $items) {
					$array [$zone][$region][$month] = ( int ) $items->count ();
					$array [$zone][$region]['Regional_Total'] = AnalysisHelper::generateSum ( $array [$zone][$region]['Regional_Total'], $array [$zone][$region][$month]);
				}
				$array [$zone][$region]['Zonal_Total'] = AnalysisHelper::generateSum ( $array [$zone][$region]['Zonal_Total'] , $array [$zone][$region]['Regional_Total']);
				/**
				 * For weightage
				 */
				$allZoneSum = AnalysisHelper::generateSum ( $allZoneSum , $array [$zone][$region]['Zonal_Total']);
			}
		}
		/**
		 * Weightage Calculate
		 * $startBy|$endBy for the excel mergecell reference.
		 */		
		$startBy = (int) $this->chartDataRow;
		$endBy = 0;
		foreach ( $array as $zone => $regionList ) {
			$i = 0;
			foreach ($regionList as $region => $dataList){
				$percentage = AnalysisHelper::generateWeightage ( $allZoneSum , $dataList ['Zonal_Total'] );
				$array [$zone][$region]['Weightage'] = $percentage;
				/**
				 * Area weightage is same for all the categories. so take only 1st Instance.
				 */
				if($i == 0)
					$Weightage [] = $percentage;
				$i++;
			}
			/**
			 * For the use of merge cell generation.
			 * Add for export adding merge cell references
			 * Excel -> Column A - Zone
			 * Excel -> From column C months columns get started.
			 * count($this->monthList) -> Regional_total column.
			 * count($this->monthList) + 1 -> Zonal_Total.
			 * count($this->monthList) + 2 -> Weightage.
			 * count($regionList) -1 to get accurate row count with the startBy row.
			 */
			$ExcelColumns = ['A'];
			$alphabet = range('C','Z');
			$ExcelColumns[] = $alphabet[count($this->monthList) + 1];
			$ExcelColumns[] = $alphabet[count($this->monthList) + 2];
			$endBy = (int)$startBy + (int)count($regionList) -1;
			foreach ($ExcelColumns as $key=>$column){
				$mergeCells[] = ''.$column.$startBy.':'.$column.$endBy.'';
			}
			$startBy = $endBy+1;
		}
		/**
		 * tableDataSet
		 */
		foreach ( $array as $zone => $regionList ) {
			foreach ( $regionList as $region => $dataList ) {
				$tableDataSet [] = $dataList;
			}
		}
		/**
		 * chartDataSet
		 */
		$chartDataSet ['data'] = $Weightage;
		/**
		 * ChartLabels
		 */
		foreach ( $zones as $zone) {
			$chartLabels [] = $zone->name;
		}

		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => $chartDataSet ?? [ ],
				'chartLabels' => $chartLabels ?? [ ],
				'chartColors' => $chartColors ?? [ ],
				'mergeCells' => $mergeCells ?? []
		);
	}
	
	private function getCCDataSet(){
		
	}
	
	private function getRADataSet($filters, $queryData){
		$ownerList = [
		    (object) array('name' => 'BRN', 'label' => 'Branches'),
		    (object) array('name' => 'DEPT', 'label' => 'Departments'),
		    (object) array('name' => 'MKDEPT', 'label' => 'Marketing Departments'),
		    (object) array('name' => 'CCC', 'label' => 'Customer Call Centre')
		];
		$roleList = explode('|',RoleHelper::getResolvedByRoles('RAW_NAMES'));
		foreach ($roleList as $k=>$role){
		    $roleList[$k] = strtoupper($role);
		};
		$array = array();
		foreach ($ownerList as $owner) {
		    $array[$owner->name] ['Owner'] = $owner->name;
		    foreach ( $roleList as $role ) {
		        $array [$owner->name] [$role] = 0;
		    }
		    $array[$owner->name]['Total'] = 0;
		}
		/**
		 * Get Total row, Unresolved row, All Complaint row, pending row
		 */
		$array['Total']['Owner'] = 'Total Complaints Received';
		$array['Unresolved']['Owner'] = 'Unresolved (as at '.DateHelper::getLongFormatDateString().')';
		$array['All']['Owner'] = 'Total Complaints received during the year';
		$array['Pending']['Owner'] = 'Pending resolution from complaint owner';
		
		foreach ($roleList as $role) {
		    $array ['Total'] [$role] = 0;
		    $array['Unresolved'][$role] = 0;
		    $array['All'][$role] = 0;
		    $array['Pending'][$role] = 0;
		}
		$array ['Total'] ['Total'] = 0;
		$array ['Unresolved'] ['Total'] = 0;
		$array ['All'] ['Total'] = 0;
		$array ['Pending'] ['Total'] = 0;
		
		$statusGrouped = $queryData->groupBy ( [		    
		    function ($complaint) {
		        return $complaint->status;
		    },
		    function ($complaint) {
		        if(isset($complaint->complaintUsers[0]))
		          return $complaint->complaintUsers[0]->department->type;
		    },		    
		    function ($complaint) {
		        if(isset($complaint->complaintUsers[0]))
		            return $complaint->complaintUsers[0]->system_role;
		    }
	    ] );

		foreach ($statusGrouped as $status => $ownerListItems){
		    foreach ($ownerListItems as $owner => $roleListItems ) {
		        foreach ($roleListItems as $role => $items ) {
	                if($status == 'CLO'){
	                    /**
	                     * Resolved complaints
	                     */
    	                $array [$owner] [$role] = ( int ) $items->count ();
    	                $array [$owner] ['Total'] = AnalysisHelper::generateSum ( $array [$owner] ['Total'], $array [$owner] [$role] );
    	                /**
    	                 * Get Total row
    	                 */
    	                $array ['Total'] [$role] = AnalysisHelper::generateSum ( $array ['Total'] [$role], $array [$owner] [$role] );
    	                $array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$owner] ['Total'] );
	                }else{
	                   /**
	                    * Pending/Unresolved complaints
	                    */
	                    $array ['Pending'] ['Total'] = $array ['Unresolved'] ['Total'] = AnalysisHelper::generateSum ( $array ['Unresolved'] ['Total'], ( int ) $items->count ());
	                }
	                /**
	                 * Total Complaints
	                 */
	                $array ['All'] ['Total'] = AnalysisHelper::generateSum ( $array ['All'] ['Total'], ( int ) $items->count ());
	            }
	       }
		}
		
		/**
		 * For the use of merge cell generation.
		 * Add for export adding merge cell references
		 * Excel -> Column K - Percentage
		 * 1 + count($rolelist) to get accurate column before end column count with the startBy column.
		 * 5th data total row.
		 */
		$startBy = (int) $this->startingRow;
		$endByColumn = 1 + (int)count($roleList);
		$endByColumnColor = $endByColumn + 1;
		for($i=6;$i<=8;$i++){
		    $mergeCells[] = 'A'.((int)$startBy+$i).':'.$this->getExcelColumnText($endByColumn).((int)$startBy+$i).'';
		    $colorCells[]['range'] = 'A'.((int)$startBy+$i).':'.$this->getExcelColumnText($endByColumnColor).((int)$startBy+$i).'';
		}
		/**
		 * St custom colors to cells
		 * 1 => Unresolved
		 * 2 => Total Complaint Received
		 * 3 => Pending resolution
		 * @var Ambiguous $colorCells
		 */
		$colorCells[0]['color'] = 'FFE4B5';
		$colorCells[1]['color'] = 'E3E3E3';
		$colorCells[2]['color'] = 'FAA3A0';
		/**
		 * tableDataSet
		 */
		$setText = function($item) use($ownerList){
		    foreach ($ownerList as $owner){
		        if(!is_numeric($item) && $owner->name == $item){
		            $item = $owner->label;
		        }   
		    }
		    return $item;
		};
		foreach ($array as $owner => $dataList) {
		    $tableDataSet[] = array_map($setText,$dataList);
		}
		/**
		 * chartDataSet
		 */
		$chartDataSet ['data'] = [];
		
		return array (
		    'headerRow' => $this->headerRow,
		    'tableDataSet' => $tableDataSet ?? [],
		    'chartDataSet' => $chartDataSet ?? [],
		    'chartLabels' => [],
		    'chartColors' => [],
		    'mergeCells' => $mergeCells ?? [],
		    'colorCells' =>  $colorCells ?? []
		);
		
	}
	
	private function getTFCRDataSet($filters, $queryData){
	    $modeList = ComplaintMode::where('status','ACT')->get();
	    $statusList = [
	        (object) array('name' => 'PEN'),
	        (object) array('name' => 'INP'),
	        (object) array('name' => 'ESC'),
	        (object) array('name' => 'REP'),
	        (object) array('name' => 'COM'),
	        (object) array('name' => 'CLO'),
	        (object) array('name' => 'REJ'),
	    ];
	    $descriptionList = [
	        (object) array('name' => 'Total Complaint Received', 'index' => 0),
	        (object) array('name' => 'Resolved','index' => 1),
	        (object) array('name' => 'Pending Resolution','index' => 2),
	        (object) array('name' => 'Time Frame Of Resolved Complaints','index' => 3),
	        (object) array('name' => 'Within the day','index' => 4),
	        (object) array('name' => 'Within the 03 days','index' => 5),
	        (object) array('name' => 'Within a week','index' => 6),
	        (object) array('name' => 'More than a week','index' => 7),
	        /**
	         * Get the Total row
	         */
	        (object) array('name' => 'Total', 'index' => 8)
	    ];
	    $array = $modeStatusArr = array();
	    foreach ($descriptionList as $description){
	        $array[$description->name]['Description'] = $description->name;
	        foreach ( $modeList as $mode ) {
	            $array [$description->name] [$mode->name] = 0;
	        }
	        $array[$description->name]['Total'] = 0;
	        /**
	         * Ignore percentage for first 2 column according to the dataset to be generated
	         */
	        if(!in_array($description->name, [$descriptionList[0]->name,$descriptionList[1]->name, $descriptionList[2]->name, $descriptionList[3]->name]))
	           $array[$description->name]['Percentage'] = 0;
	        else
	           $array[$description->name]['Percentage'] = '';
	    }
	    foreach ($modeList as $mode){
	        foreach ($statusList as $status){
	            $modeStatusArr[$mode->name][$status->name] = 0;
	        }
	    }
	    $modeGrouped = $queryData->groupBy ( [
	        function ($complaint) {
	            return $complaint->complaintMode->name;
	        },
	        function ($complaint) {
	              return $complaint->status;
	        }
	    ] );
	    foreach ($modeGrouped as $mode => $statusList ) {
	        foreach ($statusList as $status => $items){
	           $modeStatusArr[$mode][$status] = (int) $items->count ();
	           $array[$descriptionList[0]->name][$mode] = AnalysisHelper::generateSum ( $array[$descriptionList[0]->name][$mode]??0, $modeStatusArr[$mode][$status] ?? 0);
    	        if($status == 'CLO'){
    	            $array[$descriptionList[1]->name][$mode] = AnalysisHelper::generateSum ( $array[$descriptionList[1]->name][$mode]??0, $modeStatusArr[$mode][$status] ?? 0);
    	            foreach ($items as $item){
    	                $diffInDays = (int) DateHelper::getDateDiff($item->open_date, $item->close_date);
    	                if($diffInDays > 7)
    	                    $array [$descriptionList[7]->name][$mode] = ++$array [$descriptionList[7]->name][$mode];
    	                else if($diffInDays <= 7 && $diffInDays > 3 )
    	                    $array [$descriptionList[6]->name][$mode] = ++$array [$descriptionList[6]->name][$mode];
    	                else if($diffInDays <= 3 && $diffInDays > 1 )
    	                    $array [$descriptionList[5]->name][$mode] = ++$array [$descriptionList[5]->name][$mode];
    	                else if($diffInDays <= 1 )
    	                    $array [$descriptionList[4]->name][$mode] = ++$array [$descriptionList[4]->name][$mode];
    	            }
    	        }else{
    	            /**
    	             * All the other status are pending for resolution
    	             */
    	            $array[$descriptionList[2]->name][$mode] = AnalysisHelper::generateSum ( $array[$descriptionList[2]->name][$mode]??0, $modeStatusArr[$mode][$status] ?? 0);
    	        }
    	     
	       }
	    }
	    foreach ($descriptionList as $description){
	        foreach ($modeList as $mode){
	            $array[$description->name]['Total'] = AnalysisHelper::generateSum ( $array[$description->name]['Total']??0, $array[$description->name][$mode->name] ?? 0);
	        }
	    }
	    /**
	     * Percentage Calculate
	     */
	    $sum = 0;
	    foreach ($array as $description => $dataList) {
	        if(!in_array($description, [$descriptionList[0]->name,$descriptionList[1]->name, $descriptionList[2]->name, $descriptionList[3]->name, $descriptionList[8]->name]))
	            $sum = AnalysisHelper::generateSum($sum, $dataList['Total']);
	    }
	    foreach ($array as $description => $dataList) {
	        if(!in_array($description, [$descriptionList[0]->name,$descriptionList[1]->name, $descriptionList[2]->name, $descriptionList[3]->name, $descriptionList[8]->name])){
                $percentage = AnalysisHelper::generateWeightage($sum, $dataList['Total']);
                $array[$description]['Percentage'] = $percentage;
                $Percentage[] = $percentage;
                /**
                 * Get Total row
                 */
                $array['Total']['Percentage'] = AnalysisHelper::generateSum($array['Total']['Percentage'], $array[$description]['Percentage']);
	        }
	    }
	    
	    /**
	     * For the use of merge cell generation.
	     * Add for export adding merge cell references
	     * Excel -> Column K - Percentage
	     * 1 + count($modeList) + 2 to get accurate end column count with the startBy column.
	     * 4th data total row.
	     */
	    $startBy = (int) $this->startingRow;
	    $endByColumn = 1 + (int)count($modeList) + 2;
	    $mergeCells[] = ''.$this->getExcelColumnText($endByColumn).$startBy.':'.$this->getExcelColumnText($endByColumn).((int)$startBy+3).'';
	    $mergeCells[] = 'A'.((int)$startBy+4).':'.$this->getExcelColumnText($endByColumn).((int)$startBy+4).'';
	    
	    /**
	     * St custom colors to cells
	     * 1 => Time Frame Of Resolved Complaints
	     * 2 => Total Complaint Received
	     * @var Ambiguous $colorCells
	     */
	    $colorCells[] = array(
	        'range' => $mergeCells[1],
	        'color' => 'FFE4B5'
	    );
	    $colorCells[] = array(
	        'range' => 'B'.((int)$startBy+1).':'.$this->getExcelColumnText($endByColumn-1).((int)$startBy+1).'',
	        'color' => 'A9A9A9'
	    );
	    
	    /**
	     * tableDatatset
	     */
	    foreach ($array as $description => $dataList) {
	        $tableDataSet[] = $dataList;
	    }
	    
	    /**
	     * chartDataSet - not using in this dataset
	     */
	    $chartDataSet['data'] = [];
	    
	    return array(
	        'headerRow' => $this->headerRow,
	        'tableDataSet' => $tableDataSet ?? [],
	        'chartDataSet' => $chartDataSet ?? [],
	        'chartLabels' => [],
	        'chartColors' => [],
	        'mergeCells' => $mergeCells ?? [],
	        'colorCells' =>  $colorCells ?? []
	    );
	}
	
	private function getMG($filters, $queryData){
	    $modeList = ComplaintMode::where('status','ACT')->get();
	    $array = $Weightage = array ();
	    foreach ( $modeList as $mode ) {
	        $array [$mode->name] ['Mode'] = $mode->name;
	        $array [$mode->name] ['Total'] = 0;
	        $array [$mode->name] ['Weightage'] = 0;
	    }
	    /**
	     * Get Total row
	     */
	    $array['Total']['Mode'] = 'Total';
	    $array ['Total'] ['Total'] = 0;
	    $array ['Total'] ['Weightage'] = 0;
	    
	    $modeGrouped = $queryData->groupBy ( [
	        function ($complaint) {
	            return $complaint->complaintMode->name;
	        }
	        ] );
	    
	    foreach ( $modeGrouped as $mode => $items ) {
	        $array [$mode] ['Total'] = $items->count ();
	        /**
	         * Get Total row
	         */
	        $array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$mode] ['Total'] );
	    }
	    /**
	     * Weightage Calculate
	     */
	    $sum = 0;
	    foreach ( $array as $mode => $dataList ) {
	        if($mode !== 'Total')
	            $sum = AnalysisHelper::generateSum ( $sum, $dataList ['Total'] );
	    }
	    foreach ( $array as $mode => $dataList ) {
	        if($mode !== 'Total'){
	            $percentage = AnalysisHelper::generateWeightage ( $sum, $dataList ['Total'] );
	            $array [$mode] ['Weightage'] = $percentage;
	            $Weightage [] = $percentage;
	            /**
	             * Get Total row
	             */
	            $array ['Total'] ['Weightage'] = AnalysisHelper::generateSum ( $array ['Total'] ['Weightage'], $array [$mode] ['Weightage'] );
	            
	        }
	    }
	    /**
	     * tableDataSet
	     */
	    foreach ( $array as $mode => $dataList ) {
	        $tableDataSet [] = $dataList;
	    }
	    /**
	     * chartDataSet
	     */
	    $chartDataSet ['data'] = $Weightage;
	    /**
	     * ChartLabels
	     */
	    foreach ( $modeList as $mode ) {
	        $chartLabels [] = $mode->name;
	        $chartColors [] = $mode->color;
	        
	    }
	    
	    return array (
	        'headerRow' => $this->headerRow,
	        'tableDataSet' => $tableDataSet ?? [ ],
	        'chartDataSet' => $chartDataSet ?? [ ],
	        'chartLabels' => $chartLabels ?? [ ],
	        'chartColors' => $chartColors ?? [ ]
	    );
	}
	
	private function getCG($filters, $queryData){
	    $categoryList = Category::where('category_level', 1)->where('status','ACT')->get();
	    $array = $Weightage = array ();
	    foreach ($categoryList as $category){
	        $array [$category->name] ['Category'] = $category->name;
	        $array [$category->name] ['Total'] = 0;
	        $array [$category->name] ['Weightage'] = 0;
	    }
	    /**
	     * Get Total row
	     */
	    $array['Total']['Category'] = 'Total';
	    $array ['Total'] ['Total'] = 0;
	    $array ['Total'] ['Weightage'] = 0;
	    
	    $categoryGrouped = $queryData->groupBy ( [
	        function ($complaint) {
	            /**
	             * Removed with CR3
	             *
	             // return $complaint->category->name;
	             /**
	             * Added in CR3
	             * Get the mapping related to category level 1
	             * @var unknown $categoryGrouped
	             */
	            if ($this->categoryLevels == 3)
	                return $complaint->category->parentCategory->parentCategory->name;
	            if ($this->categoryLevels == 4)
	                return $complaint->category->parentCategory->parentCategory->parentCategory->name;
	            if ($this->categoryLevels == 5)
	                return $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->name;
	            if ($this->categoryLevels == 6)
	                return $complaint->category->parentCategory->parentCategory->parentCategory->parentCategory->parentCategory->name;
	        }
	    ]);
	    foreach ( $categoryGrouped as $category => $items ) {
	        $array [$category] ['Total'] = ( int ) $items->count ();	            /**
	        * Get Total row
	        */
	        $array ['Total'] ['Total'] = AnalysisHelper::generateSum ( $array ['Total'] ['Total'], $array [$category] ['Total'] );
	    }
	    /**
	     * Weightage Calculate
	     */
	    $sum = 0;
	    foreach ( $array as $category => $dataList ) {
	        if($category !== 'Total')
	            $sum = AnalysisHelper::generateSum ( $sum, $dataList ['Total'] );
	    }
	    foreach ( $array as $category => $dataList ) {
	        if($category !== 'Total'){
	            $percentage = AnalysisHelper::generateWeightage ( $sum, $dataList ['Total'] );
	            $array [$category] ['Weightage'] = $percentage;
	            $Weightage [] = $percentage;
	            /**
	             * Get Total row
	             */
	            $array ['Total'] ['Weightage'] = AnalysisHelper::generateSum ( $array ['Total'] ['Weightage'], $array [$category] ['Weightage'] );
	        }
	    }
	    /**
	     * tableDataSet
	     */
	    foreach ( $array as $category => $dataList ) {
	        $tableDataSet [] = $dataList;
	    }
	    /**
	     * chartDataSet
	     */
	    $chartDataSet ['data'] = $Weightage;
	    /**
	     * ChartLabels
	     */
	    foreach ( $categoryList as $category ) {
	        $chartLabels [] = $category->name;
	        $chartColors [] = $category->color;
	        
	    }
	    
	    return array (
	        'headerRow' => $this->headerRow,
	        'tableDataSet' => $tableDataSet ?? [ ],
	        'chartDataSet' => $chartDataSet ?? [ ],
	        'chartLabels' => $chartLabels ?? [ ],
	        'chartColors' => $chartColors ?? [ ]
	    );
	}
	/**
	 * Compliments
	 */
	private function getTEDataSet($filters, $queryData){
		$array ['Name'] = 'Email';
		foreach ( $this->monthList as $month ) {
			$array [$month] = 0;
		}
		$array ['Total'] = 0;
		$monthGrouped = $queryData->groupBy ( [
				function ($complaint) {
					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
				}
		] );
		foreach ($monthGrouped as $month => $items ) {
			$array [$month] = ( int ) $items->count ();
			$array ['Total'] = AnalysisHelper::generateSum ( $array ['Total'], $array [$month] );
		}
		/**
		 * tableDataSet
		 */
		$tableDataSet[0] = $array;
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => [ ],
				'chartLabels' => [ ],
				'chartColors' => [ ]
		);
	}
	
	private function getTCRDataSet($filters, $queryData){
		$array ['Name'] = 'CSAT';
		$array ['Total_Ratings'] = $queryData->count();
		$array ['Excellent_Ratings'] = 0;
		$array ['Weightage'] = 0;
// 		$monthGrouped = $queryData->groupBy ( [
// 				function ($complaint) {
// 					return DateHelper::formatToDate ( $complaint->open_date )->format ( 'F' );
// 				}
// 				] );
// 		foreach ($monthGrouped as $month => $items ) {
// 			$array [$month] = ( int ) $items->count ();
// 			$array ['Total'] = AnalysisHelper::generateSum ( $array ['Total'], $array [$month] );
// 		}
		/**
		 * tableDataSet
		 */
		$tableDataSet[0] = $array;
		
		/**
		 * Weightage Calculate
		 */
		foreach ( $array as $mode => $dataList ) {
			$percentage = AnalysisHelper::generateWeightage ( $dataList['Total_Ratings'], $dataList ['Excellent_Ratings'] );
			$array ['Weightage'] = $percentage;
			$Weightage [] = $percentage;
		}
		return array (
				'headerRow' => $this->headerRow,
				'tableDataSet' => $tableDataSet ?? [ ],
				'chartDataSet' => [ ],
				'chartLabels' => [ ],
				'chartColors' => [ ]
		);
	}
	
	private function getExcelColumnText($column){
	    $alphas = range('A', 'Z');
	    return $alphas[$column-1];
	}
	
}