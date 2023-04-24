<?php

namespace App\Models\Repositories\Analyses;

interface AnalysesInterface {
    
    public function fetchAnalysesForSearch($filters);
    public function fetchForTable($filters);
    public function fetchForChart($filters);
    public function exportExcel($filters);
    public function exportPDF($filters);
    
}