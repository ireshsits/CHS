<?php

namespace App\Classes\Analyses;
use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintMode;
use App\Models\Entities\Setting;

class FetchDB {
    protected $categoryLevels;
    protected $categoryString;
    public function __construct() {
        $this->categoryLevels = Setting::getCategorySettings()->CATEGORY_LEVELS;
        $this->categoryString = 'category';
	}
	private function generateQueryByCode($filters, $query) {
	    
		switch ($filters ['code']) {
			case 'MWA' : //done
				return $query;
				break;
			case 'CRTTM' :
				/**
				 * Top management consider as corporate managemenet.
				 *
				 * @var unknown $ownerRoles
				 */
				return $query->whereHas ( 'complaintMode', function ($qmode) use ($filters) {
					$qmode->where('name', 'Corporate Management');
				} );
				break;
			case 'CRTCCHL' :
				/**
				 * Need to identify Card Center code statically
				 */
				return $query->whereHas ( 'complaintUsers.department', function ($q) use ($filters) {
					return $qrole->where ( 'sol_id', '222222222' );
				} );
				break;
			case 'DACRTCCHL' :
				/**
				 * Need to identify Card Center code statically
				 */
				return $query->whereHas ( 'complaintUsers.department', function ($q) use ($filters) {
					return $q->where ( 'sol_id', '222222222' );
				} );
				break;
			case 'AWA' : //done
				/**
				 * Changed in CR3
				 */
// 				return $query->has('subCategory.area');
                return $query->has('area');
				break;
			case 'DAWA' :
			    /**
			     * Change in CR3
			     */
// 				return $query->whereHas ('subCategory', function($q) use ($filters){
// 					return $q->where('area_id_fk',$filters['area_id_fk']);
// 				});
			    return $query->where('area_id_fk',$filters['area_id_fk']);
				break;
			case 'AWODA' :				
				/**
				 * Fetch only complaint with subcategories which has area.
				 */
				return $query->has('subCategory.area');
				break;
			case 'CWA' : //done
				return $query;
				break;
			case 'DCWA' : //done
			    /**
			     * Changed in CR3
			     */
			    for($i = 1;$i<=($this->categoryLevels - 1);$i++){
			        $this->categoryString = $this->categoryString.'.parentCategory';
			    }
// 				return $query->where('category_id_fk',$filters['category_id_fk']);
			    if(isset($filters['category_id_fk'])){
			        return $query->whereHas($this->categoryString, function($q) use ($filters){
    			            $q->where('category_id_pk',$filters['category_id_fk']);
    			         });
			    }else{
			        return $query;
			    }
				break;
			case 'DSCWA' : //done
			    for($i = 1;$i<=($this->categoryLevels - 2);$i++){
			        $this->categoryString = $this->categoryString.'.parentCategory';
			    }
			    if(isset($filters['category_id_fk'])){
			        return $query->whereHas($this->categoryString, function($q) use ($filters){
			            $q->where('category_id_pk',$filters['category_id_fk']);
			        });
			    }else{
			        return $query;
			    }
			    break;
			case 'IDSCWA' : //not implemented yet
// 			    if(isset($filters['category_id_fk'])){
// 			        return $query->whereHas($this->categoryString, function($q) use ($filters){
// 			            $q->where('category_id_pk',$filters['category_id_fk']);
// 			        });
// 			    }else{
// 			        return $query;
// 			    }
			    break;
			case 'BSL' :
				return $query;
				break;
			case 'BSLZR' :
				return $query;
				break;
			case 'CC' :
				return $query;
				break;
			case 'RA' :
				return $query;
				break;
			case 'TFCR' : 
			    return $query;
			    break;
			case 'MG' :
			    return $query;
			    break;
			case 'CG' :
			    return $query;
			    break;
			case 'TE' :
				/**
				 * Only compliments by Email considered.
				 */
				return $query->whereHas ( 'complaintMode', function ($qmode) use ($filters) {
					$qmode->where('name', 'Email');
				} )->where('type','CMPLI');
				break;
			case 'TCR' :
				/**
				 * Only CSAT considered.
				 */
				return $query->whereHas ( 'complaintMode', function ($qmode) use ($filters) {
					$qmode->where('name', 'CSAT');
				} )->where('type','CMPLI');
				break;
		}
	}
	public function generateQuery($filters) {
		$query = Complaint::select ( '*' );
		
		$query = $this->generateQueryByCode ( $filters, $query );
		
		/**
		 * Category level dynamic.
		 * Add the category eager loading according to the number of category levels in the system
		 * @var string $categoryString
		 */
// 		$categoryString = 'category';
// 		for($i = 1;$i<=($this->categoryLevels - 1);$i++){
// 		    $categoryString = $categoryString.'.parentCategory';
// 		}
		
		$query->with ( [ 
		        $this->categoryString => function ($q) use ($filters) {
					$q->where('status','ACT');
				},
				/**
				 * Removed in CR3
				 */
// 				'subCategory.area' => function ($q) use ($filters) {
// 					$q->where('status','ACT');
// 				},
                /**
                 * Added in CR3
                 */
				'area' => function ($q) use ($filters) {
					$q->where('status','ACT');
				},
				'complaintMode' => function ($q) use ($filters) {
					$q->where('status','ACT');
				},
				/**
				 * Refers the branch/department saved in the ComplaintUser entity. Not the users actual department. 
				 * Because user may be transfered to a separate branch now. Consider the department at the time of complaint saving.
				 */
				'complaintUsers' => function($q) use ($filters) {
				    $q->with([
				        'department.region.zone' => function ($qd) use ($filters) {
				        },
				        'user' => function($qu) use ($filters) {
				        }
				    ])->where('primary_owner',true);
				}
		] );
		
		if (isset ( $filters ['complaint_id_pk'] ))
			$query->where ( 'complaint_id_pk', $filters ['complaint_id_pk'] );
		/**
		 * Analysis Filters
		 */
		if (isset ( $filters ['year'] ))
			$query->whereRaw ( 'EXTRACT(YEAR FROM OPEN_DATE) = ?', [ 
					$filters ['year'] 
			] );
		if (isset ( $filters ['status'] ) && $filters ['status'] !== 'ALL')
			$query->whereIn ( 'status', explode ( ',', $filters ['status'] ) );
		if (isset ( $filters ['type'] ) && $filters ['type'] !== 'ALL')
		    $query->where( 'type', $filters ['type']);
			    
		if (isset ( $filters ['random'] ))
			$query->inRandomOrder ();
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		return $query;
	}
}