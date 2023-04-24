<?php

namespace App\Models\Repositories\BranchDepartments;

use DateHelper;
use App\Models\Entities\BranchDepartment;
use App\Models\Entities\BranchDepartmentUser;
use Log;
use DB;
use UserHelper;
use EnumTextHelper;
use DataTables;
use RoleHelper;

class BranchDepartmentsRepository {
	public function __construct() {
	}
	private function generateRegionMapSearchQuery($data) {
	    $query = BranchDepartment::select ( '*' )->whereDoesntHave('region');
	    // DB::raw ( "complainant_id_pk AS id" ),
	    // DB::raw ( "CONCAT(first_name,' ',last_name) AS text" ) )
	    // where ( 'name', 'like', '%' . $data ['name'] . '%' )->
	    if(isset($data ['name']))
	        $query->whereRaw ( 'LOWER(name) like ?', array (
	            'name' => '%' . strtolower ( $data ['name'] ) . '%'
	        ))->orWhere ( 'sol_id', 'like', '%' . $data ['name'] . '%' );
	        
	        return $query->distinct ();
	}
	private function generateSearchQuery($data) {
		$query = BranchDepartment::select ( '*' );
		// DB::raw ( "complainant_id_pk AS id" ),
		// DB::raw ( "CONCAT(first_name,' ',last_name) AS text" ) )
		// where ( 'name', 'like', '%' . $data ['name'] . '%' )->
		if(isset($data ['name']))
		    $query->whereRaw ( 'LOWER(name) like ?', array (
				'name' => '%' . strtolower ( $data ['name'] ) . '%' 
		    ))->orWhere ( 'sol_id', 'like', '%' . $data ['name'] . '%' );
		
		return $query->distinct ();
	}
	private function generateUsersSearchQuery($data){
	    $roles = RoleHelper::getComplaintRaiseRoles('NAMES');
		$query = BranchDepartmentUser::select('*');
		$query->whereHas('user', function($q) use($data,$roles) {
		    if(isset($data ['name']))
			$q->whereRaw('LOWER(first_name) like ?', array (
					'first_name' => '%' . strtolower ( $data ['name'] ) . '%'
			))->orWhereRaw('LOWER(last_name) like ?', array (
					'last_name' => '%' . strtolower ( $data ['name'] ) . '%'
			))->orWhere ('email', 'like', '%' . $data ['name'] . '%' );
			
			$q->WhereHas ( 'roles', function ($qr) use($roles) {
			    $qr->whereIn ( 'name', $roles );
			} )->distinct();
		})->with(['user']);
		/**
		 * Omit logged in user for search select lists
		 */
		$query->where('user_id_fk', '!=', UserHelper::getLoggedUserId());
		$query->where ( 'branch_department_id_fk', $data ['resource_id'] )->distinct ();
		return $query->where('status','ACT')->distinct();
	}
	private function generateQuery($filters) {
		$query = BranchDepartment::select ( '*' );
		$query->with ( [
		    'region',
		    'manager'
		] );
		if (isset ( $filters ['branch_department_id_pk'] ))
			$query->where ( 'branch_department_id_pk', $filters ['branch_department_id_pk'] );
		if(isset($filters['region_id_fk']))
			$query->where ( 'region_id_fk', $filters ['region_id_fk'] );
		if (isset ( $filters ['random'] ))
			$query->inRandomOrder ();
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		return $query;
	}
	private function buildSearchResponse($branch) {
		$branch->id = $branch->branch_department_id_pk;
		$branch->type_text = EnumTextHelper::getEnumText($branch->type);
		$branch->text = $branch->name.' - <code>'.sprintf ( "%03d",$branch->sol_id).' - '.$branch->type_text.'</code>';
		$branch->display_branch_department = true;
		return $branch;
	}
	private function buildUsersSearchResponse($user) {
		$user->id = $user->user_id_pk;
		$user->text = $user->first_name.' '.$user->last_name.' - <code>'.$user->email.'</code>';
		$user->display_branch_department_user = true;
		return $user;
	}
	private function buildResponse($branch) {
		return $branch;
	}
	/**
	 *
	 * @param unknown $filters
	 * @return unknown
	 */
	public function getBranchDepartments($filters) {
	    $queryString = $this->generateQuery ( $filters );
	    $datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($branch) {
	        return $branch->branch_department_id_pk;
	    } )->addColumn ( 'manager_name', function ($branch) {
	        return (isset($branch->manager) ? $branch->manager->first_name.' '.$branch->manager->last_name : '');
	    } )->addColumn ( 'manager_email', function ($branch) {
	        return (isset($branch->manager) ? $branch->manager->email : '');
	    } )->addColumn ( 'type', function ($branch) {
	        return '<code>'.EnumTextHelper::getEnumText($branch->type).'</code>';
	    } )->addColumn ( 'zone_name', function ($branch) {
	        return (isset($branch->region) ? $branch->region->name : '');
	    } )->editColumn ( 'created_at', function ($branch) {
	        return DateHelper::formatToDateString ( $branch->created_at );
	    } )->removeColumn ( 'manager_id_fk','manager','region_id_fk','region');
	    
	    return $datatable->toJson ();
	}
	public function getForSearch(array $filters) {
		$queryString = $this->generateSearchQuery ( $filters );
		$branchDepartments = $queryString->get ();
		foreach ( $branchDepartments as $bkey => $branchDepartment ) {
			$branchDepartment = $this->buildSearchResponse ( $branchDepartment );
		}
		return $branchDepartments;
	}	
	public function getForRegionMapSearch(array $filters) {
	    $queryString = $this->generateRegionMapSearchQuery ( $filters );
	    $branchDepartments = $queryString->get ();
	    foreach ( $branchDepartments as $bkey => $branchDepartment ) {
	        $branchDepartment = $this->buildSearchResponse ( $branchDepartment );
	    }
	    return $branchDepartments;
	}
	public function getUsersForSearch(array $filters) {
		$queryString = $this->generateUsersSearchQuery ( $filters );
		$branchDepartmentsUsers = $queryString->get ();
		foreach ( $branchDepartmentsUsers as $bkey => $user ) {
			$users[] = $this->buildUsersSearchResponse( $user->user );
		}
		return $users??[];
	}
	public function getBranchDepartment(array $filters) {
		$queryString = $this->generateQuery ( $filters );
		$branchDepartment = $queryString->first ();
		return $this->buildResponse ( $branchDepartment );
	}
	public function getBranchSolId($id){
	    return BranchDepartment::where('branch_department_id_pk',$id)->first()->sol_id;
	}
	/**
	 *
	 * @param unknown $params
	 * @return unknown
	 */
	public function saveOrEditBranch($branches){
        try {
            foreach ($branches as $brn){
                $branch = BranchDepartment::firstOrNew(array(
                    'sol_id' => (int) $brn['SOL_ID']
                ));
                $branch->fill([
                    'sol_id' => (int) $brn['SOL_ID'],
                    'name' => $brn['NAME']
                ])->save();
            }
        } catch ( \Exception $e ) {
            dd ( $e );
        }
	}
	
}