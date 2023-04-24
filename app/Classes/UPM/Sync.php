<?php

namespace App\Classes\UPM;

use Log;
use App\Classes\UPM\Connectors\Connector;
use App\Models\Entities\BranchDepartment;
use App\Models\Entities\Setting;

// use Batch;
use Illuminate\Database\DatabaseManager;
use App\Classes\BatchUpdate\Batch;
use App\Models\Repositories\BranchDepartments\BranchDepartmentsRepository;
use App\Models\Repositories\Users\UserRepository;
use App\Classes\UPM\BuildDataSet\DataTypes;

class Sync {
    
    protected $batch;
    protected $branchRepo;
    protected $userRepo;
    protected $dataTypes;
    protected $getUPMSettings;
    public function __construct(){
        $this->getUPMSettings = Setting::getUPMSettings();
        $this->connector = new Connector($this->getUPMSettings);
        $this->batch = new Batch();
        $this->dataTypes = new DataTypes();
    }
    
    public function getInitialUPMSync(array $params = []){
        try{
            $callServices = ['ALLSOLS','USERSFR'];
//             $callServices = ['USERSBAC'];
            foreach ($callServices as $service){
                if($service == 'USERSFR'){
                    $solIds = $this->dataTypes->getSolIds();
                    foreach ($solIds as $solId){
                        foreach($this->getUPMSettings->SECURITY_CLASSES as $securityClass){
                            $this->getSolWiseUsersForRole($solId, $securityClass);
                        }
                    }
                }else{
                    $responseData = $this->connector->connectApi(array('method'=>$service, 'data' => $params));
                    $this->syncDB($responseData,$service);
                }
            }
        }catch(\Exception $e){
            Log::error ( 'getInitialUPMSync---------error---------' . json_encode ( $e->getMessage () ) );
        }
    }
    public function getSolWiseUsersForRole($solId, $securityClass = 30){
        try{
            $service = 'USERSFR';
            $params['solId'] = sprintf ( "%03d", $solId);
            $params['appSecurityClass'] = $securityClass;
            $responseData = $this->connector->connectApi(array('method'=>$service, 'data' => $params));
            $this->syncDB($responseData,$service);
        }catch(\Exception $e){
            Log::error ( 'getSolWiseUsersForRole---------error---------' . json_encode ( $e->getMessage () ) );
        }
    }
    public function signOn($params){
        try{
            $service = 'SIGNON';
            $responseData = $this->connector->connectApi(array('method'=>$service, 'data' => $params));
            
            if (isset($responseData['success']) && $responseData['success'] == false) {
                return $responseData;
            }
            // $this->syncDB($responseData,$service);
            // return $responseData;
            if ($this->syncDB($responseData,$service) == false) {
                return false;
            } else {
                return $responseData;
            }
            
        }catch(\Exception $e){
            Log::error ( 'signOn---------error---------' . json_encode ( $e->getMessage () ) );
        }
    }
    public function signOff($params){
        try{
            $service = 'SIGNOFF';
            return $this->connector->connectApi(array('method'=>$service, 'data' => $params));
        }catch(\Exception $e){
            Log::error ( 'signOff---------error---------' . json_encode ( $e->getMessage () ) );
        }
    }
    public function getSolInformationByEmployeeID($empId){
        try{
            $service = 'SOLBEMPID';
            $params['empId'] = $empId;
            $responseData = $this->connector->connectApi(array('method'=>$service, 'data' => $params));
            $this->syncDB($responseData,$service);
            return $responseData;
        }catch(\Exception $e){
            Log::error ( 'getSolInformationByEmployeeID---------error---------' . json_encode ( $e->getMessage () ) );
        }
    }
    private function syncDB($responseData, $action){
        switch ($action){
            case 'SIGNON'      : return $this->signOnUserSync($responseData);
//             case 'SIGNOFF'     : return $this->signOff($responseData);
            case 'ALLDIVS'     : return $this->saveAllDivisions($responseData);
            case 'ALLSOLS'     : return $this->saveAllSols($responseData);
            case 'USERSBAC'    : return $this->saveUsers($responseData);
            case 'USERSBDC'    : return $this->saveUsers($responseData);
            case 'USERSBS'     : return $this->saveUsers($responseData);
            case 'USERSFR'     : return $this->saveUsers($responseData);
            case 'SOLBEMPID'   : return $this->saveBranch($responseData);
            case 'USERSBACDS'  : return $this->saveUsers($responseData);
            default: return null;
        }
    }
    private function signOnUserSync($responseData){
        if($responseData['userInfo']){
            $this->userRepo = new UserRepository();
            $upmUserSyncRes = $this->userRepo->upmUserSync($responseData['userInfo']);
            // for email existing validation
//            if ($upmUserSyncRes == false) {
//                return false;
//            }
            return true;
        }
    }
    private function saveAllDivisions($responseData){
        dd($responseData);
        
    }
    private function saveAllSols($responseData){
        $this->branchRepo  = new BranchDepartmentsRepository();
        $this->branchRepo->saveOrEditBranch($responseData);
        return true;
    }
    private function saveUsers($responseData){
        $this->userRepo = new UserRepository();
        $this->userRepo->upmUserSync($responseData);
        return true;
    }
    private function saveBranch($responseData){
        $this->branchRepo  = new BranchDepartmentsRepository();
        $this->branchRepo->saveOrEditBranch($responseData);
        return true;
    }
    
}