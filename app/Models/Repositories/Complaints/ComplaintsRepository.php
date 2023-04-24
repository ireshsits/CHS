<?php
namespace App\Models\Repositories\Complaints;

use DateHelper;
use ArrayHelper;
use UserHelper;
use EnumTextHelper;
use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintUser;
use App\Models\Entities\Complainant;
use App\Models\Entities\ComplaintAttachment;
use App\Models\Entities\ComplaintEscalation;
use App\Models\Entities\ComplaintMode;
use App\Models\Entities\ComplaintReminder;
use App\Models\Entities\ComplaintSolution;
use App\Models\Entities\SolutionAmendment;
use App\Models\Entities\SolutionHistory;
use App\Models\Entities\ComplaintNotificationOtherUser;
use DB;
use App\Traits\CustomConfigurationService;

class ComplaintsRepository
{

    use CustomConfigurationService;

    public function __construct()
    {}

    /**
     * Fetch requirements for complaint setup
     *
     * @param unknown $data
     * @return unknown
     */
    private function generateSearchQuery($data)
    {
        $query = ComplaintMode::select('*');
        if (isset($data['name']))
            $query->whereRaw('LOWER(name) like ?', array(
                'name' => '%' . strtolower($data['name']) . '%'
            ));
        return $query->where('status', 'ACT')->distinct();
    }
    private function generateComplaintUsersSearchQuery($data)
    {
        $query = ComplaintUser::select('*')->with(['user']);
        if (isset($data['name']))
            $query->whereHas('user', function ($q) use ($data){
                $q->whereRaw('LOWER(first_name) like ?', array('name' => '%' . strtolower($data['name']) . '%'));
                $q->orWhereRaw('LOWER(last_name) like ?', array('name' => '%' . strtolower($data['name']) . '%'));
                $q->orWhereRaw('LOWER(email) like ?', array('name' => '%' . strtolower($data['name']) . '%'));
            });
        return $query->where('complaint_id_fk',$data['resource_id'])->whereNotIn('user_role',['RECPT'])->distinct();
    }

    private function buildComplaintModesSearchResponse($mode)
    {
        $mode->id = $mode->complaint_mode_id_pk;
        $mode->text = $mode->name;
        $mode->display_category = true;
        return $mode;
    }
    private function buildComplaintUsersSearchResponse($complaintUser){
        $user['id'] = $complaintUser->complaint_user_id_pk;
        $user['text'] = $complaintUser->user->first_name.' '.$complaintUser->user->last_name.' - '.$complaintUser->user->email;
        return $user;
    }

    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getComplaintModesForSearch($filters)
    {
        $queryString = $this->generateSearchQuery($filters);
        $response = $queryString->get();
        foreach ($response as $mode) {
            $mode = $this->buildComplaintModesSearchResponse($mode);
        }
        return $response;
    }
    
    public function getComplaintUsersForSearch($filters){
        $queryString = $this->generateComplaintUsersSearchQuery($filters);
        $response = $queryString->get();
        foreach ($response as $complaintUser) {
            $users[] = $this->buildComplaintUsersSearchResponse($complaintUser);
        }
        return $users??[];
    }

    /**
     * Save a Complaint
     *
     * @param array $filters
     * @return boolean
     */
    public function save(array $params){
        return DB::transaction(function() use($params) {
            if (isset($params['customer']))
                $complainantModel = $this->saveOrEditComplainant($params);
    
            if ((isset($complainantModel) && $complainantModel)) {
                $params['complainant_id_fk'] = $complainantModel->complainant_id_pk;
            } else {
                $params['complainant_id_fk'] = null;
            }
            $complaintModel = $this->saveOrEditComplaint($params);
            return $complaintModel->reference_number;
        });
    }

    /**
     * Reply to Complaint
     *
     * @param unknown $params
     * @return boolean
     */
    public function saveReply($params){
        return DB::transaction(function() use($params) {
            $userId = ComplaintUser::where('complaint_id_fk', $params['complaint_id_fk'])->where('user_id_fk', UserHelper::getLoggedUserId())->first()->complaint_user_id_pk;
            $params += array(
                'resolved_by_fk' => $userId,
                'status' => 'PEN'
            );
    
            $complaintSolutionModel = ComplaintSolution::withTrashed()->firstOrNew(array(
                'resolved_by_fk' => $params['resolved_by_fk'],
                'complaint_id_fk' => $params['complaint_id_fk']
            ));
            $washTrashed = false;
            if($complaintSolutionModel->trashed()){
                $washTrashed = true;
                $complaintSolutionModel->restore();
            }
            /**
             * Save Current solution in history before update it when not in PEN mode
             */
            if($complaintSolutionModel->exists && $complaintSolutionModel->status !== 'PEN' && !$washTrashed)
                $this->addToSolutionHistory($complaintSolutionModel);
                
            $complaintSolutionModel->fill($params)->save($params);
            return $complaintSolutionModel->complaint_solution_id_pk;
        });
    }
    /**
     * Ammendment to solution
     * @param unknown $params
     * @return unknown
     */
    public function saveAmendment(array $params){
        return DB::transaction(function() use($params) {
            $amendment = SolutionAmendment::withTrashed()->firstOrNew(array(
                'amendment_by_fk' => UserHelper::getLoggedUserId(),
                'complaint_solution_id_fk' => $params['complaint_solution_id_fk']
            ));
            if($amendment->trashed())
                $amendment->restore();
                
            $amendment->fill($params)->save($params);
            return $amendment->solution_amendment_id_pk;
        });
    }

