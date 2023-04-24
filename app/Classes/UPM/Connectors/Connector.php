<?php

namespace App\Classes\UPM\Connectors;

// use Carbon\Carbon;
// use App;
use App\Classes\UPM\Traits\ServiceXMLClientTrait;
use App\Classes\UPM\Traits\ServiceRESTClientTrait;
use App\Classes\UPM\BuildDataSet\XMLDataSet;

class Connector {
	    
	    private $appCode;
	    private $endPoint;
	    
	    protected $upmSettings;
	    protected $buildXMLDataSet;
	    
	    use ServiceXMLClientTrait;
	    use ServiceRESTClientTrait;
	    
	    public function __construct($upmSettings){
	        $this->upmSettings = $upmSettings;
	        $this->soapVersion = (float)$upmSettings->SOAP_VERSION;
// 	        $this->appCode = 'RBC';
	        $this->buildXMLDataSet = new XMLDataSet($upmSettings);
	    }
	    
	    public function connectApi(array $params = []){
	        if($params['method'] == 'USERSFR'){
	             $requestURL = $this->restAction($params['method'], $params);
	             return $this->callRESTService($requestURL,$params ,[], 'GET');
	        }
	        
	        $requestURL = $this->xmlAction($params['method']);
	        $params['requestBody'] = $this->requestBody($params['method'], $params);
	        $headerParams = array();
	        if($this->soapVersion === 1.1){
	            $headerParams[] = "SOAPAction: ".$this->soapAction($params['method']);
	        }
	        $parser = $this->callXMLService($requestURL,$params ,$headerParams, 'POST', $this->soapVersion);
	        return $this->buildXMLDataSet->generate($parser, $params);
	    }

	    private function restAction($action, $params){
	        switch ($action){
	            case 'USERSFR'     : return $this->upmSettings->REST_URL.'/UpmRestService/GetUsersForRole/'.$params['data']['solId'].'/'.$params['data']['appSecurityClass'].'/'.$this->upmSettings->APPLICATION_CODE.'/';
	            default            : return null;
	        }
	    }
	    
	    private function xmlAction($action){
	        if($this->soapVersion === 1.2){
    	        switch ($action){
    	            case 'SIGNON'      : return $this->upmSettings->SOAP_URL.'?op=signOn';
    	            case 'SIGNOFF'     : return $this->upmSettings->SOAP_URL.'?op=signOff';
    	            case 'ALLDIVS'     : return $this->upmSettings->SOAP_URL.'?op=getAllDivInformation';
    	            case 'ALLSOLS'     : return $this->upmSettings->SOAP_URL.'?op=getAllSolInformation';
    	            case 'USERSBAC'    : return $this->upmSettings->SOAP_URL.'?op=getEmployeesByAppCode';
    	            case 'USERSBDC'    : return $this->upmSettings->SOAP_URL.'?op=getEmployeesByUPMDivCode';
    	            case 'USERSBS'     : return $this->upmSettings->SOAP_URL.'?op=getSolWiseEmployeeList';
    	            case 'SOLBEMPID'   : return $this->upmSettings->SOAP_URL.'?op=getSolInformationByEmployeeID';
    	            default            : return null;
    	        }
	        }else{
	            return $this->upmSettings->SOAP_URL;
	        }
	    }
	    
	    private function requestBody($action,$params){
	        switch ($action){
	            case 'SIGNON'      : return '<signOn xmlns="http://tempuri.org/">
                                                <UserId>'.$params['data']['empId'].'</UserId>
                                                <Password>'.$params['data']['password'].'</Password>
                                                <Application_Code>'.$this->upmSettings->APPLICATION_CODE.'</Application_Code>
                                             </signOn>';
	            case 'SIGNOFF'     : return '<signOff xmlns="http://tempuri.org/">
                                                <UserId>'.$params['data']['empId'].'</UserId>
                                                <Application_Code>'.$this->upmSettings->APPLICATION_CODE.'</Application_Code>
                                            </signOff>';
	            case 'ALLDIVS'     : return '<getAllDivInformation xmlns="http://tempuri.org/" />';
	            case 'ALLSOLS'     : return '<getAllSolInformation xmlns="http://tempuri.org/" />';
	            case 'USERSBAC'    : return '<getEmployeesByAppCode xmlns="http://tempuri.org/">
                                                <Application_Code>'.$this->upmSettings->APPLICATION_CODE.'</Application_Code>
                                            </getEmployeesByAppCode>';
	            case 'USERSBDC'    : return '<getEmployeesByUPMDivCode xmlns="http://tempuri.org/">
                                                <solID>'.$params['data']['solId'].'</solID>
                                            </getEmployeesByUPMDivCode>';
	            case 'USERSBS'     : return '<getSolWiseEmployeeList xmlns="http://tempuri.org/">
                                                <solID>'.$params['data']['solId'].'</solID>
                                            </getSolWiseEmployeeList>';
	            case 'SOLBEMPID'   : return '<getSolInformationByEmployeeID xmlns="http://tempuri.org/">
                                                <employeeID>'.$params['data']['empId'].'</employeeID>
                                            </getSolInformationByEmployeeID>';
	            default            : return null;
	        }
	    }
	    private function soapAction($action){
	        switch ($action){
	            case 'SIGNON'      : return 'http://tempuri.org/signOn';
	            case 'SIGNOFF'     : return 'http://tempuri.org/signOff';
	            case 'ALLDIVS'     : return 'http://tempuri.org/getAllDivInformation';
	            case 'ALLSOLS'     : return 'http://tempuri.org/getAllSolInformation';
	            case 'USERSBAC'    : return 'http://tempuri.org/getEmployeesByAppCode';
	            case 'USERSBDC'    : return 'http://tempuri.org/getEmployeesByUPMDivCode';
	            case 'USERSBS'     : return 'http://tempuri.org/getSolWiseEmployeeList';
	            case 'SOLBEMPID'   : return 'http://tempuri.org/getSolInformationByEmployeeID';
	            default            : return null;
	        }
	    }
}