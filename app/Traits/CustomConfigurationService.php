<?php

namespace App\Traits;

use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintUser;
use App\Models\Entities\BranchDepartment;
use App\Models\Entities\Area;
use Log;

trait CustomConfigurationService {

    public function getReportPurposeComplaintStatus ($data) {

		try {

            $branchDepartments = [];
            
            if (isset($data['complaint_id'])) {
                
                $complaint = Complaint::find($data['complaint_id']);
                $complaintUserDepartments = ComplaintUser::where ('complaint_id_fk', $data['complaint_id'])->get();

                foreach ($complaintUserDepartments as $key => $userdepartment) {
                    // $branchName = str_replace(' ', '', strtoupper(BranchDepartment::find($userdepartment['branch_department_id_fk'])->name));
                    $branchSolId = str_replace(' ', '', strtoupper(BranchDepartment::find($userdepartment['branch_department_id_fk'])->sol_id));
                    if (!in_array($branchSolId, $branchDepartments)) {
                        $branchDepartments[] = $branchSolId;
                    }
                }

                $complaintAreaName = str_replace(' ', '', strtoupper(Area::find($complaint->area_id_fk)->name));

            } else {

                foreach ($data['complaint_user_departments'] as $key => $userdepartment) {
                    // $branchName = str_replace(' ', '', strtoupper(BranchDepartment::find($userdepartment)->name));
                    $branchSolId = str_replace(' ', '', strtoupper(BranchDepartment::find($userdepartment)->sol_id));
                    if (!in_array($branchSolId, $branchDepartments)) {
                        $branchDepartments[] = $branchSolId;
                    }
                }

                $complaintAreaName = str_replace(' ', '', strtoupper(Area::find($data['area_id_fk'])->name));

            }

            // $branchDepartments[] = 'NSC'; // test
            
            $areas = explode('|', config('custom.report_purpose_complaint.areas'));
            $branchDepartmentsForReports = explode('|', config('custom.report_purpose_complaint.branch_departments'));

            $reportBrancheDepartments = array_intersect($branchDepartments, $branchDepartmentsForReports);

            return (in_array($complaintAreaName, $areas) && !empty($reportBrancheDepartments)) ? true : false;
            
		} catch (\Exception $e) {
            Log::error('Custom Configuration Service Error >>' . json_encode($e));
			// return null;
		}

	}

}