    public function saveEscalation($params)
    {
        /**
         * If the user is the OWNER, then add the id to the owner_id_fk
         * If the user is a ESCALATED, then get the escalation info related to him and get the owner_info;
         * @var unknown $user
         */
        return DB::transaction(function() use($params) {
            $user = ComplaintUser::where('complaint_id_fk', $params['complaint_id_fk'])->where('user_id_fk', UserHelper::getLoggedUserId())->first();
            if($user->user_role  == 'OWNER'){
                $params+= array(
                    'owner_id_fk' => $user->complaint_user_id_pk
                );
            }else if($user->user_role == 'ESCAL'){
                $ownEscalationInfo = ComplaintEscalation::where('complaint_id_fk', $params['complaint_id_fk'])->where('escalated_to_fk', $user->complaint_user_id_pk)->first();
                $params+= array(
                    'owner_id_fk' => $ownEscalationInfo->owner_id_fk
                );
            }
            
            $params += array(
             'escalated_by_fk' => $user->complaint_user_id_pk,
             'status' => 'INP'
            );
            /**
             * As Escalation done only to one person saveOrEditComplaintUsers will return only one ComplaintUser table id in array.
             */
            $params['branch_department_user'][0] = $params['escalated_to_fk'];
            $params['escalated_to_fk'] = $this->saveOrEditComplaintUsers($params, $params['complaint_id_fk'], 'ESCAL')[0];
    
            $complaintEscalateModel = ComplaintEscalation::firstOrNew(array(
                'escalated_by_fk' => $params['escalated_by_fk'],
                'complaint_id_fk' => $params['complaint_id_fk']
            ));
            $complaintEscalateModel->fill($params)->save($params);
    
            return $complaintEscalateModel->complaint_escalation_id_pk;
        });
    }

    /**
     * Send Reminder to Complaint
     *
     * @param unknown $params
     */
    public function sendReminder($params)
    {
        /**
         * If the reminder sent by the system, remindered_by_fk is 0.
         */
        $params['override_status'] = 'RMDIR';
        Complaint::sendNotifications($params['complaint_id_pk'], $params['role'], $params['schedule']? 0:UserHelper::getLoggedUserId(), $params['override_status'], $params['schedule']);
        $this->saveOrEditReminder($params['complaint_id_pk'], $params['schedule']? 0:UserHelper::getLoggedUserId());
        return true;
    }

    /**
     *
     * @param unknown $complaintId
     */
    private function saveOrEditReminder($complaintId, $reminderedById)
    {
        /**
         * Not saving manually sending rminders. only by the system
         */
        if((int)$reminderedById == 0){
        $reminder = new ComplaintReminder();
        $reminder->fill([
            'complaint_id_fk' => $complaintId,
            'reminded_by_fk' => $reminderedById,
            'reminder_date' => DateHelper::getDate(),
            'status' => 'INP'
        ])->save();
        }
    }

