<?php

namespace App\Classes\UPM\BuildDataSet;
use Cache;
use Log;

class DataTypes {
    
    public function setSolIds($solIds){
        Log::info('Put in SolIds');
        Cache::put ( 'sol-ids', json_encode (array_unique(array_filter($solIds))), 1800 ); //30 minutes
    }
    
    public function getSolIds(){
        return (array) json_decode ( Cache::get ( 'sol-ids' ) );
    }
    public function setDivIds($divIds){
        Log::info('Put in DivIds');
        Cache::put ( 'div-ids', json_encode (array_unique(array_filter($divIds))), 1800 ); //30 minutes
    }
    
    public function getDivIds(){
        return (array) json_decode ( Cache::get ( 'div-ids' ) );
    }
}