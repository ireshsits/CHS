<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cache;
use Log;

class Setting extends Model
{
	use SoftDeletes;
	
// 	protected $connection = 'oracle';
	protected $table="settings";
	protected $primaryKey = 'setting_id_pk';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'name',
			'value'
			
	];
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
			'deleted_at'
	];
	
	public static function getNotificationSettings() {
	    if (Cache::has ( 'SEND_NOTIFICATIONS' )) {
	        return self::getFromCache('SEND_NOTIFICATIONS');
	    }else{
		    $settings = explode ( ',', parent::select ( 'name', 'value' )->whereRaw ( 'UPPER(name) like ?', array( 'name' => 'SEND_NOTIFICATIONS'))->first ()->value );
    		$object = ( object ) [
    			'NOTIFICATIONS' => ($settings [0] == 'Y' ? true : false),
    			'EMAIL' => ($settings [1] == '1' ? true : false),
    			'WEBPUSH' => ($settings [2] == '1' ? true : false),
    			'SMS' => ($settings [3] == '1' ? true : false),
    			'CALL' => ($settings [4] == '1' ? true : false)
    		];
    		self::putInCache('SEND_NOTIFICATIONS', $object, 'SETTINGS');
    		return $object;
	    }
	}
	
	public static function getReminderSettings() {
	    if (Cache::has ( 'SEND_REMINDER' )) {
	        return self::getFromCache('SEND_REMINDER');
	    }else{
    	    $settings = explode ( ',', parent::select ( 'name', 'value' )->whereRaw ( 'UPPER(name) like ?', array( 'name' => 'SEND_REMINDER'))->first ()->value );
    	    foreach ($settings as $sKey=>$sVal){
    	        if($sKey != 0)
    	           $days[] = (int)$sVal;
    	    }
    	    $object = ( object ) [
    	        'DAILY' => ($settings [0] == 'Y' ? true : false),
    	        'DAYS' => $days
    	    ];
    	    self::putInCache('SEND_REMINDER', $object, 'SETTINGS');
    	    return $object;
	    }
	}
	
	public static function getExcelSettings() {
	    if (Cache::has ( 'EXCEL_EXPORT' )) {
	        return self::getFromCache('EXCEL_EXPORT');
	    }else{
	        $settings = explode ( ',', parent::select ( 'name', 'value' )->whereRaw ( 'UPPER(name) like ?', array( 'name' => 'EXCEL_EXPORT'))->first ()->value );
	        $object = ( object ) [
	            'EXPORT' => ($settings [0] == 'Y' ? true : false),
	            'STARTING_ROW' => $settings [1],
	            'CHART_DATA_ROW' => $settings [2],
	            'CHART_VIEW_START_ROW' => $settings [3],
	            'CHART_VIEW_END_ROW' => $settings [4]
	        ];
	        self::putInCache('EXCEL_EXPORT', $object, 'SETTINGS');
	        return $object;
	    }
	}
	
	public static function getUPMSettings(){
	    if (Cache::has ( 'UPM_SERVICE' )) {
	        return self::getFromCache('UPM_SERVICE');
	    }else{
    	    $settings = explode ( ',', parent::select ( 'name', 'value' )->whereRaw ( 'UPPER(name) like ?', array( 'name' => 'UPM_SERVICE'))->first ()->value );
    	    $object = ( object ) [
    	        'UPM' => ($settings [0] == 'Y' ? true : false),
    	        'APPLICATION_CODE' => $settings [1],
    	        'SOAP_URL' => $settings [2],
    	        'SOAP_VERSION' => $settings [3],
    	        'REST_URL' => $settings [4],
    	        'SECURITY_CLASSES' => explode('-', $settings[5]),
    	        'RAW_SECURITY_CLASSES' => $settings[5],
    	    ];
    	    self::putInCache('UPM_SERVICE', $object, 'SETTINGS');
    	    return $object;
	    }
	}
	
	public static function getMailSettings(){
	    if (Cache::has ( 'MAIL_SETTINGS' )) {
	        return self::getFromCache('MAIL_SETTINGS');
	    }else{
    	    $settings = explode ( ',', parent::select ( 'name', 'value' )->whereRaw ( 'UPPER(name) like ?', array( 'name' => 'MAIL_SETTINGS'))->first ()->value );
    	    $object = ( object ) [
    	        'MAIL_DRIVER' => $settings [0]??null,
    	        'MAIL_HOST' => $settings [1]??null,
    	        'MAIL_PORT' => $settings [2]??null,
    	        'MAIL_USERNAME' => $settings [3]??null,
    	        'MAIL_PASSWORD' => $settings [4]??null,
    	        'MAIL_ENCRYPTION' => $settings [5]??null,
    	        'MAIL_FROM_ADDRESS' => $settings[6]??null,
    	        'MAIL_FROM_NAME' => $settings[7]??null
    	    ];
    	    self::putInCache('MAIL_SETTINGS', $object, 'SETTINGS');
    	    return $object;
	    }
	}
	
	public static function getCategorySettings() {
	    if (Cache::has ( 'CATEGORY_SETTINGS' )) {
	        return self::getFromCache('CATEGORY_SETTINGS');
	    }else{
	        $settings = explode ( ',', parent::select ( 'name', 'value' )->whereRaw ( 'UPPER(name) like ?', array( 'name' => 'CATEGORY_SETTINGS'))->first ()->value );
	        $object = ( object ) [
	            'CATEGORY_LEVELS' => $settings [0],
	            'RAISE_LEVEL' => $settings [1]
	        ];
	        self::putInCache('CATEGORY_SETTINGS', $object, 'SETTINGS');
	        return $object;
	    }
	}
	private static function putInCache($key, $content, $tag) {
	    Log::info('Put in Cache >>'.json_encode($key));
	    Cache::put ( $key, json_encode ( $content ), 1800 ); //30 minutes
	}
	private static function getFromCache($key) {
	    return (object) json_decode ( Cache::get ( $key ) );
	}
}
