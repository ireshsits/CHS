<?php

namespace App\Models\Repositories\Zones;

interface ZonesInterface {
    
    public function getZone($filters);
    public function getZones($filters);
    public function saveOrEditZone($data);
    public function syncRegion($params);
    public function getZonesForSearch($filters);
    public function zoneDelete($id);
    public function statusZonesUpdate($data);
    
}