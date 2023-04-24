<?php
namespace App\Classes\UPM\Traits;

use Illuminate\Support\Facades\Log;
trait  ServiceRESTClientTrait{
	
	
	public function callRESTService($requestURL, array $params = [], array $headerParams = [], $type = "GET"){

		Log::info('requestURL >>> '.$requestURL);
		
		$header[] = "Accept: application/json";
		$header[] = "Content-Type: application/json";
		$header = array_merge($header,$headerParams);
		
		// Log::info('UPM request >>> '.json_encode($params));
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
		if($type == 'GET'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			
		}else{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		}
		curl_setopt( $ch, CURLOPT_URL, $requestURL );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
		$response =  curl_exec($ch);
		if(curl_errno($ch)){
		    throw new \Exception(curl_error($ch));
		}
		/**
		 * return an associative array
		 */
		return json_decode($response,TRUE);
	
	}
}