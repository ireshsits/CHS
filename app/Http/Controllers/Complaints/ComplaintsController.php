<?php
namespace App\Http\Controllers\Complaints;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Complaints\ComplaintsRepository;
use App\Models\Repositories\Complaints\ManageComplaintsRepository;
use Illuminate\Support\Facades\Validator;
use App\Traits\FileService;
use DateHelper;
use RoleHelper;
use UserHelper;
use Log;
use Auth;
use DB;
use App\Models\Entities\User;
use App\Rules\ReportingComplaint;
use App\Traits\CustomConfigurationService;

class ComplaintsController extends Controller
{

    use FileService;
    use CustomConfigurationService;

    protected $complaintsRepo;

    protected $manageComplaintsRepo;

    public function __construct()
    {
        $this->complaintsRepo = new ComplaintsRepository();
        $this->manageComplaintsRepo = new ManageComplaintsRepository();
    }

    public function index($encodedURI = null, Request $request)
    {
        try {
//         $params = substr($encodedURI, strrpos($encodedURI, '&') + 1);
        $params = explode('&', $encodedURI);
        /**
         *  0 => "mode=edit"
         *  1 => "id=1"
         *  2 => "subMode=solutionEdit"
         *  3 => "sid=2"
         */
        $params = array_filter($params);
        if (count($params) > 0) {
            $complaint = $this->manageComplaintsRepo->getComplaint([
//                 'complaint_id_pk' => explode('=', $params)[1],
                'complaint_id_pk' => explode('=', $params[1])[1],
                'complaint_solution_id_pk' => isset($params[3])?explode('=', $params[3])[1]:null
            ]);
            if ($complaint) {
                
                // redirect to complaint view only for complaint notification other users
                if ($complaint->complaintNotificationOtherUsers != null) {
                    foreach ($complaint->complaintNotificationOtherUsers as $user) {
                        if($user->user_id_fk == UserHelper::getLoggedUserId()) {
                            return redirect()->route('dashboard.search.get.complaint', urlencode('mode=edit&id='.explode('=', $params[1])[1]));
                        }
                    }
                }
//                 dd(Auth::user(),UserHelper::getLoggedUserId(),$complaint->userRoles,$complaint->complaint_id_pk);
                if (array_key_exists(UserHelper::getLoggedUserId(), $complaint->userRoles) || 
                    UserHelper::getLoggedUserHasAnyRole(RoleHelper::getAdminViewRoles()) || 
                    UserHelper::getLoggedUserHasAnyRole(RoleHelper::getZonalAdminRoles()) || 
                    UserHelper::getLoggedUserHasAnyRole(RoleHelper::getRegionalAdminRoles()) || 
                    UserHelper::getLoggedUserHasAnyRole(RoleHelper::getBranchAdminRoles())) {
                    if ($complaint->status == 'PEN') {
                        /**
                         * CR2 changes
                         */
                        if ((isset($complaint->userRoles[UserHelper::getLoggedUserId()]) && $complaint->userRoles[UserHelper::getLoggedUserId()] == "RECPT") ||
                            UserHelper::getLoggedUserHasAnyRole(RoleHelper::getAdminViewRoles()) ||
                            UserHelper::getLoggedUserHasAnyRole(RoleHelper::getZonalAdminRoles()) ||
                            UserHelper::getLoggedUserHasAnyRole(RoleHelper::getRegionalAdminRoles()) ||
                            UserHelper::getLoggedUserHasAnyRole(RoleHelper::getBranchAdminRoles())) {} else {
                            // return response()->view('errors', [], 405);
                            abort(405);
                        }
                    }
                    
                    // complaint rejections by users
                    $complaintRejections = DB::table('complaint_users')->where('complaint_id_fk', '=', explode('=', $params[1])[1])->where('status', '=', 'REJ')->get();
                    foreach ($complaintRejections as $user) {
                        $user->user_info = User::find($user->user_id_fk);
                    }
                    
                    $previousTable = "PEN";
                    foreach ($params as $p) {
                        $pData = explode('=', $p);
                        if ($pData[0] == "pretable") {
                            $previousTable = $pData[1];
                        }
                    }

                    $viewParams = array(
                        'mode' => 'edit',
                        'complaint' => $complaint,
                        'complaint_rejections' => $complaintRejections,
                        'table' => $previousTable
                    );

                    if(isset($params[2]) && explode('=', $params[2])[0] != 'pretable')
                        $viewParams+=array(
                            'subMode' =>'solutionEdit'
                        );
                    return view('dashboard.complaints.setup', $viewParams);
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        } else {
            return view('dashboard.complaints.setup', [
                'mode' => 'new',
                'complaint' => collect(),
                'complaint_rejections' => collect()
            ]);
        }
        } catch ( \Exception $e ) {
            Log::error('Complaint Page Error >>' . json_encode($e));
            abort ( 404 );
        }
    }

    public function complaintModesForSearch(Request $request)
    {
        return json_encode(array(
            "result" => "success",
            'items' => $this->complaintsRepo->getComplaintModesForSearch($request->all())
        ));
    }

    public function getEnumValues($field, Request $request)
    {
        return json_encode(array(
            "result" => "success",
            'items' => $this->complaintsRepo->getEnumValues($field)
        ));
    }

    public function manage($type, $table='PEN', Request $request)
    {
        try {
            return view('dashboard.complaints.manage', [
                'type' => $type == 'CMPLA' ? 'complaints' : 'compliments',
                'table' => (isset($table)) ? $table : 'PEN',
                'typeCode' => $type ?? 'CMPLA'
            ]);
        } catch (\Exception $e) {
            Log::error('Complaint Manage Page Error >>' . json_encode($e));
            abort(404);
        }
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $regexValid = isset($data['customer']) ? true : false;
        if ($data['mode'] == 'edit' && $data['sourceRef'] && $data['sourceRef'] == 'db') {
            unset($data['attachment']);
        }
        return Validator::make($data, [
            'nic' => [
                'required_with:customer',
                $regexValid ? 'regex:/^([0-9]{9}[x|X|v|V]|[0-9]{12})$/' : ''
            ],
            'title' => 'required_with:customer',
            'first_name' => 'required_with:customer',
            'last_name' => 'required_with:customer',
            'contact_no' => 'required_with:customer',
            //  
            'branch_department_id_fk' => 'required|array|min:1',
            // 'branch_department_user' => 'required|array|min:1',
            'branch_department_user' => 'array',
            'area_id_fk' => 'required|exists:areas,area_id_pk',
            'category_id_fk' => 'required|exists:categories,category_id_pk',
//             'sub_category_id_fk' => 'required_with:category_id_fk|exists:sub_categories,sub_category_id_pk',
            'complaint_mode_id_fk' => 'required|exists:complaint_modes,complaint_mode_id_pk',
            'complaint' => 'required',
            'attachment' => 'sometimes|file|mimes:pdf,eml,png,jpeg|max:3096'
        ], [
            'required' => 'The :attribute is required.',
            'branch_department_id_fk.required' => 'Select branch name.',
            'branch_department_user.required' => 'Select branch user.',
            'area_id_fk' => 'Select area.',
            'category_id_fk.required' => 'Select category.',
            'sub_category_id_fk.required_with' => 'Select sub category for selected category.',
            'complaint_mode_id_fk.required' => 'Select mode.',
            'category_id_fk.exists' => 'Selected category not exists.',
//             'sub_category_id_fk.exists' => 'Selected sub category not exists.',
            'complaint_mode_id_fk.exists' => 'Selected mode not exists.',
            'file' => 'The :attribute must be a valid file',
            'mimes' => 'The :attribute must be one of the following types: :values',
            'max' => 'The :attribute size should not exceed 3MB (3096 KB).'
        ]);
    }

    public function saveComplaint(Request $request)
    {
        /**
         * Validate
         */
        $this->validator($request->all())
            ->validate();
        
        $isReporting = $this->getReportPurposeComplaintStatus([
            'area_id_fk' => $request->get('area_id_fk'),
            'complaint_user_departments' => ($request->get('branch_department_id_fk')) ?? []
        ]);

        $branchDepartmentIds = ($request->get('branch_department_id_fk')) ? count($request->get('branch_department_id_fk')) : 0;
        $branchDepartmentUsers = ($request->get('branch_department_user')) ? count($request->get('branch_department_user')) : 0;

        if ($isReporting == false && ($branchDepartmentIds != $branchDepartmentUsers)) {
            return json_encode(array(
                'status' => 'false',
                'message' => 'Branch department users not found.',
                'redirect_url' => route('dashboard.complaint.manage', [
                    'type' => ($request->has('type') ? 'CMPLA' : 'CMPLI')
                ])
            ));
        }

        $crefn = $this->complaintsRepo->save($request->all());
        if ($crefn) {
            if ($request->has('sourceRef') && $request->input('sourceRef') == 'new') {
                if ($request->hasFile('attachment')) {
                    $file = $this->saveFile(array(
                        'file' => $request->file('attachment'),
                        'dir' => 'complaints',
                        'object' => 'Complaint',
                        'reference' => $crefn,
                        'saveInDB' => true,
                        'cacheData' => false
                    ));
                    if ($file) {
                    /**
                     * proceed if a new file added.
                     * remove old file
                     */
                        // if ($request->has ( 'mode' ) && $request->input ( 'mode' ) == 'edit') {
                        // $this->deleteFile ( array (
                        // 'file' => $request->input ( 'source' ),
                        // 'dir' => 'complaints'
                        // ) );
                        // }
                    }
                }
            } else if ($request->has('sourceRef') && $request->input('sourceRef') == 'delete') {
                /**
                 * proceed if file deleted.
                 * remove file
                 */
                if ($request->has('mode') && $request->input('mode') == 'edit') {
                    $this->deleteFile(array(
                        'file' => $request->input('source'),
                        'dir' => 'complaints',
                        'object' => 'Complaint',
                        'reference' => $crefn,
                        'saveInDB' => true,
                        'cacheData' => false
                    ));
                }
            }
            return json_encode(array(
                'status' => 'true',
                'reference_number' => $crefn,
                'redirect_url' => route('dashboard.complaint.manage', [
                    'type' => ($request->has('type') ? 'CMPLA' : 'CMPLI')
                ])
            ));
        } else {
            return json_encode(array(
                'status' => 'false',
                'redirect_url' => route('dashboard.complaint.manage', [
                    'type' => ($request->has('type') ? 'CMPLA' : 'CMPLI')
                ])
            ));
        }
    }

    public function getComplaints(Request $request)
    {
        try {
            return $this->manageComplaintsRepo->getComplaints($request->all());
        } catch (\Exception $e) {
            Log::error('Get Complaints Error >>' . json_encode($e));
            abort(404);
        }
    }

    public function getComplaint($encodedURI = null, Request $request)
    {
        try {
            $id = explode('=', $encodedURI)[1];
            return $this->manageComplaintsRepo->getComplaint(array(
                'complaint_id_pk' => $id
            ));
        } catch (\Exception $e) {
            Log::error('Get Complaint Error >>' . json_encode($e));
            abort(404);
        }
    }
    
    public function getComplaintSolution($encodedURI = null, Request $request)
    {
        try {
            $id = explode('=', $encodedURI)[1];
            return $this->manageComplaintsRepo->getSolution(array(
                'complaint_solution_id_pk' => $id
            ));
        } catch (\Exception $e) {
            Log::error('Get Complaint Solution Error >> '. json_encode($e));
            abort(404);
        }
    }
    
    public function getComplaintUsers(Request $request){
        return json_encode(array(
            "result" => "success",
            'items' => $this->complaintsRepo->getComplaintUsersForSearch($request->all())
        ));
    }

    protected function replyValidator(array $data)
    {
        return Validator::make($data, [
            'action_taken' => 'required_without:escalation',
            'branch_department_id_fk' => 'required_with:escalation|exists:branch_departments,branch_department_id_pk',
            'escalated_to_fk' => 'required_with:escalation|exists:users,user_id_pk',
            'remarks' => 'required_with:escalation'
        ], [
            'action_taken.required_without' => 'The action taken is required.',
            'branch_department_id_fk.required_with' => 'Select branch name.',
            'escalated_to_fk.required_with' => 'Select escalate person.',
            'branch_department_id_fk.exists' => 'Selected branch not exists.',
            'escalated_to_fk.exists' => 'Selected person not exists.',
            'remarks.required_with' => 'The remarks is required'
        ]);
    }

    public function saveComplaintReply(Request $request)
    {
        /**
         * Validate
         */
        $this->replyValidator($request->all())->validate();
        if ($request->has('escalation')) {
            $status = $this->complaintsRepo->saveEscalation($request->all());
        } else {
            $status = $this->complaintsRepo->saveReply($request->all());
        }
        $table = ($request->input('table')) ? $request->input('table') : 'INPESC';
        if ($status) {
            return json_encode(array(
                'status' => $status,
                'redirect_url' => route('dashboard.complaint.setup') . '/' . urlencode('mode=edit&id=' . $request->input('complaint_id_fk').'&pretable='.$table)
            ));
        }
    }
    
    protected function amendmentValidator(array $data)
    {
        return Validator::make($data, [
            'id' => 'required|exists:complaint_solutions,complaint_solution_id_pk',
            'amendment' => 'required'
        ], [
            'required' => 'The :attribute is required.',
            'complaint_solution_id_pk.exists' => 'Solution not exists.',
        ]);
    }
    
    public function saveSolutionAmendment(Request $request){
        /**
         * Validate
         */
        $this->amendmentValidator($request->all())->validate();
        try {
            $request->merge(['complaint_solution_id_fk' => $request->input('id')]);
            $solution = $this->manageComplaintsRepo->getSolution(array(
                'complaint_solution_id_pk' => $request->input('id')
            ));
            $status = $this->complaintsRepo->saveAmendment($request->all());
            if ($status)
                return json_encode(array(
                    'status' => $status,
                    'redirect_url' => route('dashboard.complaint.setup') . '/' . urlencode('mode=edit&id=' . $solution->complaint->complaint_id_pk)
                ));
        } catch (\Exception $e) {
            Log::error('Solution Amendment Error >> '.json_encode($e));
            abort(500);
        }
    }

    public function sendReminder($encodedURI = null, Request $request)
    {
        try {
            $params = explode('&', $encodedURI);
            if ($params) {
                $status = $this->complaintsRepo->sendReminder(array(
                    'role' => explode('=', $params[0])[1],
                    'complaint_id_pk' => explode('=', $params[1])[1],
                    'schedule' => false
                ));
                if ($status) {
                    return json_encode(array(
                        'status' => $status
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error('Reminder Error >> '.json_encode($e));
            abort(500);
        }
    }

    public function getComplaintReminder()
    {
        try {
            $complaints = $this->manageComplaintsRepo->getComplaintSchedulingIds([
//                 'open_date' => DateHelper::getDate(- 3),
                'status' => [
                    'INP',
                    'ESC',
                    'REP'
                ]
            ]);
            foreach ($complaints as $complaint) {

                $this->complaintsRepo->sendReminder(array(
                    'complaint_id_pk' => $complaint->complaint_id_pk,
                    'role' => 'ALL',
                    'schedule' => true
                ));
            }
        } catch (\Exception $e) {
            Log::error('Reminder Error >> '.json_encode($e));
        }
    }

    protected function actionValidator(array $data)
    {
        return Validator::make($data, [
            'status' => 'required',
            /**
             * Reason is needed for INP status also when reopen a complaint.
             * Need to do a workaround for that.
             */
            'reason' => 'required_if:status,==,REJ',
            'complaint_user_id_pk' => 'required_if:status,==,CLO',
            'system_role' => 'required_if:status,==,CLO'
        ], [
            'required' => 'The Remark is Required.',
            'complaint_user_id_pk' => 'The Primary Owner Required.',
            'system_role' => 'The Primary Role Required.',  
        ]);
    }

    public function statusUpdate(Request $request)
    {

        $isReporting = $this->getReportPurposeComplaintStatus(['complaint_id' => $request->input('id')]);
        
        if (!$isReporting) {
            /**
             * Validate
             */
            $this->actionValidator($request->all())->validate();
        }

        try {
            $status = $this->manageComplaintsRepo->statusUpdate($request->all());
            $table = ($request->input('table')) ? $request->input('table') : 'INPESC';
            if ($status){
                return json_encode(array(
                    'status' => $status,
                    'redirect_url' => route('dashboard.complaint.setup') . '/' . urlencode('mode=edit&id=' . $request->input('id').'&pretable='.$table)
                ));
            }
        } catch (\Exception $e) {
            Log::error('Complaint Status Update Error >> '.json_encode($e));
            abort(500);
        }
    }

    public function solutionStatusUpdate(Request $request)
    {
        /**
         * Validate
         */
        $this->actionValidator($request->all())->validate();
        try {
            $solution = $this->manageComplaintsRepo->getSolution(array(
                'complaint_solution_id_pk' => $request->input('id')
             ));
            $status = $this->manageComplaintsRepo->solutionStatusUpdate($request->all());
            $table = ($request->input('table')) ? $request->input('table') : 'INPESC';
            if ($status)
                return json_encode(array(
                    'status' => $status,
                    'redirect_url' => route('dashboard.complaint.setup') . '/' . urlencode('mode=edit&id=' . $solution->complaint->complaint_id_pk.'&pretable='.$table)
                ));
        } catch (\Exception $e) {
            Log::error('Solution Status Update Error >> '.json_encode($e));
            abort(500);
        }
    }

    public function delete($encodedURI = null, Request $request)
    {
        try {
            $id = explode('=', $encodedURI)[1];
            $status = $this->manageComplaintsRepo->delete($id);
            if ($status)
                return json_encode(array(
                    'status' => $status
                ));
        } catch (\Exception $e) {
            Log::error('Complaint Delete Error >> '.json_encode($e));
            abort(500);
        }
    }

    public function solutionDelete($encodedURI = null, Request $request)
    {
        try {
        $id = explode('=', $encodedURI)[1];
        $solution = $this->manageComplaintsRepo->getSolution(array(
            'complaint_solution_id_pk' =>  $id
        ));
        $status = $this->manageComplaintsRepo->solutionDelete($id);
        if ($status)
            return json_encode(array(
                'status' => $status,
                'redirect_url' =>  route('dashboard.complaint.setup') . '/' . urlencode('mode=edit&id=' .$solution->complaint_id_fk)
            ));
        } catch (\Exception $e) {
            Log::error('Solution Delete Error >> '.json_encode($e));
            abort(500);
        }
    }

    public function getConfigurations(Request $request){

        try {
            $areas = explode('|', config('custom.report_purpose_complaint.areas'));
            $branchDepartmentsForReports = explode('|', config('custom.report_purpose_complaint.branch_departments'));

            $configurations = [
                'report_purpose_areas' => $areas,
                'report_purpose_branch_departments' => $branchDepartmentsForReports,
            ];

            return json_encode(array(
                "status" => true,
                'configurations' => $configurations
            ));
        } catch (\Exception $e) {
            Log::error('Get Configurations Error >> '.json_encode($e));
            abort(500);
        }
    }

//     public function testMail($id)
//     {
//         // try {
//         $status = $this->complaintsRepo->testMail($id);
//         if ($status) {
//             return json_encode(array(
//                 'status' => $status
//             ));
//         }
//         // } catch ( \Exception $e ) {
//         // Log::info ( 'Mail Error >>' . json_encode ( $e ) );
//         // abort ( 404 );
//         // }
//     }
}
