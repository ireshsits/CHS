<?php

namespace App\Models\Repositories\Regions;

interface RegionsInterface {
    
    public function getRegion($filters);
    public function getRegions($filters);
    public function saveOrEditRegion($data);
    public function syncBranch($params);
    public function getRegionForSearch($filters);
    public function regionDelete($id);
    public function statusRegionsUpdate($data);
    
}