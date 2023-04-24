<?php

namespace App\Models\Repositories\Reports;

interface ReportsInterface {
    
    public function getReportTable($filters);
    public function getReports(array $filters);
    public function exportExcel($data);
    public function exportPDF($data);
    
}