<?php

namespace App\Http\Controllers\Zones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Zones\ManageZonesRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ZoneRequest;

class ManageZonesController extends Controller {
protected $manageZonesRepo;
/**
 * Create a new controller instance.
 *
 * @return void
 */
public function __construct() {
    $this->manageZonesRepo = new ManageZonesRepository ();
}

/**
 * Show the application author manage page.
 *
 * @return \Illuminate\Http\Response
 */
public function index() {
    try {
        return view ( 'dashboard.zones.manage' );
    } catch ( \Exception $e ) {
        abort ( 404 );
    }
}
public function getZone($encodedURI = null, Request $request) {
    try {
        $id = explode ( '=', $encodedURI ) [1];
        return $this->manageZonesRepo->getZone ( [
            'zone_id_pk' => $id
        ] );
    } catch ( \Exception $e ) {
        abort ( 404 );
    }
}
public function getZonesForSearch(Request $request) {
    return json_encode ( array (
        "result" => "success",
        'items' => $this->manageZonesRepo->getZonesForSearch( $request->all () )
    ) );
}
public function getZones(Request $request) {
    try {
        return $this->manageZonesRepo->getZones ( [ ] );
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
// protected function validator(array $data) {
//     return Validator::make ( $data, [
//         'name' => 'required',
//         'number' => 'required|unique:App\Models\Entities\Zone',
//         'manager_id_fk' => 'required|exists:users,user_id_pk',
//         'status' => 'required'
//     ], [
//         'required' => 'The :attribute is required.',
//         'manager_id_fk.required' => 'The manager is required',
//         'manager_id_fk.exists' => 'Selected user not exists.',
//         'number.unique' => 'Number already taken.'
//     ] );
// }
public function statusZonesUpdate(Request $request) {
    try {
        $status = $this->manageZonesRepo->statusZonesUpdate ( $request->all () );
        if ($status)
            return json_encode ( array (
                'status' => $status
            ) );
    } catch ( \Exception $e ) {
        abort ( 500 );
    }
}
public function saveZone(ZoneRequest $request) {
    /**
     * Validate
     */
//     $this->validator ( $request->all () )->validate ();
    $zone = $this->manageZonesRepo->saveOrEditZone ( $request->all () );
    if($zone) {
        return json_encode ( array (
            'status' => true,
            'redirect_url' => route ( 'dashboard.zone.setup').'/'.urlencode('mode=edit&id='.$zone->zone_id_pk)
        ) );
    }
}
public function deleteZone($encodedURI = null, Request $request) {
//     try {
        $id = explode ( '=', $encodedURI ) [1];
        $status = $this->manageZonesRepo->zoneDelete ( $id );
        if ($status)
            return json_encode ( array (
                'status' => $status
            ) );
//     } catch ( \Exception $e ) {
//         abort ( 500 );
//     }
}
}
