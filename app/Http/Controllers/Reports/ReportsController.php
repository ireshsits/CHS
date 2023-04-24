<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Complaints\ManageComplaintsRepository;
use App\Models\Repositories\Reports\ManageReportsRepository;
use Log;
use App\Models\Entities\Setting;
use App\Models\Entities\Category;

class ReportsController extends Controller {
	protected $manageComplaintsRepo;
	protected $manageReportsRepo;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->manageComplaintsRepo = new ManageComplaintsRepository ();
		$this->manageReportsRepo = new ManageReportsRepository ();
	}
	
	/**
	 * Show the application author manage page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			return view ( 'dashboard.reports.manage', array(
			    'category_levels' => Setting::getCategorySettings()->CATEGORY_LEVELS
			) );
		} catch ( \Exception $e ) {
			abort ( 404 );
		}
	}
	public function getReports(Request $request) {
// 		try {
            
	    return $this->manageReportsRepo->getReportTable ($this->buildRequestArray($request->all ()) );
// 		} catch ( \Exception $e ) {
// 		    Log::info ( 'Get Report Error >>' . json_encode ( $e ) );
// 			abort ( 404 );
// 		}
	}
	public function exportReports(Request $request) {
// 		try {
			if ($request->has ( 'mode' )) {
				if ($request->input ( 'mode' ) == 'PDF')
				    return $this->manageReportsRepo->exportPDF ($this->buildRequestArray($request->input ( 'filters' )) );
				else if ($request->input ( 'mode' ) == 'EXCEL')
				    return $this->manageReportsRepo->exportExcel ($this->buildRequestArray($request->input ( 'filters' )) );
			}
// 		} catch ( \Exception $e ) {
// 			Log::info ( 'Report Export Error >>' . json_encode ( $e ) );
// 			abort ( 500 );
// 		}
	}
	private function buildRequestArray($filters){
	    if(isset($filters['category_id_fk'])){
	        $filters['category_id_fk'] = array_filter($filters['category_id_fk']);
	        if(!empty($filters['category_id_fk'])){
	            $filters['category_filter_level'] = min(array_keys($filters['category_id_fk']));
	            $filters['category_id_fk'] = $filters['category_id_fk'][$filters['category_filter_level']];
	        }else
	            unset($filters['category_id_fk']);
	    }
	    return $filters;
	}
}
