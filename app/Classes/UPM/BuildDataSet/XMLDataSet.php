<?php

namespace App\Classes\UPM\BuildDataSet;

use App\Models\Entities\Setting;
use App\Classes\UPM\Traits\ServiceXMLClientTrait;
use App\Models\Entities\SystemRole;
use App\Classes\UPM\BuildDataSet\DataTypes;
use Log;

class XMLDataSet {
    
    protected $dataTypes;
    protected $upmSettings;
    
    public function __construct($upmSettings){
        $this->upmSettings = $upmSettings;
        $this->soapVersion = (float)$upmSettings->SOAP_VERSION;
        $this->dataTypes = new DataTypes();
    }

    public function generate($parser, $params){
        switch($params['method']){
            case 'SIGNON'      : return $this->getSignOnInformation($parser);
            case 'SIGNOFF'     : return $this->getSignOffInformation($parser);
            case 'ALLDIVS'     : return $this->getAllDivInformation($parser);
            case 'ALLSOLS'     : return $this->getAllSolInformation($parser);
            case 'USERSBAC'    : return $this->getEmployeeByAppCodeInformation($parser);
            case 'USERSBDC'    : return $this->getEmployeeByUPMDivCodeInformation($parser);
            case 'USERSBS'     : return $this->getSolWiseEmployeeList($parser);
            case 'SOLBEMPID'   : return $this->getSolInformationByEmpId($parser);
            case 'USERSBACDS'  : return $this->getUsersByAppCodeDivSol($parser);
            default: return null;
        }
    }
    
