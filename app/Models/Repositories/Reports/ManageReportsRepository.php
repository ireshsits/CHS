<?php

namespace App\Models\Repositories\Reports;

use DateHelper;
use App\Models\Entities\Complaint;
use App\Models\Entities\Area;
use App\Models\Entities\User;
use App\Models\Entities\Setting;
use DataTables;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use EnumTextHelper;

class ManageReportsRepository implements ReportsInterface {
	
    protected $categoryLevels;
	public function __construct() {
	    $this->categoryLevels = Setting::getCategorySettings()->CATEGORY_LEVELS;
	}
	
	private function generateReportQuery($filters) {
		$query = Complaint::select ( '*' );
		/**
		 * Removed in CR3
		 */
// 		if (isset($filters ['area_id_fk'] ))
// 			$query->whereHas ( 'subCategory', function ($q) use ($filters) {
// 				$q->where ( 'area_id_fk', $filters ['area_id_fk']);
// 			} );
       if (isset ( $filters ['category_id_fk'] )){
           /**
            * Everytime raise_level will be the lowest category level
            * Get the category_level in the inverse from UI to check the value easily in query. Eg: category_level 3 item will come as category_filter_level 1 item.
            * if the filter_category_level equals 1 we can directly check it.
            */
            if ($filters['category_filter_level'] == 1) {
                $query->where('category_id_fk', $filters['category_id_fk']);
            } else {
                $query->whereHas('category', function($q) use ($filters){
                    /**
                     * Every time we get filter level - 2 to get the number of parentCategory bindings to be added in whereHas.
                     * @var unknown
                     * 2-2 = 0
                     * 3-2 = 1
                     */
                    $loop = $filters['category_filter_level'] - 2;
                    if($loop > 0){
                        $string = 'parentCategory';
                        if($loop > 1){
                            for($i = 1; $i<=$loop; $i++){
                                $string.'.parentCategory';
                            }
                        }
                        $q->whereHas($string, function($pq) use ($filters){
                            $pq->where('parent_category_id',$filters['category_id_fk']);
                        });
                    }else{
                        $q->where('parent_category_id',$filters['category_id_fk']);
                    }
                });
            }
       }
	   /**
	    * Addded to handle department filter.
	    * Use the complaintUser entity department field to get the complaint department info, as the user may get transfered after the complaint completed. 
	    */
	   if (isset ( $filters ['branch_department_id_fk'] ))
	    //    $query->whereHas ( 'complaintUsers.department', function ($q) use ($filters) {
	    //        $q->where ( 'branch_department_id_fk', $filters ['branch_department_id_fk']);
	    //    } );
		// CR4
			$query->whereHas ('complaintUsers', function ($q) use ($filters) {
				$q->where('user_role', 'RECPT')->whereHas('department', function ($q) use ($filters) {
					$q->where ( 'branch_department_id_fk', $filters ['branch_department_id_fk']);
				});
			});  
	    /**
	     * Category level dynamic.
	     * Add the category eager loading according to the number of category levels in the system 
	     * @var string $categoryString
	     */
	    $categoryString = 'category';
	    for($i = 1;$i<=($this->categoryLevels - 1);$i++){
	        $categoryString = $categoryString.'.parentCategory';
	    }
		$query->with ( [ 
				'solutions.resolvedBy.user' => function ($q) use ($filters) {
				},
				'reminders' => function ($q) use ($filters) {
				},
				'escalations.escalatedTo.user' => function ($q) use ($filters) {
				},
				'escalations.escalatedBy.user' => function ($q) use ($filters) {
				},
				'area' => function($q) use ($filters) { 
				},
				$categoryString => function ($q) use ($filters) {
				},
				/**
				 * Removed in CR3
				 */
// 				'subCategory' => function ($q) use ($filters) {
// 				},
				'complainant' => function ($q) use ($filters) {
				},
				'complaintMode' => function ($q) use ($filters) {
				},
				'complaintUsers.department.users.user' => function ($q) use ($filters) {
				},
				'complaintUsers.department.region' => function ($q) use ($filters) {
				}
				
		] );
		/**
		 * Need to rearrange filters according ComplaintUser entity. 
		 */
		if (isset ( $filters ['complaint_id_pk'] ))
			$query->where ( 'complaint_id_pk', $filters ['complaint_id_pk'] );
		if(isset($filters ['date_from']) && isset($filters['date_to']))
			$query->whereBetween( 'open_date', [ $filters ['date_from'],$filters['date_to'] ]);
		if (isset($filters ['area_id_fk'] ))
		    $query->where ( 'area_id_fk', $filters ['area_id_fk']);
		/**
		 * Removed in CR2
		 */
// 		if (isset ( $filters ['branch_department_id_fk'] ))
// 			$query->where ( 'branch_department_id_fk', $filters ['branch_department_id_fk'] );
		if (isset ( $filters ['complaint_mode_id_fk'] ))
			$query->where ( 'complaint_mode_id_fk', $filters ['complaint_mode_id_fk'] );
		/**
		 * Removed in CR3
		 */
// 		if (isset ( $filters ['sub_category_id_fk'] ))
// 			$query->where ( 'sub_category_id_fk', $filters ['sub_category_id_fk'] );
		if (isset ( $filters ['status'] ) && $filters ['status'] !== 'ALL')
		    $query->where ( 'status', $filters ['status'] );		
		if (isset ( $filters ['type'] ) && $filters ['type'] !== 'ALL')
		    $query->where ( 'type', $filters ['type'] );
		/**
		 * CR4
		 */	
		// if (isset ( $filters ['random'] ))
		// 	$query->inRandomOrder ();
		$query->orderBy('open_date', 'ASC'); // CR4
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		return $query;
	}
	public function getReportTable($filters) {
		$queryString = $this->generateReportQuery ( $filters );
		$datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($report) {
			return $report->complaint_id_pk;
		} )->addColumn ( 'branch_name', function ($report) {
// 			return $report->department->name;
		    /**
		     * Become multiple with multiple owners
		     * @var array $users
		     */
		    $branches = [];
		    foreach ($report->complaintUsers as $user){
		        // if($user->user_role == 'OWNER')
				if($user->user_role == 'RECPT') // CR4
		        $branches[] = $user->department->name;
		    }
		    return (count($branches) > 0 ? implode(', ',$branches) : '');
//Removed in CR3
// 		} )->addColumn ( 'sub_category_name', function ($report) {
// 			return $report->subCategory->name;->addColumn ('category_1', function ($report){
		})->addColumn ('area_name', function ($report){
		    return $report->area->name;
		});
		
