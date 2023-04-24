<?php

namespace App\Classes\DashboardStats;
use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintMode;
use RoleHelper;
use UserHelper;

class FetchDB {
	public function __construct() {
	}
	public function generateQuery($filters) {
		$query = Complaint::select ( '*' );
		
		/**
		 * CR2 changes..
		 * if logged in user has any role in the complaint. get those for dashboard stats
		 */
// 		$cccRoles = RoleHelper::getCCCRoles ();
		$adminViewRoles = RoleHelper::getAdminViewRoles();
		$zonalAdminRoles = RoleHelper::getZonalAdminRoles ();
		$regionalAdminRoles = RoleHelper::getRegionalAdminRoles ();
		$branchAdminRoles = RoleHelper::getBranchAdminRoles ();
		
		if (UserHelper::getLoggedUserHasAnyRole ( $adminViewRoles)){
		    $filters['raise_by'] = $filters['raise_by']??'all';
		    if(isset($filters['raise_by'])){
		        if($filters['raise_by'] == 'me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		              $q->where('user_role', 'RECPT')->where('user_id_fk', UserHelper::getLoggedUserId ());
		            });
		        }else if($filters['raise_by'] == 'others_to_me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		              $q->whereNotIn('user_role', ['RECPT'])->whereNotIn('status',['REJ'])->where('user_id_fk', UserHelper::getLoggedUserId ());
		            })->whereNotIn('status',['PEN']);
		        }else{}
		    }
		}else if(UserHelper::getLoggedUserHasAnyRole ( $zonalAdminRoles)){
		    $filters['raise_by'] = $filters['raise_by']??'all';
		    if(isset($filters['raise_by'])){
		        if($filters['raise_by'] == 'me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->where('user_role', 'RECPT')->where('user_id_fk', UserHelper::getLoggedUserId ());
		            });
		        }else if($filters['raise_by'] == 'others_to_me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->whereNotIn('user_role', ['RECPT'])->whereNotIn('status',['REJ'])->where('user_id_fk', UserHelper::getLoggedUserId ());
		            })->whereNotIn('status',['PEN']);
		        }else{
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->whereHas('department.region.zone', function($qd) {
		                  $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
		                });
		            }); //->whereNotIn('status',['REJ']);
		        }
		    }
		}else if(UserHelper::getLoggedUserHasAnyRole ( $regionalAdminRoles)){
		    $filters['raise_by'] = $filters['raise_by']??'all';
		    if(isset($filters['raise_by'])){
		        if($filters['raise_by'] == 'me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->where('user_role', 'RECPT')->where('user_id_fk', UserHelper::getLoggedUserId ());
		            });
		        }else if($filters['raise_by'] == 'others_to_me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->whereNotIn('user_role', ['RECPT'])->whereNotIn('status',['REJ'])->where('user_id_fk', UserHelper::getLoggedUserId ());
		            })->whereNotIn('status',['PEN']);
		        }else{
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->whereHas('department.region', function($qd) {
		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
		                });
		            }); //->whereNotIn('status',['REJ']);
		        }
		    }
		}else if(UserHelper::getLoggedUserHasAnyRole ( $branchAdminRoles)){
		    $filters['raise_by'] = $filters['raise_by']??'all';
		    if(isset($filters['raise_by'])){
		        if($filters['raise_by'] == 'me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->where('user_role', 'RECPT')->where('user_id_fk', UserHelper::getLoggedUserId ());
		            });
		        }else if($filters['raise_by'] == 'others_to_me'){
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->whereNotIn('user_role', ['RECPT'])->whereNotIn('status',['REJ'])->where('user_id_fk', UserHelper::getLoggedUserId ());
		            })->whereNotIn('status',['PEN']);
		        }else{
		            $query->whereHas('complaintUsers', function ($q) use ($filters){
		                $q->whereHas('department', function($qd) {
		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
		                });
		            }); //->whereNotIn('status',['REJ']);
		        }
		    }
		}else{
		    $filters['raise_by'] = $filters['raise_by']??'others_to_me';
		    if(isset($filters['raise_by'])){
		            if($filters['raise_by'] == 'me')
		                $query->whereHas('complaintUsers', function ($q) use ($filters){
		                    $q->where('user_role', 'RECPT');
		                    $q->where('user_id_fk', UserHelper::getLoggedUserId ());
		                });
		            else if($filters['raise_by'] == 'others_to_me')
		                $query->whereHas('complaintUsers', function ($q) use ($filters){
		                  $q->whereNotIn('user_role', ['RECPT'])->whereNotIn('status',['REJ']);
		                  $q->where('user_id_fk', UserHelper::getLoggedUserId ());
		                })->whereNotIn('status',['PEN']);
		            else{}
		    }
		}
		
		$query->with ( [ 
				'category' => function ($q) use ($filters) {
					$q->where('status','ACT');
				},
				'area' => function ($q) use ($filters) {
				},
// 				'subCategory.area' => function ($q) use ($filters) {
// 				},
				'complaintMode' => function ($q) use ($filters) {
					$q->where('status','ACT');
				},
				'complaintUsers.user.departmentUser.department.region.zone' => function ($q) use ($filters) {
				}
		] );
		
		if (isset ( $filters ['complaint_id_pk'] ))
			$query->where ( 'complaint_id_pk', $filters ['complaint_id_pk'] );
		/**
		 * Analysis Filters
		 */
		if (isset ( $filters['range']))
			$query->whereBetween( 'open_date', [ $filters['range']['start'],$filters['range']['end'] ]);
		if (isset ( $filters ['status'] ) && $filters ['status'] !== 'ALL')
			$query->whereIn ( 'status', explode ( ',', $filters ['status'] ) );
		if (isset ( $filters ['type'] ) && $filters ['type'] !== 'ALL')
			$query->where ( 'type', $filters ['type']);
		if (isset ( $filters ['random'] ))
			$query->inRandomOrder ();
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		return $query;
	}
}