<?php

namespace App\Http\Controllers\Complaints;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Complaints\ComplainantsRepository;

class ComplainantsController extends Controller {
	protected $complainantRepo;
	public function __construct() {
		$this->complainantRepo = new ComplainantsRepository ();
	}
	public function getComplainantsForSearch(Request $request) {
		return json_encode ( array (
				"result" => "success",
				'items' => $this->complainantRepo->getForSearch ( $request->all () ) 
		) );
	}
	public function getComplainant($encodedURI = null, Request $request) {
		try {
			$id = explode ( '=', $encodedURI ) [1];
			return $this->complainantRepo->getComplainant ( [ 
					'complainant_id_pk' => $id 
			] );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getComplainantByNIC($encodedURI = null, Request $request) {
		try {
			$nic = explode ( '=', $encodedURI ) [1];
			return $this->complainantRepo->getComplainant ( [ 
					'nic' => $nic 
			] );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
}
