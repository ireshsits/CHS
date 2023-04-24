<?php

namespace App\Models\Repositories\Analyses;

use App\Models\Entities\AnalysisCategory;
use App\Classes\Analyses\Analysis;
use DataTables;
use App\Exports\AnalysisExport;
// use Excel;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnalysesRepository implements AnalysesInterface {
	protected $analysis;
	public function __construct() {
		$this->analysis = new Analysis ();
	}
	public function fetchAnalysesForSearch($filters) {
		return AnalysisCategory::WhereHas ( 'analyses', function ($q) use ($filters) {
			$q->where ( 'status', 'ACT' );
		} )
		->with ( [ 
				'analyses' => function ($q){
					$q->where('status','ACT');
				}
		] )->where ( 'status', 'ACT' )->get ();
	}
	public function fetchForTable($filters) {
		$complaints = $this->analysis->getAnalysis ( $filters );
		return DataTables::of ( $complaints ['tableDataSet']??[] )->toJson ();
	}
	public function fetchForChart($filters) {
		$complaints = $this->analysis->getAnalysis ( $filters );
		return array (
				'chartDataSet' => $complaints ['chartDataSet']??[],
				'chartLabels' => $complaints ['chartLabels']??[],
				'chartColors' => $complaints ['chartColors']??[] 
		);
	}
	public function exportExcel($filters) {
		$complaints = $this->analysis->getAnalysis ( $filters );
		return (new AnalysisExport($complaints, $filters))->download($filters['code'].'-'.$filters['year'].'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
	}
	public function exportPDF($filters) {
		$complaints = $this->analysis->getAnalysis ( $filters );
		return (new AnalysisExport($complaints, $filters))->download($filters['code'].'-'.$filters['year'].'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
	}
}