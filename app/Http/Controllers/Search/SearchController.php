<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Search\ManageSearchRepository;
use App\Models\Repositories\Complaints\ManageComplaintsRepository;
use App\Models\Entities\User;
use UserHelper;
use RoleHelper;
use Log;
use Auth;
use DB;

class SearchController extends Controller
{

    protected $manageSearchRepo;
    protected $manageComplaintsRepo;

    public function __construct()
    {

        $this->manageSearchRepo = new ManageSearchRepository();
        $this->manageComplaintsRepo = new ManageComplaintsRepository();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = 'CMPLA';
        return view('dashboard.search.home', [
            'type' => $type == 'CMPLA' ? 'complaints' : 'compliments',
            'typeCode' => $type ?? 'CMPLA'
        ]);

    }

    /**
     * Get search results
     *
     * @return \Illuminate\Http\Response
     */
    public function getResults(Request $request)
    {
        // try {
            return $this->manageSearchRepo->getComplaints($request->all());
        // } catch (\Exception $e) {
            // Log::error('Get Search Error >>' . json_encode($e));
            // abort(404);
        // }
    }
    
    /**
     * Get search filters data
     *
     * @return \Illuminate\Http\Response
     */
    public function getSearchFilter(Request $request) {
        
        return json_encode(array(
            "result" => "success",
            'items' => $this->manageSearchRepo->getFilterForSearch($request->all())
        ));
        
    }
    
    /**
     * show complaint details
     *
     * @return \Illuminate\Http\Response
     */
    public function complaintDisplay($encodedURI = null, Request $request) {
//        dd($encodedURI);exit;
//        try {
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
                ]);//dd($complaint);
                if ($complaint) {
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

                        $viewParams = array(
                            'mode' => 'edit',
                            'complaint' => $complaint,
                            'complaint_rejections' => $complaintRejections
                        );
                        if(isset($params[2]))
                            $viewParams+=array(
                                'subMode' =>'solutionEdit'
                            );
                        return view('dashboard.search.complaint', $viewParams);
                    } else {
                        abort(404);
                    }
                } else {
                    abort(404);
                }
                
            } else {
                return view('dashboard.search.complaint', [
                    'mode' => 'new',
                    'complaint' => collect(),
                    'complaint_rejections' => collect()
                ]);
            }
            
//        } catch ( \Exception $e ) {
//            Log::error('Complaint Page Error >>' . json_encode($e));
//            abort ( 404 );
//        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
