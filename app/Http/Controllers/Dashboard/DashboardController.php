<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\Dashboard\DashboardInterface;
use App\Models\Entities\SystemRole;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
	protected $dashboard;
    
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(DashboardInterface $interface) {
	    $this->dashboard = $interface;
	}
	
    public function index(){
    	return view('dashboard.home.home');
    }
    
    public function getComplaintChartData(Request $request) {
    	return $this->dashboard->getData( $request->all());
    }
    
    public function getMonthlyComplaintStats(Request $request){
    	return $this->dashboard->getMonthlyComplaintStats( $request->all());
    }
    
    public function getAnnualComplaintStats(Request $request){
    	return $this->dashboard->getAnnualComplaintStats( $request->all());
    }
    public function redirectFromRoot(Request $request){
    	return redirect ( '/dashboard/home' );
    }
}


