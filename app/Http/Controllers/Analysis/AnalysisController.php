<?php

namespace App\Http\Controllers\Analysis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Analyses\AnalysesInterface;
use Illuminate\Support\Facades\Validator;
use TableHeaderHelper;
use Log;

class AnalysisController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	protected $analyses;
	public function __construct(AnalysesInterface $interface) {
	    $this->analyses = $interface;
	}
	
	/**
	 * Show the application author manage page.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		try {
			return view ( 'dashboard.analysis.manage', [ 
					'analysesCategories' => $this->analyses->fetchAnalysesForSearch ( [ ] ) 
			] );
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
				'code' => 'required',
				'year' => 'required',
				'status' => 'required',
				'area_id_fk' => 'required_if:code,==,AWDA',
				'category_id_fk' => 'required_if:code,==,CWDA' 
		], [ 
				'required' => 'The :attribute is required.',
				'required.area_id_fk' => 'The area is required.',
				'required.category_id_fk' => 'The category is required.' 
		] );
	}
	public function getTableHeaders($encodedURI = null, Request $request) {
// 		try {
			$params = explode ( '&', $encodedURI );
			$columns = TableHeaderHelper::getHeadersAndColumns ( array (
					'year' => explode ( '=', $params [0] ) [1],
					'code' => explode ( '=', $params [1] ) [1] 
			) );
			$component = view ( 'dashboard.analysis.table_component', [ 
					'headers' => $columns 
			] )->render ();
			return response ()->json ( [ 
					'status' => 'success',
					'component' => $component,
					'columns' => $columns 
			] );
// 		} catch ( \Exception $e ) {
// 		    Log::info("Analysis get table headers error >> ".json_encode ( $e ) );
// 			abort ( 500 );
// 		}
	}
	public function getTableView(Request $request) {
// 		try {
			$this->validator ( $request->all () )->validate ();
			return $this->analyses->fetchForTable ( $request->all () );
// 		} catch ( \Exception $e ) {
// 		    Log::info("Analysis get table view error >> ".json_encode ( $e ) );
// 			abort ( 500 );
// 		}
	}
	public function getChartView(Request $request) {
// 		try {
			$this->validator ( $request->all () )->validate ();
			return $this->analyses->fetchForChart ( $request->all () );
// 		} catch ( \Exception $e ) {
// 		    Log::info("Analysis get chart view error >> ".json_encode ( $e ) );
// 			abort ( 500 );
// 		}
	}
	public function exportAnalyses(Request $request) {
// 		try {
			if ($request->has ( 'mode' )) {
				if ($request->input ( 'mode' ) == 'PDF')
					return $this->analyses->exportPDF ( $request->input ( 'filters' ) );
				else if ($request->input ( 'mode' ) == 'EXCEL')
					return $this->analyses->exportExcel ( $request->input ( 'filters' ) );
			}
// 		} catch ( \Exception $e ) {
// 			Log::info ( 'Anlyses Export Error >>' . json_encode ( $e ) );
// 			abort ( 500 );
// 		}
	}
	
	// public function forgetWildcard(string $pattern ='*', int $maxCountValue = 1000)
	// {
	// $redis = Cache::getRedis();
	// $currentCursor = '1';
	// do {
	// $response = $redis->scan($currentCursor, 'MATCH', $pattern, 'COUNT', $maxCountValue);
	// dump($response);
	// $currentCursor = $response[0];
	// $keys = $response[1];
	// if (count($keys) > 0) {
	// // remove all found keys
	// // $redis->del($keys);
	// }
	// } while ($currentCursor !== '0'); // finish if current Cursor is reaching '0'
	// }
}