    /**
     * Save or Edit complaint
     *
     * @param unknown $params
     * @return unknown
     */
    private function saveOrEditComplaint($params)
    {
        $complaintModel = Complaint::firstOrNew(array(
            'complaint_id_pk' => $params['complaint_id_pk'] ?? 0
        ));
        $complaintModel->fill([
            // 'reference_number' => $complaintModel->reference_number ?? Complaint::getLastReferenceNumber($params),
            'reference_number' => $complaintModel->reference_number ? Complaint::formatReferenceNumberForMode($complaintModel->reference_number, $params) : Complaint::getLastReferenceNumber($params), // CR4
            // 'branch_department_id_fk' => $params ['branch_department_id_fk'],
            'area_id_fk' => $params['area_id_fk'],
            'category_id_fk' => $params['category_id_fk'],
            /**
             * Removed Sub Category filed in CR3
             */
//             'sub_category_id_fk' => $params['sub_category_id_fk'],
            'complainant_id_fk' => $params['complainant_id_fk'],
            // 'complaint_recepient_id_fk' => $complaintModel->complaint_recepient_id_fk ?? UserHelper::getLoggedUserId (),
            'complaint_mode_id_fk' => $params['complaint_mode_id_fk'],
            'account_no' => $params['account_no'] ?? null,
            'complaint' => $params['complaint'],
            'priority_level' => $params['priority_level'] ?? 'NOR',
            'type' => (isset($params['type']) ? 'CMPLA' : 'CMPLI'),
            // 'owner_role' => $params ['owner_role'],
            'open_date' => $complaintModel->open_date ?? DateHelper::getDate(),
            'status' => $complaintModel->status ?? 'PEN'
        ])->save();
        
        // check if report purpose
        $isReporting = $this->getReportPurposeComplaintStatus([
            'area_id_fk' => $params['area_id_fk'],
            'complaint_user_departments' =>  ($params['branch_department_id_fk']) ?? []
        ]);
        
        if ($isReporting == false) {
            $this->saveOrEditComplaintUsers($params, $complaintModel->complaint_id_pk, 'OWNER');
        } else {
            /**
             * For reporting purpose only Branch Department is saved
             */
            $this->saveOrEditComplaintUserBranchDepartment($params, $complaintModel->complaint_id_pk, 'OWNER');
        }

        if (isset($params['notify_to']) && !empty($params['notify_to'])) {
            $this->saveOrEditComplaintOtherNotificationUsers($params, $complaintModel->complaint_id_pk);
        }

        if ($params['mode'] !== 'edit')
            $this->saveOrEditComplaintRecipeintUser($complaintModel->complaint_id_pk, 'RECPT');

        return $complaintModel;
    }

    /**
     * Save or Edit complint_users recepient user
     *
     * @param unknown $params
     */
    private function saveOrEditComplaintRecipeintUser($complaintId, $role)
    {
        /**
         * assigned_by_id_fk is the person who assign the user to the mentioned role.
         * currently assumed only recipeint can edit a complaint.
         */
        $userDeptId = UserHelper::getUserBranchDept()->branch_department_id_pk;
        $complaintUser = ComplaintUser::firstOrNew(array(
            'complaint_id_fk' => $complaintId,
            'user_id_fk' => UserHelper::getLoggedUserId(),
            'branch_department_id_fk' => $userDeptId,
            'user_role' => $role,
            'assigned_by_id_fk' => UserHelper::getLoggedUserId()
        ));
        if ($complaintUser->trashed()) {
            $complaintUser->restore();
        }
        $complaintUser->fill([
            'complaint_id_fk' => $complaintId,
            'user_id_fk' => UserHelper::getLoggedUserId(),
            'branch_department_id_fk' => $userDeptId,
            'user_role' => $role,
            'assigned_by_id_fk' => UserHelper::getLoggedUserId()
        ])->save();
    }

    /**
     * Save or Edit complint_users owner/Escaltor
     *
     * @param unknown $params
     */
    private function saveOrEditComplaintUsers($params, $complaintId, $role)
    {
//         dump($params, $complaintId, $role);
        /**
         * branch_department_id_fk contains the array of branch/departments ids
         * department_owner array contains the ids of above branch/department users.
         * assigned_by_id_fk is the person who assign the user to the mentioned role. currently assumed only recipeint can edit a complaint.
         * Need to drop the users who are not involved in the system after an edit.
         */
        ComplaintUser::where('complaint_id_fk', $complaintId)->
        // ->whereNotIn('branch_department_id_fk', $params['branch_department_id_fk'])
        whereNotIn('user_id_fk', $params['branch_department_user'])
            ->where('user_role', $role)
            ->where('assigned_by_id_fk', UserHelper::getLoggedUserId())
            ->delete();

        foreach ($params['branch_department_id_fk'] as $branchkey => $branchId) {
            
            $complaintUser = ComplaintUser::firstOrNew(array(
                'complaint_id_fk' => $complaintId,
                'user_id_fk' => $params['branch_department_user'][$branchkey],
                'branch_department_id_fk' => $branchId,
                'user_role' => $role,
                'assigned_by_id_fk' => UserHelper::getLoggedUserId()
            ));
            if ($complaintUser->trashed()) {
                $complaintUser->restore();
            }
            $dbParams = [
                'complaint_id_fk' => $complaintId,
                'user_id_fk' => $params['branch_department_user'][$branchkey],
                'branch_department_id_fk' => $branchId,
                'user_role' => $role,
                'assigned_by_id_fk' => UserHelper::getLoggedUserId(),
            ];
            /**
             * ESCALATED user get added at the time of complaint INPROGRESS. so mark the new escalated user as INP
             */
            if($role == 'ESCAL')
                $dbParams['status'] = 'INP';
            
            $complaintUser->fill($dbParams)->save();

            $usersIds[] = $complaintUser->complaint_user_id_pk;
        }
        return $usersIds;
    }

