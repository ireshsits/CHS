<?php
namespace App\Models\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
	public function register()
	{
		//Analyses repository
		$this->app->bind(
			'App\Models\Repositories\Analyses\AnalysesInterface',
			'App\Models\Repositories\Analyses\AnalysesRepository'
		);
		
		//Area
		$this->app->bind(
		    'App\Models\Repositories\Areas\AreasInterface',
		    'App\Models\Repositories\Areas\ManageAreaRepository'
		);
		
		//Area
		$this->app->bind(
		    'App\Models\Repositories\Auth\AuthInterface',
		    'App\Models\Repositories\Auth\AuthRepository'
		);
		
		//Category
		$this->app->bind(
		    'App\Models\Repositories\Categories\CategoryInterface',
		    'App\Models\Repositories\Categories\ManageCategoriesRepository'
		);
		
		//Dashboard
		$this->app->bind(
		    'App\Models\Repositories\Dashboard\DashboardInterface',
		    'App\Models\Repositories\Dashboard\DashboardRepository'
		);
		
		//Modes
		$this->app->bind(
		    'App\Models\Repositories\Modes\ModesInterface',
		    'App\Models\Repositories\Modes\ManageModesRepository'
		);
		
		//Region
		$this->app->bind(
		    'App\Models\Repositories\Regions\RegionsInterface',
		    'App\Models\Repositories\Regions\ManageRegionsRepository'
		);
		
		//Report
		$this->app->bind(
		    'App\Models\Repositories\Reports\ReportInterface',
		    'App\Models\Repositories\Reports\ManageReportsRepository'
		);
		
		//Zone
		$this->app->bind(
		    'App\Models\Repositories\Zones\ZonesInterface',
		    'App\Models\Repositories\Zones\ManageZonesRepository'
		);
	}
}
