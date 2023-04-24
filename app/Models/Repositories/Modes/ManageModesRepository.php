<?php

namespace App\Models\Repositories\Modes;

use DateHelper;
use App\Models\Entities\ComplaintMode;
use DataTables;

class ManageModesRepository implements ModesInterface {
	public function __construct() {
	}
	private function generateQuery($filters) {
		$query = ComplaintMode::select ( '*' );
		if (isset ( $filters ['complaintFilters'] )) {
			$query->WhereHas ( 'complaints', function ($q) use ($filters) {
				if (isset ( $filters ['complaint_id_pk'] ))
					$q->where ( 'complaint_id_pk', $filters ['complaint_id_pk'] );
			} );
		}
		$query->with ( [ 
				'complaints' 
		] );
		
		if (isset ( $filters ['complaint_mode_id_pk'] ))
			$query->where ( 'complaint_mode_id_pk', $filters ['complaint_mode_id_pk'] );
		if (isset ( $filters ['random'] ))
			$query->inRandomOrder ();
		if (isset ( $filters ['limit'] ))
			$query->limit ( $filters ['limit'] );
		return $query;
	}
	private function buildResponse($mode) {
		return $mode;
	}
	/**
	 * 
	 * @param unknown $filters
	 * @return unknown
	 */
	public function getMode($filters) {
		$queryString = $this->generateQuery ( $filters );
		$mode = $queryString->first ();
		return $this->buildResponse( $mode );
	}
	/**
	 *
	 * @param unknown $filters        	
	 * @return unknown
	 */
	public function getModes($filters) {
		$queryString = $this->generateQuery ( $filters );
		$datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($mode) {
			return $mode->complaint_mode_id_pk;
		} )->editColumn ( 'created_at', function ($mode) {
			return DateHelper::formatToDateString ( $mode->created_at );
		} )->removeColumn ( 'complaints' );
		
		return $datatable->toJson ();
	}
	public function saveOrEditMode($data) {
		if ($data ['status'] == 'REJ')
			$data ['rejected_by_fk'] = UserHelper::getLoggedUserId ();
		try {
			$filter = array ();
			if (isset ( $data ['mode'] )) {
				if ($data ['mode'] !== 'edit') {
					$filter = array (
							'name' => $data ['name'] 
					);
				} else {
					$filter = array (
							'complaint_mode_id_pk' => $data ['id'] 
					);
				}
			}
			$mode = ComplaintMode::firstOrNew ( $filter );
			$mode->fill ( $data );
			$mode->save ();
			return $mode;
		} catch ( \Exception $e ) {
			dd ( $e );
		}
	}
	public function statusModeUpdate($data) {
		if (( int ) $data ['id'] > 0)
			$modes = ComplaintMode::where ( 'complaint_mode_id_pk', $data ['id'] )->get ();
		else
		    $modes = ComplaintMode::all ();
		foreach ( $modes as $mode ) {
			if ($data ['field'] == 'status') {
				$mode->status = $data ['status'];
			}
			$mode->save ( $data );
		}
		return true;
	}
	public function modeDelete($id) {
	    $mode = ComplaintMode::withTrashed ()->where ( 'complaint_mode_id_pk', $id )->first ();
	    if ($mode->trashed ()) {
	        return true;
	    }
	    return $mode->delete ();
	}
}