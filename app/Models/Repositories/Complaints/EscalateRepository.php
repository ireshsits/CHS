<?php

namespace App\Models\Repositories\Complaints;

use RoleHelper;
use UserHelper;
use App\Models\Entities\BranchDepartmentUser;
use App\Models\Entities\ComplaintEscalation;
use App\Models\Entities\ComplaintUser;
use DB;

class EscalateRepository {
	public function __construct() {
	}
// 	private function generateSearchQuery($params) {
// 	    $roles = RoleHelper::getEscalateRoleNames ();
// 		$query = BranchDepartmentUser::WhereHas ( 'user', function ($q) use($roles) {
// 			$q->WhereHas ( 'roles', function ($qr) use($roles) {
// 				$qr->whereIn ( 'name', $roles );
// 			} );
// 		} )->with ( [ 
// 				'user.roles',
// 				'department' 
// 		] );
// 		if (isset ( $params ['branch_department_id_pk'] ))
// 			$query->where ( 'branch_department_id_fk', $params ['branch_department_id_pk'] );
// 		$query->where ( 'user_id_fk', '!=',UserHelper::getLoggedUserId () );
// 		return $query;
// 	}
	private function generateQuery($params) {
		$query = Complainant::selectRaw ( '*' );
		if (isset ( $params ['complainant_id_pk'] ))
			$query->where ( 'complainant_id_pk', $params ['complainant_id_pk'] );
		if (isset ( $params ['nic'] ))
			$query->where ( 'nic', $params ['nic'] );
		return $query;
	}
	private function buildSearchResponse($user) {
		return ( object ) [ 
				'id' => $user->user->user_id_pk,
				'text' => $user->user->first_name . ' ' . $user->user->last_name,
				'branch_name' => $user->department->name,
				'role' => $user->user->roles [0]->name,
				'display_escalate_to' => true 
				
		];
	}
// 	public function getForSearch(array $filters) {
// 		$queryString = $this->generateSearchQuery ( $filters );
// 		$users = $queryString->distinct ()->get ();
// 		foreach ( $users as $key => $user ) {
// 			$users [$key] = $this->buildSearchResponse ( $user );
// 		}
// 		return $users;
// 	}
	public function statusUpdate($data) {
// 		$complaint = Complaint::where ( 'complaint_id_pk', $data ['id'] )->first ();
// 		if ($data ['field'] == 'status')
// 			$complaint->status = $data ['status'];
// 			return $complaint->save ( $data );
	/**
	 * $data['id'] contain Complaint Id,
	 * $data['status'] contain status of the escalation.
	 * So it update the currently INP escaltion.
	 */
        return DB::transaction(function() use($data) {
            if($data ['status'] == 'REJ' && isset($data['reason'])){
                $complaintUser = ComplaintUser::where ( 'complaint_id_fk', $data ['id'] )->where('user_id_fk', UserHelper::getLoggedUserId ())->first();
                $complaintUser->status = $data ['status'];
                $complaintUser->reject_reason = $data ['reason'];
                /**
                 * Only ESCALATE can reject from here. not get deleted
                 * add mode as ESCAL
                 */
                $data['role'] = 'ESCAL';
                $complaintUser->save ( $data );
                ComplaintEscalation::updateEscalatedStatus($data ['id'],array('status' => $data ['status']));
                return true;
            }
        });
	}
}