    private function getAllSolInformation($parser){
        $solIds = array();
        $branches = array();
        if($this->soapVersion === 1.1)
            $parserArr = $parser->getAllSolInformationResponse->getAllSolInformationReturn;
        else
            $parserArr = $parser->getAllSolInformationResponse->getAllSolInformationResult->string;

        foreach ($parserArr as $sol){
            $details = explode("|",$sol);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                if(trim($details[0]) && is_numeric($details[0])){
                    $solIds [] = (int)filter_var(trim($details[0]), FILTER_SANITIZE_NUMBER_INT);
                    $branches [] = array(
                        'SOL_ID' => trim($details[0])? filter_var(trim($details[0]), FILTER_SANITIZE_NUMBER_INT):null,
                        'NAME' => $details[1]??null,
                        'AREA_CODE' => trim($details[3])? filter_var(trim($details[3]), FILTER_SANITIZE_NUMBER_INT):null
                    );
                }
            }else{
                Log::info ( 'sync----getAllSolInformation----Error---------' . json_encode ( trim($details[1]) ) );
            }
        }
        $this->dataTypes->setSolIds($solIds);
        return $branches;
    }
    private function getAllDivInformation($parser){
        $divIds = array();
        $divisions = array();
        if($this->soapVersion === 1.1)
            $parserArr = $parser->getAllDivInformationResponse->getAllDivInformationReturn;
        else
            $parserArr = $parser->getAllDivInformationResponse->getAllDivInformationResult->string;

        foreach ($parserArr as $div){
            $details = explode("|",$div);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                if(trim($details[0])){
                    $divIds [] = $details[0]?(int)$details[0]:null;
                    $divisions [] = array(
                        'SOL_ID' => trim($details[0])? filter_var(trim($details[0]), FILTER_SANITIZE_NUMBER_INT):null,
                        'NAME' => $details[1]??null
                    );
                }
            }else{
                Log::info ( 'sync----getAllDivInformation----Error---------' . json_encode ( trim($details[1]) ) );
            }
        }
        $this->dataTypes->setDivIds(($solIds));
        return $divisions;
    }
    private function getSignOnInformation($parser){
        $userInfo = array();
        $key = 0;        
        if($this->soapVersion === 1.1)
            $parserArr = $parser->signOnResponse->signOnReturn;
        else
            $parserArr = $parser->signOnResponse->signOnResult->string;

        foreach ($parserArr as $div){
            $details = explode("|",$div);
            if($key == 0 && trim($details[0]) && strtoupper(trim($details[0])) == 'ERROR'){
                $res = array(
                    'success' => false,
                    'message' => $details[1]??null
                );
            }else{
                if($key == 3)
                    $userInfo['ROLE'] = $this->mapRoleWorkClass($details[0]);
                if($key == 8)
                    $userInfo['DESIGNATION'] = $details[0]??null;
                if($key == 9)
                    $userInfo['DIV_CODE'] = $details[0]??null;
                if($key == 11)
                    $userInfo['EMAIL'] = $details[0]??null;
                if($key == 13)
                    $userInfo['FIRST_NAME'] = $details[0]??null;
                if($key == 18)
                    $userInfo['LAST_NAME'] = $details[0]??null;
                if($key == 27)
                    $userInfo['SOL_ID'] = $details[0]??null;
                if($key == 28)
                    $userInfo['STATUS'] = $details[0]??null;
                if($key == 40)
                    $userInfo['USER_ID'] = $details[0]??null;
                        
                $res = array(
                    'success' => true,
                    'userInfo' => [$userInfo]
                );
                ++$key;
            }
        }
        return $res;
    }
    private function getSignOffInformation($parser){
        $signOffInfo = [];        
        if($this->soapVersion === 1.1)
            $parserArr = $parser->signOffResponse->signOffReturn;
        else
            $parserArr = $parser->signOffResponse->signOffResult->string;

        foreach ($parserArr as $div){
            $details = explode("|",$div);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                $signOffInfo = array(
                    "success" => true,
                    "message" => $details[1]??null
                );
            }else{
                $signOffInfo = array(
                    "success" => false,
                    "message" => $details[1]??null
                );
            }
        }
        return $signOffInfo;
    }
    private function getEmployeeByAppCodeInformation($parser){
        $users = array();
        if($this->soapVersion === 1.1)
            $parserArr = $parser->getEmployeesByAppCodeResponse->getEmployeesByAppCodeReturn;
        else
            $parserArr = $parser->getEmployeesByAppCodeResponse->getEmployeesByAppCodeResult->string;
        
        foreach ($parserArr as $div){
            $details = explode("|",$div);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                $users[] = array(
                    'USER_ID' => $details[0]??null,
                    'FIRST_NAME' => $details[1]??null,
                    'LAST_NAME' => $details[2]??null,
                    'ROLE' => $this->mapRoleWorkClass($details[4]??null),
                    'STATUS' => $details[18]??0
                );
            }else{
                Log::info ( 'sync----getEmployeeByAppCodeInformation----Error---------' . json_encode ( trim($details[1]) ) );
            }
        }
        return $users;
    }
    private function getEmployeeByUPMDivCodeInformation($parser){
        $users = array();
        if($this->soapVersion === 1.1)
            $parserArr = $parser->getEmployeesByUPMDivCodeResponse->getEmployeesByUPMDivCodeReturn;
        else
            $parserArr = $parser->getEmployeesByUPMDivCodeResponse->getEmployeesByAppCodeResult->string;
        
        foreach ($parserArr as $div){
            $details = explode("|",$div);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                $users[] = array(
                    'USER_ID' => $details[0]??null,
                    'FIRST_NAME' => $details[1]??null,
                    'LAST_NAME' => $details[2]??null,
                    'DIV_CODE' => $details[3]>>null
                );
            }else{
                Log::info ( 'sync----getEmployeeByUPMDivCodeInformation----Error---------' . json_encode ( trim($details[1]) ) );
            }
        }
        return $users;
    }
    private function getSolInformationByEmpId($parser){
        $solInfo = array();
        if($this->soapVersion === 1.1)
            $parseArr = $parser->getSolInformationByEmployeeIDResponse->getSolInformationByEmployeeIDReturn;
        else
            $parseArr = $parser->getSolInformationByEmployeeIDResponse->getSolInformationByEmployeeIDResult->string;
        
        foreach ($parseArr as $sol){
            $details = explode("|",$sol);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                $solInfo [] = array(
                    'SOL_ID' => $details[0],
                    'NAME' => $details[1],
                    'DIV_CODE' => $details[2]
                );
            }else{
                Log::info ( 'sync----getSolInformationByEmpId----Error---------' . json_encode ( trim($details[1]) ) );
            }
        }
        return $solInfo;
    }
    private function getSolWiseEmployeeList($parser){
        $users = array();
        if($this->soapVersion === 1.1)
            $parserArr = $parser->getSolWiseEmployeeListResponse->getSolWiseEmployeeListReturn;
        else
            $parserArr = $parser->getSolWiseEmployeeListResponse->getSolWiseEmployeeListResult->string;
        
        foreach ($parserArr as $div){
            $details = explode("|",$div);
            if(trim($details[0]) && strtoupper(trim($details[0])) != 'ERROR'){
                $users[] = array(
                    'USER_ID' => $details[0]??null,
                    'FIRST_NAME' => $details[1]??null,
                    'LAST_NAME' => $details[2]??null,
                    'ROLE' => $this->mapRoleWorkClass($details[4]??null),
                    //                     'STATUS' => !is_null($details[18]) && (int) $details[18] == 1 ? true : false
                    'STATUS' => $details[18]??0
                );
            }else{
                Log::info ( 'sync----getEmployeeByAppCodeInformation----Error---------' . json_encode ( trim($details[1]) ) );
            }
        }
        return $users;
    }
    private function getUsersByAppCodeDivSol($parser){
        
    }
    private function mapRoleWorkClass($wClass){
        switch($wClass){
            case 50 : return SystemRole::where('key','ADMIN_ROLE')->first()->value; break;
            case 40 : return SystemRole::where('key','ADMIN_CCC_ROLE')->first()->value; break;
            case 30 : return SystemRole::where('key','USER_ROLE')->first()->value; break;
        }
    }
}