		    for($i=1;$i<=$this->categoryLevels;$i++){
            $datatable->addColumn('category_' . $i, function ($report) use ($i) {
                if ($this->categoryLevels - $i == 0)
                    return $report->category->name;
                if ($this->categoryLevels - $i == 1)
                    return $report->category->parentCategory->name;
                if ($this->categoryLevels - $i == 2)
                    return $report->category->parentCategory->parentCategory->name;
                if ($this->categoryLevels - $i == 3)
                    return $report->category->parentCategory->parentCategory->parentCategory->name;
                if ($this->categoryLevels - $i == 4)
                    return $report->category->parentCategory->parentCategory->parentCategory->parentCategory->name;
                if ($this->categoryLevels - $i == 5)
                    return $report->category->parentCategory->parentCategory->parentCategory->parentCategory->parentCategory->name;
            });
	    }
		
		$datatable->addColumn ( 'complainant_name', function ($report) {
			return isset($report->complainant)? $report->complainant->first_name??''.' '.$report->complainant->last_name??'' : '';
		} )->addColumn ( 'nic', function ($report) {
			return isset($report->complainant)? $report->complainant->nic : '';
		} )->addColumn ( 'resolved_by', function ($report) {
// 			return (count($report->solutions) > 0 ? $report->solutions->last()->resolvedBy->user->first_name.' '.$report->solutions->last()->resolvedBy->user->last_name:'');
            /**
             * Become multiple with multiple owners
             * @var array $users
             */
		    $users = [];
            foreach ($report->solutions as $solution){
                // $users[] = $solution->resolvedBy->user->first_name??''.' '.$solution->resolvedBy->user->last_name??'';
				$users[] = $solution->resolvedByUser->user->first_name??''.' '.$solution->resolvedByUser->user->last_name??''; // CR4
            }
            return (count($users) > 0 ? implode(', ',$users) : '');
		} )->addColumn ( 'reminder_1', function ($report) {
			return (count($report->reminders) > 0 ? DateHelper::formatToDateString($report->reminders[0]->reminder_date) : '');
		} )->addColumn ( 'reminder_2', function ($report) {
			return (count($report->reminders) > 1 ? DateHelper::formatToDateString($report->reminders[1]->reminder_date) : '');
		} )->addColumn ( 'reminder_3', function ($report) {
			return (count($report->reminders) > 2 ? DateHelper::formatToDateString($report->reminders[2]->reminder_date) : '');
		} )->editColumn ( 'open_date', function ($report) {
			return $report->open_date ? DateHelper::formatToDateString ( $report->open_date ) : '';
		} )->editColumn ( 'close_date', function ($report) {
			return $report->close_date ? DateHelper::formatToDateString ( $report->close_date ) : '';
		} )->editColumn ( 'type', function ($report) {
		    return $report->type ? EnumTextHelper::getEnumText ( $report->type ) : '';
		} )->editColumn ( 'created_at', function ($report) {
			return DateHelper::formatToDateString ($report->created_at );
// 		} )->removeColumn ( 'complaintUsers', 'branch_department_id_fk', 'category', 'category_id_fk', 'subCategory', 'complaintMode', 'sub_category_id_fk', 'attachments','complainant','solutions','resolvedBy', 'reminders');
		} )->removeColumn ( 'complaintUsers', 'branch_department_id_fk', 'complaintMode', 'attachments','complainant','solutions','resolvedBy', 'reminders');
		
