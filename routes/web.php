<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Route::group(['middleware' => ['web','PreventBackHistory']], function () {
    Route::group(['middleware' => ['web']], function () {
	
	Route::get('/send_reminders','Complaints\ComplaintsController@getComplaintReminder');
// 	Route::get('/test_mail/{id}','Complaints\ComplaintsController@testMail');
//    Route::get('/test_UPM', 'Sync\SyncController@getInitialUPMSync');
	
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@authenticate')->name('login.post');
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// 	Auth::routes();
	
// 	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// 	Route::post('login', 'Auth\LoginController@authenticate')->name('login.post');
	
	Route::group(['middleware' => 'auth'], function () {
		Route::group(['prefix' => 'dashboard','as' => 'dashboard.'], function () {
			
		    Route::group(['middleware' => 'PreventBackHistory'], function () {
    			Route::group(['prefix' => 'complaints','as' => 'complaint.'], function () {
    				Route::get('/setup/{encodedURI}', 'Complaints\ComplaintsController@index')->name('setup');
    				Route::get('/setup', 'Complaints\ComplaintsController@index')->name('setup')->middleware('ComplaintEntryMiddleware');
    				Route::get('/modes/search/all', 'Complaints\ComplaintsController@complaintModesForSearch')->name('search.mode');
    				Route::get('/manage/{type}/{table?}', 'Complaints\ComplaintsController@manage')->name('manage');
    				Route::get('/getById/{encodedURI}', 'Complaints\ComplaintsController@getComplaint')->name('get');
    				Route::post('/get/users/all', 'Complaints\ComplaintsController@getComplaintUsers')->name('get.users.all');
    				Route::post('/get/all', 'Complaints\ComplaintsController@getComplaints')->name('get.all');
    				Route::get('/send_reminder/{encodedURI}', 'Complaints\ComplaintsController@sendReminder')->name('send.reminder');
    				Route::get('get/enum_values/{field}', 'Complaints\ComplaintsController@getEnumValues')->name('enum.get');
    				Route::post('/save_complaint', 'Complaints\ComplaintsController@saveComplaint')->name('post');
    				Route::post('/save_reply', 'Complaints\ComplaintsController@saveComplaintReply')->name('post');
    				Route::put('/status', 'Complaints\ComplaintsController@statusUpdate')->name('update.status');
    				Route::delete('/delete/{encodedURI}', 'Complaints\ComplaintsController@delete')->name('delete');
					Route::get('/get/configurations', 'Complaints\ComplaintsController@getConfigurations')->name('get.configurations');
    				
    				Route::group(['prefix' => 'solutions','as' => 'solution.'], function () {
    				    Route::get('/getById/{encodedURI}', 'Complaints\ComplaintsController@getComplaintSolution')->name('get');
    					Route::delete('/delete/{encodedURI}', 'Complaints\ComplaintsController@solutionDelete')->name('solution.delete');
    					Route::post('/amendment','Complaints\ComplaintsController@saveSolutionAmendment')->name('solution.amendment');
    					Route::put('/status','Complaints\ComplaintsController@solutionStatusUpdate')->name('solution.update');
    					Route::put('/edit','Complaints\ComplaintsController@solutionEdit')->name('solution.edit');
    				});
    				Route::group(['prefix' => 'escalations','as' => 'escalate.'], function () {
    // 					Route::get('/getEscalateToById/{encodedURI}', 'Complaints\EscalateController@getEscalateToById')->name('get.all');
    					Route::put('/status', 'Complaints\EscalateController@statusUpdate')->name('update.status');
    				});
    			});
    			Route::group(['prefix' => 'complainants', 'as' => 'complainant.'], function() {
    				Route::get('/getByNIC/{encodedURI}', 'Complaints\ComplainantsController@getComplainantByNIC')->name('get.nic');
    				Route::get('/getById/{encodedURI}', 'Complaints\ComplainantsController@getComplainant')->name('get.id');
    				Route::post('/get/all', 'Complaints\ComplainantsController@getComplainantsForSearch')->name('search.get');
    			});

				Route::group(['prefix' => 'search', 'as' => 'search.'], function() {
					Route::get('/', 'Search\SearchController@index')->name('home');
					Route::post('/get/results', 'Search\SearchController@getResults')->name('get.results');
					Route::post('/get/filter', 'Search\SearchController@getSearchFilter')->name('get.filter');
					Route::get('/complaint/{encodedURI}', 'Search\SearchController@complaintDisplay')->name('get.complaint');
				});

    			Route::group(['prefix' => 'branches', 'as' => 'branch.'], function() {
    			    Route::get('/users/sync/{encodedURI}', 'BranchDepartments\BranchDepartmentsController@userSync')->name('user.sync');
    				Route::get('/getById/{encodedURI}', 'BranchDepartments\BranchDepartmentsController@getBranchDepartment')->name('get');
    				Route::post('/get/users/all','BranchDepartments\BranchDepartmentsController@getBranchDepartmentUsers')->name('get.users.all');
    				Route::post('/get/map/search/all', 'BranchDepartments\BranchDepartmentsController@getBranchesForRegionMapSearch')->name('map.search.get');
    				Route::post('/get/search/all', 'BranchDepartments\BranchDepartmentsController@getBranchesForSearch')->name('search.get');
    				Route::post('/get/all', 'BranchDepartments\BranchDepartmentsController@getBranchDepartments')->name('get.all');
    			});
    			Route::group(['prefix' => 'categories', 'as' => 'category.'], function() {
    			    Route::get('/getById/{encodedURI}', 'Categories\ManageCategoriesController@getCategory')->name('get');
    			    Route::post('/get/search/all/{level}', 'Categories\CategoryController@getCategoriesForSearch')->name('search.get');
    				Route::post('/get/search/all', 'Categories\CategoryController@getCategoriesForSearch')->name('search.get');
    				Route::post('/get/all', 'Categories\ManageCategoriesController@getCategories')->name('get.all');
    				Route::get('/manage', 'Categories\ManageCategoriesController@index')->name('manage')->middleware('SiteManagement');
    				Route::post('/save', 'Categories\ManageCategoriesController@saveCategory')->name('post')->middleware('SiteManagement');
    				Route::put('/status', 'Categories\ManageCategoriesController@statusCategoryUpdate')->name('update.status')->middleware('SiteManagement');
    				Route::delete('/delete/{encodedURI}', 'Categories\ManageCategoriesController@deleteCategory')->name('delete')->middleware('SiteManagement');
    			});
    			/**
    			 * Removed in CR3
    			 */
//     			Route::group(['prefix' => 'sub_categories', 'as' => 'subcategory.'], function() {
//     				Route::get('/getById/{encodedURI}', 'Categories\ManageSubCategoriesController@getSubCategory')->name('get');
//     				Route::post('/get/search/all', 'Categories\CategoryController@getSubCategoriesForSearch')->name('search.get');
//     				Route::post('/get/all', 'Categories\ManageSubCategoriesController@getSubCategories')->name('get.all');
//     				Route::get('/manage', 'Categories\ManageSubCategoriesController@index')->name('manage')->middleware('SiteManagement');
//     				Route::post('/save', 'Categories\ManageSubCategoriesController@saveSubCategory')->name('post')->middleware('SiteManagement');
//     				Route::put('/status', 'Categories\ManageSubCategoriesController@statusSubCategoryUpdate')->name('update.status')->middleware('SiteManagement');
//     				Route::delete('/delete/{encodedURI}', 'Categories\ManageSubCategoriesController@deleteSubCategory')->name('delete')->middleware('SiteManagement');
//     			});
    			Route::group(['prefix' => 'modes', 'as' => 'mode.'], function() {
    				Route::get('/getById/{encodedURI}', 'Modes\ManageModesController@getMode')->name('get');
    // 				Route::post('/get/search/all', 'Categories\CategoryController@getSubCategoriesForSearch')->name('search.get');
    // 				Route::post('/get/search/area/all', 'Categories\CategoryController@getAreaForSearch')->name('search.area');
    				Route::post('/get/all', 'Modes\ManageModesController@getModes')->name('get.all');
    				Route::get('/manage', 'Modes\ManageModesController@index')->name('manage')->middleware('SiteManagement');
    				Route::post('/save', 'Modes\ManageModesController@saveMode')->name('post')->middleware('SiteManagement');
    				Route::put('/status', 'Modes\ManageModesController@statusModeUpdate')->name('update.status')->middleware('SiteManagement');
    				Route::delete('/delete/{encodedURI}', 'Modes\ManageModesController@deleteMode')->name('delete');
    			});
    		    Route::group(['prefix' => 'zones', 'as' => 'zone.'], function() {
    		        Route::get('/setup/{encodedURI}', 'Zones\ZoneController@index')->name('setup')->middleware('SiteManagement');
    		        Route::get('/setup', 'Zones\ZoneController@index')->name('setup')->middleware('SiteManagement');
    		        Route::get('/getById/{encodedURI}', 'Zones\ManageZonesController@getZone')->name('get');
    		        Route::post('/get/search/all', 'Zones\ManageZonesController@getZonesForSearch')->name('search.get');
    		        Route::post('/get/all', 'Zones\ManageZonesController@getZones')->name('get.all');
    		        Route::post('/sync_region', 'Zones\ZoneController@syncRegion')->name('region.sync')->middleware('SiteManagement');
    		        Route::get('/manage', 'Zones\ManageZonesController@index')->name('manage')->middleware('SiteManagement');
    		        Route::post('/save', 'Zones\ManageZonesController@saveZone')->name('post')->middleware('SiteManagement');
    		        Route::put('/status', 'Zones\ManageZonesController@statusZonesUpdate')->name('update.status')->middleware('SiteManagement');
    		        Route::delete('/delete/{encodedURI}', 'Zones\ManageZonesController@deleteZone')->name('delete')->middleware('SiteManagement');
    		    });
    		    Route::group(['prefix' => 'regions', 'as' => 'region.'], function() {		        
    		        Route::get('/setup/{encodedURI}', 'Regions\RegionController@index')->name('setup')->middleware('SiteManagement');
    		        Route::get('/setup', 'Regions\RegionController@index')->name('setup')->middleware('SiteManagement');
    	            Route::get('/getById/{encodedURI}', 'Regions\ManageRegionsController@getRegion')->name('get');
    	            Route::post('/get/search/all', 'Regions\ManageRegionsController@getRegionsForSearch')->name('search.get');
    	            Route::post('/get/all', 'Regions\ManageRegionsController@getRegions')->name('get.all');
    	            Route::post('/sync_branch', 'Regions\RegionController@syncBranch')->name('branch.sync')->middleware('SiteManagement');
    	            Route::get('/manage', 'Regions\ManageRegionsController@index')->name('manage')->middleware('SiteManagement');
    	            Route::post('/save', 'Regions\ManageRegionsController@saveRegion')->name('post')->middleware('SiteManagement');
    	            Route::put('/status', 'Regions\ManageRegionsController@statusRegionsUpdate')->name('update.status')->middleware('SiteManagement');
    	            Route::delete('/delete/{encodedURI}', 'Regions\ManageRegionsController@deleteRegion')->name('delete')->middleware('SiteManagement');
    	        });
		        Route::group(['prefix' => 'notifications', 'as' => 'notification.'], function(){
		            Route::get('/all', 'Notifications\NotificationsController@notifications')->name('get.all');
		            Route::get('/read/{id}', 'Notifications\NotificationsController@notificationRead')->name('read')->middleware('MarkNotificationAsReadMiddleware');
		            Route::post('/push','Notifications\PushController@store')->name('push.store');
		        });
	            Route::group(['prefix' => 'users', 'as' => 'user.'], function(){
	                Route::post('/get/search/all', 'Users\UserController@getUsersForManagerSearch')->name('search.get');
					Route::post('/get/all', 'Users\UserController@getAllUsers')->name('get.all');
	            });
	            Route::group(['prefix' => 'areas', 'as' => 'area.'], function() {
	                Route::post('/get/search/all', 'Areas\AreaController@getAreaForSearch')->name('search.get');
                });
                Route::group(['middleware' => 'SiteManagement'], function () {
                    Route::group(['prefix' => 'configurations', 'as' => 'config.'], function(){
                        Route::post('/update', 'Configurations\ConfigController@updateConfigurations')->name('update');
                        Route::get('/manage', 'Configurations\ConfigController@index')->name('manage');
                    });
                });
		    });
            Route::group(['middleware' => 'ReportsManagement'], function () {
                Route::group(['prefix' => 'reports', 'as' => 'report.'], function() {
                    Route::get('/manage', 'Reports\ReportsController@index')->name('manage')->middleware('PreventBackHistory');
                    Route::post('/get/all', 'Reports\ReportsController@getReports')->name('get.all')->middleware('PreventBackHistory');
                    Route::get('/export','Reports\ReportsController@exportReports')->name('export');
                });
                Route::group(['prefix' => 'analysis', 'as' => 'analysis.'], function() {
                    Route::get('/manage', 'Analysis\AnalysisController@index')->name('manage')->middleware('PreventBackHistory');
                    Route::get('/table_headers/{encodedURI}','Analysis\AnalysisController@getTableHeaders')->name('table.headers')->middleware('PreventBackHistory');
                    Route::post('/get/table','Analysis\AnalysisController@getTableView')->name('table.view')->middleware('PreventBackHistory');
                    Route::post('/get/chart','Analysis\AnalysisController@getChartView')->name('chart.view')->middleware('PreventBackHistory');
                    Route::get('/export','Analysis\AnalysisController@exportAnalyses')->name('export');
                });
            });
			Route::post('/getMonthlyCharts', 'Dashboard\DashboardController@getMonthlyComplaintStats')->name('complaint.month.charts');
			Route::post('/getAnnualCharts', 'Dashboard\DashboardController@getAnnualComplaintStats')->name('complaint.annual.charts');
			Route::get('/home', 'Dashboard\DashboardController@index')->name('home')->middleware('PreventBackHistory');
		
		
			Route::group(['prefix' => 'errors', 'as' => 'error.'], function(){
			    Route::get('/{type}', 'Errors\ErrorController@index')->name('load');
			});
		});
		
	});

	Route::get('/', 'Dashboard\DashboardController@redirectFromRoot');
});
