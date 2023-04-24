<?php

namespace App\Http\Controllers\Regions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Regions\RegionsInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegionRequest;

class ManageRegionsController extends Controller {
    protected $manageRegions;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RegionsInterface $interface) {
        $this->manageRegions = $interface;
    }
    
    /**
     * Show the application author manage page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            return view ( 'dashboard.regions.manage' );
        } catch ( \Exception $e ) {
            abort ( 404 );
        }
    }
    public function getRegionsForSearch(Request $request)
    {
        return json_encode(array(
            "result" => "success",
            'items' => $this->manageRegions->getRegionForSearch($request->all())
        ));
    }
    public function getRegion($encodedURI = null, Request $request) {
        try {
            $id = explode ( '=', $encodedURI ) [1];
            return $this->manageRegions->getRegion ( [
                'region_id_pk' => $id
            ] );
        } catch ( \Exception $e ) {
            abort ( 404 );
        }
    }
    public function getRegions(Request $request) {
        try {
            return $this->manageRegions->getRegions ( $request->all() );
        } catch ( \Exception $e ) {
            abort ( 404 );
        }
    }
    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
//     protected function validator(array $data) {
//         return Validator::make ( $data, [
//             'zone_id_fk' => 'required|exists:zones,zone_id_pk',
//             'name' => 'required',
//             'number' => 'required|unique:App\Models\Entities\Region',
// //             'solIds' => 'required',
//             'manager_id_fk' => 'required|exists:users,user_id_pk',
//             'status' => 'required'
            
//         ], [
//             'required' => 'The :attribute is required.',
// //             'solIds' => 'branch Sol ids required',
//             'zone_id_fk.required' => 'Select zone.',
//             'zone_id_fk.exists' => 'Selected zone not exists.',
//             'manager_id_fk.required' => 'The manager is required',
//             'manager_id_fk.exists' => 'Selected user not exists.',
//             'number.unique' => 'Number already taken.'
//         ] );
//     }
    public function statusRegionsUpdate(Request $request) {
        // try {
            $status = $this->manageRegions->statusRegionsUpdate ( $request->all () );
            if ($status)
                return json_encode ( array (
                    'status' => $status
                ) );
        // } catch ( \Exception $e ) {
        //     abort ( 500 );
        // }
    }
    public function saveRegion(RegionRequest $request) {
        /**
         * Validate
         */
//         $this->validator ( $request->all () )->validate ();
        $region = $this->manageRegions->saveOrEditRegion ( $request->all () );
        if($region) {
            return json_encode ( array (
                'status' => true,
                'redirect_url' => route ( 'dashboard.region.setup').'/'.urlencode('mode=edit&id='.$region->region_id_pk)
            ) );
        }
    }
    public function deleteRegion($encodedURI = null, Request $request) {
        try {
            $id = explode ( '=', $encodedURI ) [1];
            $status = $this->manageRegions->regionDelete ( $id );
            if ($status)
                return json_encode ( array (
                    'status' => $status
                ) );
        } catch ( \Exception $e ) {
            abort ( 500 );
        }
    }
}
