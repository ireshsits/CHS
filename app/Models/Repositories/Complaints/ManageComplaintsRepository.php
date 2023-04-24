<?php

namespace App\Models\Repositories\Complaints;

use DateHelper;
use RoleHelper;
use UserHelper;
use EnumTextHelper;
use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintUser;
use App\Models\Entities\ComplaintSolution;
use App\Models\Entities\SolutionAmendment;
use App\Models\Entities\ComplaintReopen;
use App\Models\Entities\ComplaintClose;
use App\Models\Entities\User;
use App\Models\Entities\Setting;
use App\Traits\FileService;
use DataTables;
use DB;
use App\Traits\CustomConfigurationService;


class ManageComplaintsRepository {
	use FileService;
	use CustomConfigurationService;
	public function __construct() {
	}
	private function generateQuery($filters) {
		$query = Complaint::select ( 'complaints.*' );
		
		if (isset ( $filters ['complaint_id_pk'] )){
			$query->where ( 'complaint_id_pk', $filters ['complaint_id_pk'] );
		}else{
			/**
			 * System get change from here
			 * Need to add a separate table to save user roles
			 * complaint_users contain the users involved in the complaint with role.
			 * complaint_users -> user -> deprtmentuser -> department contains the details of the department
			 */

            $adminViewRoles = RoleHelper::getAdminViewRoles();
			$zonalAdminRoles = RoleHelper::getZonalAdminRoles ();
			$regionalAdminRoles = RoleHelper::getRegionalAdminRoles ();
			$branchAdminRoles = RoleHelper::getBranchAdminRoles ();
			
			if (UserHelper::getLoggedUserHasAnyRole ( $adminViewRoles)){
				/**
				 * Handle ccc admin views
				 */
			    if(isset($filters['table'])){
			        if($filters['table'] == 'PEN')
// 			            $query->whereIn('complaints.status',['PEN','REJ']);
	                if($filters['table'] == 'FORWD'){
	                    $query->whereHas('complaintUsers', function ($q) use ($filters){
	                        $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->where('complaint_users.status','ACT');
	                    });
// 	                    ->whereIn('complaints.status',['INP','ESC','REP']);
	                }
	                if($filters['table'] == 'INPESC'){
	                    $query->whereHas('complaintUsers', function ($q) use ($filters){
	                        $q->where('user_id_fk', UserHelper::getLoggedUserId ())->whereNotIn('user_role',['RECPT'])->whereNotIn('complaint_users.status', ['ACT','REJ']);
	                    });
// 	                    ->whereIn('complaints.status',['INP','ESC','REP']);
	                }
	                if($filters['table'] == 'COM'){
// 	                    $query->where('complaints.status','COM');
	                }
	                if($filters['table'] == 'CLO'){
// 	                    $query->where('complaints.status','CLO');
	                }
			    }
			}
			else if(UserHelper::getLoggedUserHasAnyRole($zonalAdminRoles)){
			    /**
			     * Handle zonal admin views
			     * Need to arrange
			     */
			    if(isset($filters['table'])){
			        if($filters['table'] == 'PEN'){
			            $query->whereHas('complaintUsers', function ($q) use ($filters){
			                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
			                $q->orWhereHas('department.region.zone', function($qd) {
			                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
			                });
			            });
// 			            ->whereIn('complaints.status',['PEN','REJ']);
			        }
		            if($filters['table'] == 'FORWD'){
		                $query->whereHas('complaintUsers', function ($q) use ($filters){
		                    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->where('complaint_users.status','ACT');
		                });
// 		                ->whereIn('complaints.status',['INP','ESC','REP']);
		            }
		            if($filters['table'] == 'INPESC'){
		                $query->whereHas('complaintUsers', function ($q) use ($filters){
		                    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
		                    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
		                    $q->orWhereHas('department.region.zone', function($qd){
		                        $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
		                    });
		                });
// 		                ->whereIn('complaints.status',['INP','ESC','REP']);
		            }
		            if($filters['table'] == 'COM'){
		                $query->whereHas('complaintUsers', function ($q) use ($filters){
		                    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
		                    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
		                    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
		                    $q->orWhereHas('department.region.zone', function($qd){
		                        $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
		                    });
		                });
// 		                ->where('complaints.status','COM');
		            }
		            if($filters['table'] == 'CLO'){
		                $query->whereHas('complaintUsers', function ($q) use ($filters){
		                    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
		                    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
		                    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
		                    $q->orWhereHas('department.region.zone', function($qd){
		                        $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
		                    });
		                });
// 		                ->where('complaints.status','CLO');
		            }
			    }
    		}
    		else if(UserHelper::getLoggedUserHasAnyRole($regionalAdminRoles)){
    		    /**
    		     * Handle regional admin views
    		     * Need to arrange
    		     */
    		    if(isset($filters['table'])){
    		        if($filters['table'] == 'PEN'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
    		                $q->orWhereHas('department.region', function($qd) use ($filters) {
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->whereIn('complaints.status',['PEN','REJ']);
    		        }
    		        if($filters['table'] == 'FORWD'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->where('complaint_users.status','ACT');
    		            })->whereIn('complaints.status',['INP','ESC','REP']);
    		        }
    		        if($filters['table'] == 'INPESC'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhereHas('department.region', function($qd){
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->whereIn('complaints.status',['INP','ESC','REP']);
    		        }
    		        if($filters['table'] == 'COM'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhereHas('department.region', function($qd){
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->where('complaints.status','COM');
    		        }
    		        if($filters['table'] == 'CLO'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhereHas('department.region', function($qd){
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->where('complaints.status','CLO');
    		        }
    		    }
    		}
    		else if(UserHelper::getLoggedUserHasAnyRole($branchAdminRoles)){
    		    /**
    		     * Handle branch admin views
    		     * Need to arrange
    		     */
    		    if(isset($filters['table'])){
    		        if($filters['table'] == 'PEN'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
    		                $q->orWhereHas('department', function($qd) use ($filters) {
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->whereIn('complaints.status',['PEN','REJ']);
    		        }
    		        if($filters['table'] == 'FORWD'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->where('complaint_users.status','ACT');
    		            })->whereIn('complaints.status',['INP','ESC','REP']);
    		        }
    		        if($filters['table'] == 'INPESC'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhereHas('department', function($qd){
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->whereIn('complaints.status',['INP','ESC','REP']);
    		        }
    		        if($filters['table'] == 'COM'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhereHas('department', function($qd){
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->where('complaints.status','COM');
    		        }
    		        if($filters['table'] == 'CLO'){
    		            $query->whereHas('complaintUsers', function ($q) use ($filters){
    		                $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
    		                $q->orWhereHas('department', function($qd){
    		                    $qd->where('manager_id_fk', UserHelper::getLoggedUserId ());
    		                });
    		            });
//     		            ->where('complaints.status','CLO');
    		        }
    		    }
    		}
			else{
				/**
				 * Handle user
				 */
				if(isset($filters['table'])){
					if($filters['table'] == 'PEN')
						$query->whereHas('complaintUsers', function ($q) use ($filters){
						    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
						});
// 						->whereIn('complaints.status',['PEN','REJ']);
					if($filters['table'] == 'FORWD'){
						$query->whereHas('complaintUsers', function ($q) use ($filters){
						    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
						});
// 						->whereIn('complaints.status',['INP','ESC','REP']);
					}
					if($filters['table'] == 'INPESC'){
						$query->whereHas('complaintUsers', function ($q) use ($filters){
						    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
						    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('status', ['ACT','REJ']);
						});
// 						->whereIn('complaints.status',['INP','ESC','REP']);
					}
					if($filters['table'] == 'COM'){
						$query->whereHas('complaintUsers', function ($q) use ($filters){
						    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
						    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
						    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
						});
// 						->where('complaints.status','COM');
					}
					if($filters['table'] == 'CLO'){
						$query->whereHas('complaintUsers', function ($q) use ($filters){
						    $q->where('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','RECPT')->whereNotIn('complaint_users.status', ['REJ']);
						    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','OWNER')->whereNotIn('complaint_users.status', ['ACT','REJ']);
						    $q->orWhere('user_id_fk', UserHelper::getLoggedUserId ())->where('user_role','ESCAL')->whereNotIn('complaint_users.status', ['ACT','REJ']);
						});
// 						->where('complaints.status','CLO');
					}
				}
			}
		}
		
		$query->with ( [
		        'solutions.resolvedBy.user' => function ($q) use ($filters) {
		        },
				'solutions.resolvedByUser.user' => function ($q) use ($filters) {
		        },		        
		        'solutions.resolvedBy.department' => function ($q) use ($filters) {
		        },
				'solutions.amendments.amendmentBy' => function ($q) use ($filters) {
				},
				'solutions.complaintOwner.user' => function ($q) use ($filters) {
				},
				'solutions.complaintOwner.department' => function ($q) use ($filters) {
				},
				'solutions.histories' => function ($q) use ($filters) {
				    $q->orderBy('solution_history_id_pk','desc');
				},
				'reminders' => function ($q) use ($filters) {
				},
				'escalations.escalatedTo.user' => function ($q) use ($filters) {
				},
				'escalations.escalatedBy.user' => function ($q) use ($filters) {
				},
				'attachments' => function ($q) use ($filters) {
				},
				'category' => function ($q) use ($filters) {
				    $q->withTrashed();
				},
// Removed in CR3
// 				'subCategory' => function ($q) use ($filters) {
// 				},
				'area' => function ($q) use ($filters) {
				    $q->withTrashed();
				},
				'complainant' => function ($q) use ($filters) {
				},
				'complaintReopens.reopnedBy' => function ($q) use ($filters) {
				},
				'complaintCloses.closedBy' => function ($q) use ($filters) {
				},
				'complaintMode' => function ($q) use ($filters) {
				    $q->withTrashed();
				},
				'complaintUsers.user.departmentUser.department' => function ($q) use ($filters) {
				},
				'complaintNotificationOtherUsers' => function ($q) use ($filters) {
				},
		] );

		if (isset($filters ['complaint_id_pk'])) {

			$isReportingComplaint = $this->getReportPurposeComplaintStatus(['complaint_id' => $filters ['complaint_id_pk']]);

			if ($isReportingComplaint) {
				$query->with ([
					'complaintUsers.department' => function ($q) use ($filters) {
					},
				]);
			}

		}
		
		if (isset ( $filters ['status'] ))
			$query->whereIn ( 'complaints.status', explode ( ',', $filters ['status'] ) );
		if (isset ( $filters ['type'] ))
			$query->where ( 'complaints.type', $filters ['type']);
		// if (isset ( $filters ['random'] ))
		// 	$query->inRandomOrder ();
		$query->orderBy('updated_at', 'desc'); // CR4 - desc by latest update
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		/**
		 * temporaly added
		 */
// 			$query->orderBy('complaint_id_pk','desc');
		return $query;
	}
	private function generateSolutionQuery($filters) {
	    if(isset($filters['with_trashed'])){
	        $query = ComplaintSolution::withTrashed();
	    }else{
	        $query = ComplaintSolution::with ( [
	            'complaint'
	        ] );
	    }
		if (isset ( $filters ['complaint_solution_id_pk'] ))
			$query->where ( 'complaint_solution_id_pk', $filters ['complaint_solution_id_pk'] );
		return $query;
	}
	private function generateAmendmentQuery($filters) {
	    if(isset($filters['with_trashed'])){
	        $query = SolutionAmendment::withTrashed()->with ( [
	            'solution.resolvedBy.user'
	        ] );
	    }else{
	        $query = SolutionAmendment::with ( [
	            'solution.resolvedBy.user'
	        ] );
	    }
	    if (isset ( $filters ['solution_amendment_id_pk'] ))
	        $query->where ( 'solution_amendment_id_pk', $filters ['solution_amendment_id_pk'] );
	        return $query;
	}
	private function buildResponse($complaint, $filters) {
	    /**
	     * if complaint_solution_id_pk is not null, need to fetch the solution details for edit
	     */
	    if(isset($filters['complaint_solution_id_pk']) && !is_null($filters['complaint_solution_id_pk'])){
// 	        $complaint->editSolution = $this->getSolution($filters);
	        $complaint->edit_solution_id_pk = $filters['complaint_solution_id_pk'];
	    }
	    /**
	     * If the escalations present order the collection to load INP -> PAS
	     * Will easy to identify INPROGRESS escalations and display them.
	     */
	    if (count ( $complaint->escalations ) > 0) {
	        $complaint->escalations = $complaint->escalations->sortBy('status');
	    }
	    
		if (count ( $complaint->attachments ) > 0) {
			foreach ( $complaint->attachments as $attachment ) {
				$complaint->source_url = $this->getFile ( array (
						'dir' => 'complaints',
						'file_name' => $attachment->source 
				) );
				$complaint->source_status = $this->getFileStatus ( array (
						'dir' => 'complaints',
						'file_name' => $attachment->source 
				) );
				$complaint->source = $attachment->source;
			}
		}
		$rolesArr = array();
		foreach ($complaint->complaintUsers as $user){
			$rolesArr[$user->user_id_fk] = $user->user_role;
		}
		$complaint->userRoles = $rolesArr;
		
		$statusArr = array();
		foreach ($complaint->complaintUsers as $user){
		    $statusArr[$user->user_id_fk] = $user->status;
		}
		$complaint->userStatus = $statusArr;
		
		$complaint->reopened = $this->checkReopened($complaint);
		
		$complaint->priority_level_text = EnumTextHelper::getEnumText($complaint->priority_level);

		$complaint->is_reporting_complaint = $this->getReportPurposeComplaintStatus(['complaint_id' => $complaint->complaint_id_pk]);

		return $complaint;
	}
	private function checkReopened($complaint){
	    $reopened['status'] = false;
	    if (count ( $complaint->complaintReopens ) > 0) {
	        foreach ($complaint->complaintReopens as $reopen){
	            if($reopen->status == 'INP'){$reopened['status'] = true; $reopened['type'] = $reopen->type; break;}
	        }
	    }
	    return $reopened;
	}
	private function buildSolutionResponse($solution) {
		return $solution;
	}
	private function buildAmendmentResponse($amendment) {
	    return $amendment;
	}
	public function getComplaints($filters) {
		$queryString = $this->generateQuery ( $filters );
		$datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($complaint) {
			return $complaint->complaint_id_pk;
		} )/* ->addColumn ( 'branch_name', function ($complaint) {
			return $complaint->department->name;
			} ) */
		->addColumn ( 'category_name', function ($complaint) {
			return $complaint->category->name;
		})->addColumn ( 'area_name', function ($complaint) {
		    return $complaint->area->name;
// Removed in CR3
// 		} )->addColumn ( 'sub_category_name', function ($complaint) {
// 			return $complaint->subCategory->name;
		} )->addColumn ( 'complaint_mode', function ($complaint) {
			return $complaint->complaintMode->name;
		} )->addColumn ( 'reminder_count', function ($complaint) {
			return count ( $complaint->reminders );
		} )->addColumn ( 'source', function ($complaint) {
			return $complaint->attachments [0]->source ?? null;
		} )->addColumn ( 'source_url', function ($complaint) {
			// return $this->getFile ( array (
			// 		'dir' => 'complaints',
			// 		'file_name' => $complaint->attachments [0]->source ?? null
			// ) );
			return $this->getFileUrlWithCustom (array (
				'dir' => 'complaints',
				'file_name' => $complaint->attachments [0]->source ?? null
			));
		} )->addColumn ( 'source_status', function ($complaint) {
			return $this->getFileStatus ( array (
					'dir' => 'complaints',
					'file_name' => $complaint->attachments [0]->source ?? null
			) );
		} )->addColumn ( 'user_roles', function ($complaint) {
			foreach ($complaint->complaintUsers as $user){
				$arr[$user->user_id_fk] = $user->user_role;
			}
			return $arr??[];
		} )->addColumn ( 'user_status', function ($complaint) {
			foreach ($complaint->complaintUsers as $user){
				$arr[$user->user_id_fk] = $user->status;
			}
			return $arr??[];
		} )->addColumn ( 'reopened', function ($complaint) {
		    return $this->checkReopened($complaint);
		} )->addColumn ( 'is_reporting_complaint', function ($complaint) {
			return $this->getReportPurposeComplaintStatus(['complaint_id' => $complaint->complaint_id_pk]);
		} )
		/* ->editColumn ( 'owner_role', function ($complaint) {
			return ($complaint->owner_role == 'ALL' ? 'All' : ($complaint->owner_role == 'MNGR' ? 'Manager' : 'Assistant Manager'));
			
		} ) */->addColumn ( 'priority_level_text', function ($complaint) {
		    return EnumTextHelper::getEnumText($complaint->priority_level );
		} )->editColumn ( 'open_date', function ($complaint) {
			return DateHelper::formatToDateString ( $complaint->open_date );
		} )->editColumn ( 'close_date', function ($complaint) {
			return $complaint->close_date ? DateHelper::formatToDateString ( $complaint->close_date ) : '';
		} )->editColumn ( 'created_at', function ($complaint) {
			return DateHelper::formatToDateString ( $complaint->created_at );
		} )
		->orderColumn('name', '-name $1');
// 		->removeColumn ( 'department', 'branch_department_id_fk', 'category', 'category_id_fk', 'subCategory', 'sub_category_id_fk', 'complaintRecepient', 'complaintMode', 'complaint_mode_id_fk', 'attachments', 'solutions', 'reminders');
// 		->removeColumn ( 'department', 'branch_department_id_fk', 'subCategory', 'sub_category_id_fk', 'complaintRecepient', 'complaintMode', 'complaint_mode_id_fk', 'attachments', 'solutions', 'reminders');
        /**
		 * temporaly added
		 */
// 		->orderColumn('id', function ($query, $order) {
// 			$query->orderBy('complaint_id_pk', $order);
// 		});
		
		return $datatable->toJson ();
	}
	public function getComplaintSchedulingIds($data) {
	    $reminderSettings = Setting::getReminderSettings();
		$query = Complaint::select('complaint_id_pk')->whereIn('status',$data['status']);
		if($reminderSettings->DAILY){
		    /**
		     * If Daily active, every day will send notifications after 3 days of open. 
		     */
		    $query->whereRaw("to_char(open_date,'YYYY-MM-DD') <= ? ", [DateHelper::getDate(- 3)]);
		}else{
		    /**
		     * Else send reminders on each
		     */
		    foreach ($reminderSettings->DAYS as $dIndex => $day){
		       if($dIndex == 0)
		           $query->whereRaw("to_char(open_date,'YYYY-MM-DD') = ? ", [DateHelper::getDate(abs($day))]);
		       else
		           $query->whereRaw("to_char(open_date,'YYYY-MM-DD') = ? ", [DateHelper::getDate(abs($day))]);
		    }
		}
		return $query->distinct()->get();
	}
	public function getComplaint($filters) {
		$queryString = $this->generateQuery ( $filters );
		$complaint = $queryString->first ();
		return $this->buildResponse ( $complaint, $filters);
	}
	public function getSolution($filters) {
		$queryString = $this->generateSolutionQuery ( $filters );
		$solution = $queryString->first ();
		return $this->buildSolutionResponse ( $solution );
	}	
	public function getTrashedSolution($filters) {
	    $queryString = $this->generateSolutionQuery ( $filters );
	    $solution = $queryString->first ();
	    return $this->buildSolutionResponse ( $solution );
	}
	public function getAmendment($filters) {
	    $queryString = $this->generateAmendmentQuery ( $filters );
	    $amendment = $queryString->first ();
	    return $this->buildAmendmentResponse ( $amendment );
	}
	public function statusUpdate($data) {

		$isReportingComplaint = $this->getReportPurposeComplaintStatus(['complaint_id' => $data ['id']]);
		$data['is_reporting_complaint'] = $isReportingComplaint;
	    /**
	     * From version 2 got chnaged.
	     * Complaint get mark as REJ if and only if all the owners mark as Rejected.
	     * Escalators rejections not affect the complaint status as owner in between complaint and the escalator. 
	     * @var unknown $complaint
	     */
	    return DB::transaction(function() use($data) {
    		if ($data ['field'] == 'status'){
    		    if($data ['status'] == 'REJ' && isset($data['reason'])){
    		        /**
    		         * In the REJ status, At complaintUser entitiy will check for active ownners and update complaint automatically
    		         */
    		        $complaintUser = ComplaintUser::where ( 'complaint_id_fk', $data ['id'] )->where('user_id_fk', UserHelper::getLoggedUserId ())->first();
    		        $complaintUser->status = $data ['status'];
    			    $complaintUser->reject_reason = $data ['reason'];
    			/**
    			 * Only owner can reject from here. not get deleted
    			 * add mode as OWNER
    			 */
    			    $data['role'] = 'OWNER';
    			    return $complaintUser->save ( $data );
    			 
    		    }elseif($data ['status'] == 'INP' && isset($data['reason'])){
    		        /**
    		         * In the status of INP with reason, its a Reopen. At complaintReopen entitiy will check for active owners and update complaint automatically
    		         */
    		        $reopen = ComplaintReopen::firstOrNew(array(
    		            'complaint_id_fk' => $data['id'],
    		            'status' => 'INP'
    		        ));
    		        $reopen->fill([
    		            'complaint_id_fk' => $data['id'],
    		            'reopen_by_id_fk' => UserHelper::getLoggedUserId(),
    		            'reopen_reason' => $data['reason'],
    		            'status' => 'INP'
    		        ]);
    		        return $reopen->save($data);
    		    }else{
    		        /**
    		         * Else handle default status update. usually get called at FORWADING state.
    		         * Remarks hold the comment related to the close.
    		         * @var unknown $complaint
    		         */
    		        if($data['status'] == 'CLO'){
    		            $completion = new ComplaintClose();
    		            $completion->fill([
    		                'complaint_id_fk' => $data['id'],
    		                'closed_by_id_fk' => UserHelper::getLoggedUserId(),
    		                'remarks' => $data['reason']??null
    		            ])->save();
    		            
    		           /**
    		            * Added for the RA analyses data arrangement
    		            * Mark a selected user as primary owner and a role manually due to roles not identified properly in the system using UPM.
    		            */
						DB::table('complaint_users')->where('complaint_id_fk', $data['id'])->whereIn('user_role', ['OWNER', 'ESCAL'])
            			->update(['primary_owner'=> false]);
						if (!$data['is_reporting_complaint']) {
							ComplaintUser::where ( 'complaint_user_id_pk', $data ['complaint_user_id_pk'] )->update(['primary_owner'=> true, 'system_role' => $data['system_role']]);
						} else {
							ComplaintUser::where('complaint_id_fk', $data['id'])->where ('user_role', 'OWNER')
								->update(['primary_owner'=> true]);
						}
    		        }
    		        
    		        $complaint = Complaint::where ( 'complaint_id_pk', $data ['id'] )->first ();
					if ($data['is_reporting_complaint'] && $data ['status'] != 'CLO') {
						$complaint->status = 'COM';
					} else {
						$complaint->status = $data ['status'];
					}
    		        return $complaint->save ( $data );
    		    }
    		}
	    });
	}
	public function solutionStatusUpdate($data){	    
	    return DB::transaction(function() use($data) {
    	    if ($data ['field'] == 'status'){
    	        /**
    	         * Else handle default status update. get called when verify / Accepted or Not Accepted
    	         * @var unknown $complaintSolution
    	         */
    	        $complaintSolution = ComplaintSolution::where ( 'complaint_solution_id_pk', $data ['id'] )->first ();
    	        $complaintSolution->status = $data ['status'];
    	        return $complaintSolution->save ( $data );
    	    }
	    });
	}
	public function delete($id) {
	    return DB::transaction(function() use($id) {
    		$complaint = Complaint::withTrashed ()->where ( 'complaint_id_pk', $id )->first ();
    		if ($complaint->trashed ()) {
    			return true;
    		}
    		return $complaint->delete ();
	    });
	}
	public function solutionDelete($id) {
	    return DB::transaction(function() use($id) {
    		$solution = ComplaintSolution::withTrashed ()->where ( 'complaint_solution_id_pk', $id )->first ();
    		if ($solution->trashed ()) {
    			return true;
    		}
    		/**
    		 * Update the solution status to DEL. otherwise restore() will not trigger status change events.
    		 */
    		$solution->update(['status' => 'DEL']);
    		return $solution->delete ();
	    });
	}
}