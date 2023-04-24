<?php

namespace App\Models\Repositories\Dashboard;

interface DashboardInterface {
    
    public function getMonthlyComplaintStats($params);
    public function getAnnualComplaintStats($params);
    
}