		return $datatable->toJson ();
	}
	public function getReports(array $filters) {
		return $this->generateReportTable ( $filters );
	}
	public function formatDataToReport($filters) {

		$data = array(
			'title' => ($filters ['type'] == 'CMPLA') ? "CUSTOMER COMPLAINTS" : (($filters ['type'] == 'CMPLI') ? "CUSTOMER COMPLIMENTS" :  "CUSTOMER COMPLAINTS / COMPLIMENTS"),
			'related_area' => (isset($filters ['area_id_fk'])) ? Area::find($filters ['area_id_fk'])->name : ''
		);
		return $data;
	}
	public function exportExcel($filters) {
		$complaints = $this->generateReportQuery ( $filters )->get();
		// $data = array(
		// 	'title' => ($filters ['type'] == 'CMPLA') ? "CUSTOMER COMPLAINTS" : (($filters ['type'] == 'CMPLI') ? "CUSTOMER COMPLIMENTS" :  "CUSTOMER COMPLAINTS / COMPLIMENTS")
		// 	'related_area' => ''
		// );
		// $title = ($filters ['type'] == 'CMPLA') ? "CUSTOMER COMPLAINTS" : (($filters ['type'] == 'CMPLI') ? "CUSTOMER COMPLIMENTS" :  "CUSTOMER COMPLAINTS / COMPLIMENTS");
		return (new ReportExport($complaints,$filters, $this->categoryLevels, $this->formatDataToReport($filters)))->download('CCER_'.$filters['date_from'].'-'.$filters['date_to'].'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
	}
	public function exportPDF($filters) {
		$complaints = $this->generateReportQuery ( $filters )->get();
		return (new ReportExport($complaints,$filters, $this->categoryLevels))->download('CCER_'.$filters['date_from'].'-'.$filters['date_to'].'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
	}
}