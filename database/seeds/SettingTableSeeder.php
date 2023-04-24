<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Setting;
class SettingsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
	    Setting::truncate();
		$settings = array (
				/**
				 * index 0 = | Y = Send Notifications true 						N = Not		| NOTIFICATIONS
				 *
				 * String explode index based each notification active status
				 *
				 * index 1 = | 1 = send Email 									0 = Not		| EMAIL
				 * index 2 = | 1 = send Web Push 								0 = Not		| Web Push
				 * index 3 = | 1 = send SMS 									0 = Not		| SMS
				 * index 4 = | 1 = send CALL 									0 = Not 	| CALL
				 *
				 */
				array (
						'name' => 'SEND_NOTIFICATIONS',
						'value' => 'Y,1,0,0,0',
				),
		       /**
				 * index 0 = | Y = Send reminders daily true					N = Not		| DAILY
				 *
				 * String explode index based reminder date separately after open_date
				 *
				 * index 1 = | Reminder one
				 * index 2 = | Reminder two
				 * index 3 = | Reminder three
		        */
    		    array (
    		        'name' => 'SEND_REMINDER',
    		        'value' => 'N,3,10,17',
    		    ),
    		    /**
    		     * index 0 = | Y = Excel export true				  		    N = Not		| EXPORT
    		     *
    		     * String explode index based excel export settings
    		     *
    		     * index 1 = | Starting row                                                 | STARTING_ROW
    		     * index 2 = | Chart data row                                               | CHART_DATA_ROW
    		     * index 3 = | Chart view start row                                         | CHART_VIEW_START_ROW
    		     * index 4 = | Chart view end row                                           | CHART_VIEW_END_ROW
    		     */
    		    array (
    		        'name' => 'EXCEL_EXPORT',
    		        'value' => 'Y,3,4,3,20',
    		    ),
    		    /**
    		     * UPM service settings
    		     * index 0 = | End point                                                    | END_POINT
    		     * index 1 = | App code                                                     | APP_CODE
    		     */
		        array(
		            'name' => 'UPM_SERVICE',
		            'value' => 'Y,CHS,http://192.168.10.10:5566/UPMService.asmx,1.1,http://127.0.0.1:8001/api/1.0,50-40-30'
		        ),
		        /**
		         * index 0 = | Mail Host
		         * index 1 = | Mail Port
		         * index 2 = | Username
		         * index 3 = | Password
		         * index 4 = | TLS
		         */
    		    array(
    		        'name' => 'MAIL_SETTINGS',
    		        'value' => 'smtp,smtp.cumulus.lk,586,chs@sits.lk,Sits_chs@123,TLS,chs@sits.lk,Help Desk'
    		    ),
		        /**
		         * index 0 = | Complaint raise category level
		         */
    		    array(
    		        'name' => 'CATEGORY_SETTINGS',
    		        'value' => '3,3'
    		    )
				
		);
		foreach ( $settings as $setting) {
			Setting::create ( $setting);
		}
	}
}