    /**
     * Save or Edit complint_users Branch / Department(for reporting purpose)
     *
     * @param unknown $params
     */
    private function saveOrEditComplaintUserBranchDepartment($params, $complaintId, $role)
    {
        /**
         * branch_department_id_fk contains the array of branch/departments ids
         * complaint owners are not considered in this
         * assigned_by_id_fk is the person who assign the user to the mentioned role. currently assumed only recipeint can edit a complaint.
         * Need to drop the branch/departments who are not involved in the system after an edit.
         */
        ComplaintUser::where('complaint_id_fk', $complaintId)
            ->whereNotIn('branch_department_id_fk', $params['branch_department_id_fk'])
            // ->whereNotIn('user_id_fk', $params['branch_department_user'])
            ->where('user_role', $role)
            ->where('assigned_by_id_fk', UserHelper::getLoggedUserId())
            ->delete();

        foreach ($params['branch_department_id_fk'] as $branchkey => $branchId) {
            
            $complaintUser = ComplaintUser::firstOrNew(array(
                'complaint_id_fk' => $complaintId,
                // 'user_id_fk' => $params['branch_department_user'][$branchkey],
                'branch_department_id_fk' => $branchId,
                'user_role' => $role,
                'assigned_by_id_fk' => UserHelper::getLoggedUserId()
            ));
            if ($complaintUser->trashed()) {
                $complaintUser->restore();
            }
            $dbParams = [
                'complaint_id_fk' => $complaintId,
                // 'user_id_fk' => $params['branch_department_user'][$branchkey],
                'branch_department_id_fk' => $branchId,
                'user_role' => $role,
                'assigned_by_id_fk' => UserHelper::getLoggedUserId(),
            ];
            /**
             * ESCALATED user get added at the time of complaint INPROGRESS. so mark the new escalated user as INP
             */
            // if($role == 'ESCAL')
            //     $dbParams['status'] = 'INP';
            
            $complaintUser->fill($dbParams)->save();

            $branchIds[] = $branchId;
        }
        return $branchIds;
    }

    /**
     * Save or edit Complainant
     *
     * @param unknown $params
     * @return unknown
     */
    private function saveOrEditComplainant($params)
    {
        $complainantModel = Complainant::firstOrNew(array(
            'nic' => $params['nic']
        ));

        $complainantModel->fill($params)->save();
        return $complainantModel;
    }

    /**
     * Save or edit Complaint Other Notification Users
     *
     * @param unknown $params
     * @return unknown
     */
    private function saveOrEditComplaintOtherNotificationUsers($params, $complaintId)
    {
        ComplaintNotificationOtherUser::where('complaint_id_fk', $complaintId)
            ->whereNotIn('user_id_fk', $params['notify_to'])
            ->delete();

        foreach ($params['notify_to'] as $key => $userId) {
        
            $complaintNotifyUser = ComplaintNotificationOtherUser::firstOrNew(array(
                'complaint_id_fk' => $complaintId,
                'user_id_fk' => $userId
            ));
            if ($complaintNotifyUser->trashed()) {
                $complaintNotifyUser->restore();
            }
            $dbParams = [
                'complaint_id_fk' => $complaintId,
                'user_id_fk' => $userId
            ];
            
            $complaintNotifyUser->fill($dbParams)->save();

            $notifyusersIds[] = $complaintNotifyUser->complaint_notification_other_user_id_pk;
        }
        return $notifyusersIds;
        
    }
    
    /**
     * Add Solution history
     */
    private function addToSolutionHistory($solutionModal){
        $solutionHistoryModel = new SolutionHistory();
        $solutionHistoryModel->fill([
            'complaint_solution_id_fk' => $solutionModal->complaint_solution_id_pk,
            'owner_id_fk' => $solutionModal->owner_id_fk,
            'resolved_by_fk' => $solutionModal->resolved_by_fk,
            'action_taken' => $solutionModal->action_taken,
            'status' => $solutionModal->status,
            'created_at' => $solutionModal->created_at,
            'updated_at' => $solutionModal->updated_at,
            'deleted_at' => $solutionModal->deleted_at
        ])->save();
    }

    /**
     * Save or Edit Attachments
     *
     * @param unknown $params
     */
    public function saveOrEditAttachments($params)
    {
        foreach ($params['attachments'] as $attachment) {
            $complaintAttachModel = ComplaintAttachment::firstOrNew(array(
                'complaint_id_fk' => $params['complainId']
            ));

            $complaintAttachModel->fill($attachment)->save();
        }
    }

    public function getEnumValues($field)
    {
        return Complaint::getPossibleEnumValues($field);
    }

    /**
     * Mail Tester
     */
    /**
     * Send Reminder to Complaint
     *
     * @param unknown $params
     */
    public function testMail($id)
    {
        Complaint::sendNotifications($id);
        return true;
    }
}