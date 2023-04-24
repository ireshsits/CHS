<?php

namespace App\Http\Controllers\Complaints;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Complaints\EscalateRepository;

class EscalateController extends Controller {
	public function __construct() {
		$this->escalateRepo = new EscalateRepository ();
	}
// 	public function getEscalateToById($encodedURI = null, Request $request) {
// 		try {
// 			$id = explode ( '=', $encodedURI ) [1];
// 			return json_encode ( array (
// 					"result" => "success",
// 					'items' => $this->escalateRepo->getForSearch ( [ 
// 							'branch_department_id_pk' => $id 
// 					] ) 
// 			) );
// 		} catch ( \Exception $e ) {
// 			abort ( 404 );
// 		}
// 	}
	public function statusUpdate(Request $request) {
// 		try {
			$status = $this->escalateRepo->statusUpdate ( $request->all () );
			if ($status)
				return json_encode ( array (
						'status' => $status 
				) );
// 		} catch ( \Exception $e ) {
// 			abort ( 500 );
// 		}
	}
}
