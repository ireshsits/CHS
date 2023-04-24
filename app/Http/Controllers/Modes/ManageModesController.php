<?php

namespace App\Http\Controllers\Modes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Modes\ModesInterface;
use Illuminate\Support\Facades\Validator;

class ManageModesController extends Controller {
	protected $manageMode;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(ModesInterface $interface) {
	    $this->manageMode = $interface;
	}
	
	/**
	 * Show the application author manage page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			return view ( 'dashboard.modes.manage' );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}	
	public function getMode($encodedURI = null, Request $request) {
		try {
			$id = explode ( '=', $encodedURI ) [1];
			return $this->manageMode->getMode ( [
					'complaint_mode_id_pk' => $id
			] );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getModes(Request $request) {
		try {
		    return $this->manageMode->getModes ( [ ] );
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
	protected function validator(array $data) {
		return Validator::make ( $data, [ 
				'name' => 'required',
		        'code' => 'required',
				'status' => 'required',
				'color' => 'required' 
		], [ 
				'required' => 'The :attribute is required.' 
		] );
	}
	public function saveMode(Request $request){
		/**
		 * Validate
		 */
		$this->validator ( $request->all () )->validate ();
		return $this->manageMode->saveOrEditMode ( $request->all () );
	}
	public function statusModeUpdate(Request $request) {
		try {
		    $status = $this->manageMode->statusModeUpdate ( $request->all () );
			if ($status)
				return json_encode ( array (
						'status' => $status 
				) );
		} catch ( \Exception $e ) {
			abort ( 500 );
		}
	}
	public function deleteMode($encodedURI = null, Request $request) {
	    try {
	        $id = explode ( '=', $encodedURI ) [1];
	        $status = $this->manageMode->modeDelete ( $id );
	        if ($status)
	            return json_encode ( array (
	                'status' => $status
	            ) );
	    } catch ( \Exception $e ) {
	        abort ( 500 );
	    }
	}
}
