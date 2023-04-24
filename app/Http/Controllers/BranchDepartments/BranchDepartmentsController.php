<?php

namespace App\Http\Controllers\BranchDepartments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\BranchDepartments\BranchDepartmentsRepository;
use App\Classes\UPM\Sync;
use App\Jobs\ScheduleJob;

class BranchDepartmentsController extends Controller
{
	protected $branchDepartmentRepo;
	public function __construct(){
		$this->branchDepartmentRepo = new BranchDepartmentsRepository();
	}
	public function getBranchesForSearch(Request $request){
		return json_encode ( array (
				"result" => "success",
				'items' => $this->branchDepartmentRepo->getForSearch($request->all())
		) );
	}
	public function getBranchesForRegionMapSearch(Request $request){
	    return json_encode ( array (
	        "result" => "success",
	        'items' => $this->branchDepartmentRepo->getForRegionMapSearch($request->all())
	    ) );
	}
	public function getBranchDepartment($encodedURI = null, Request $request){
		try{
			$id = explode ( '=', $encodedURI ) [1];
			return $this->branchDepartmentRepo->getBranchDepartment( [
					'branch_department_id_pk' => $id
			] );
		}catch(\Exception $e){
			dd($e);
		}
	}
	public function getBranchDepartments(Request $request){
	    try {
	       return $this->branchDepartmentRepo->getBranchDepartments ( $request->all() );
	    } catch ( \Exception $e ) {
	        abort ( 404 );
	    }
	}
	public function getBranchDepartmentUsers(Request $request){
		return json_encode ( array (
				"result" => "success",
				'items' => $this->branchDepartmentRepo->getUsersForSearch($request->all())
		) );
	}
	public function getAllBranchDepartmentUsers(Request $request){
		return $this->branchDepartmentRepo->getUsersForSearch($request->all());
	}
	public function userSync($encodedURI = null,Request $request){
	    try{
	        $id = explode ( '=', $encodedURI ) [1];
	        $solId = $this->branchDepartmentRepo->getBranchSolId($id);
	        ScheduleJob::dispatch([
	            'type' => 'UPMUSERSYNC',
	            'solId' => $solId
	        ]);
	    }catch(\Exception $e){
	        
	    }
	}
}
