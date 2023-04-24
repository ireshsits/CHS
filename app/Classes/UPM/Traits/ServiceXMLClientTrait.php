<?php
namespace App\Classes\UPM\Traits;

use Illuminate\Support\Facades\Log;

trait  ServiceXMLClientTrait{
	
	
	public function callXMLService($requestURL, array $params = [], array $headerParams = [], $type = "GET", $version){

		Log::info('requestURL >>> '.$requestURL);
		
		// Log::info('UPM request >>> '.json_encode($params));
		if($version === 1.1){
		    $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                    <soap:Body>
                        '.$params['requestBody'].'
                    </soap:Body>
                </soap:Envelope>';
		    $header[] = "Content-Type: text/xml;";
		}else{
		    $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                    <soap12:Body>
                        '.$params['requestBody'].'
                    </soap12:Body>
                </soap12:Envelope>';
		    
		    $header[] = "Content-type: application/soap+xml;";
		}
		
		$header[] = "Content-length: ".strlen($xml_post_string);
		$header = array_merge($header,$headerParams);

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
		if($type == 'GET'){
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			
		}else{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
		}
		curl_setopt( $ch, CURLOPT_URL, $requestURL );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLINFO_HEADER_OUT, true); // capture the header info

		$response = curl_exec($ch);
		if(curl_errno($ch)){
		    throw new \Exception(curl_error($ch));
		}
		
		$info = curl_getinfo($ch);
		curl_close($ch);

		if($version === 1.1){
		    $response1 = str_replace("<soapenv:Body>","",$response);
		    $response2 = str_replace("</soapenv:Body>","",$response1);
		}else{
		    $response1 = str_replace("<soap:Body>","",$response);
		    $response2 = str_replace("</soap:Body>","",$response1);
		}

		return simplexml_load_string($response2);
	
	}
}