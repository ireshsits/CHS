<?php

namespace App\Models\Repositories\Complaints;

use DateHelper;
use App\Models\Entities\Complainant;
use Log;
use DB;

class ComplainantsRepository {
	public function __construct() {
	}
	
	private function generateSearchQuery($data) {
		$query = Complainant::selectRaw ( '*' )->
		// DB::raw ( "complainant_id_pk AS id" ),
		// DB::raw ( "CONCAT(first_name,' ',last_name) AS text" ) )
		where ( 'nic', 'like', '%' . $data ['name'] . '%' )->distinct ();
		return $query;
	}
	private function generateQuery($params) {
		$query = Complainant::selectRaw ( '*' );
		if (isset ( $params ['complainant_id_pk'] ))
			$query->where ( 'complainant_id_pk', $params ['complainant_id_pk'] );
		if (isset ( $params ['nic'] ))
			$query->where ( 'nic', $params ['nic'] );
		return $query;
	}
	private function buildSearchResponse($complainant) {
		$complainant->id = $complainant->nic;
		$complainant->text = $complainant->nic;
		$complainant->display_complainant = true;
		return $complainant;
	}
	private function buildResponse($complainant){
		return $complainant;
	}
	public function getForSearch(array $filters) {
		$queryString = $this->generateSearchQuery ( $filters );
		$complainants = $queryString->get ();
		foreach ( $complainants as $comkey => $complainant ) {
			$complainant = $this->buildSearchResponse ( $complainant );
		}
		return $complainants;
	}
	public function getComplainant(array $filters) {
		$queryString = $this->generateQuery ( $filters );
		$complainant = $queryString->first ();
		return $this->buildResponse ( $complainant );
	}
}