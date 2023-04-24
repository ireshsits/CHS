<?php

namespace App\Models\Repositories\Zones;

use DateHelper;
use UserHelper;
use App\Models\Entities\Zone;
use App\Models\Entities\Region;
use DataTables;

class ManageZonesRepository implements ZonesInterface {
    public function __construct() {
    }
    private function generateSearchQuery($data) {
        $query = Zone::select ( '*' );
        if(isset($data['name']))
            $query->whereRaw ( 'LOWER(name) like ?', array (
                'name' => '%' . strtolower ( $data ['name'] ) . '%'
            ) );
            return $query->where('status','ACT')->distinct ();
    }
    private function generateQuery($filters) {
        $query = Zone::select ( '*' );
        if (isset ( $filters ['regionFilters'] )) {
            $query->WhereHas ( 'regions', function ($q) use ($filters) {
                if (isset ( $filters ['region_id_pk'] ))
                    $q->where ( 'region_id_pk', $filters ['region_id_pk'] );
            } );
        }
        $query->with ( [
            'regions',
            'manager',
            'rejectedBy'
        ] );
        
        if (isset ( $filters ['zone_id_pk'] ))
            $query->where ( 'zone_id_pk', $filters ['zone_id_pk'] );
            if (isset ( $filters ['random'] ))
                $query->inRandomOrder ();
                if (isset ( $filters ['limit'] ))
                    $query->limit ( $filters ['limit'] );
                    return $query;
    }
    private function buildSearchResponse($zone) {
        $zone->id = $zone->zone_id_pk;
        $zone->text = $zone->name;
        $zone->display_zone = true;
        return $zone;
    }
    private function buildResponse($zone) {
        return $zone;
    }
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getZone($filters) {
        $queryString = $this->generateQuery ( $filters );
        $zone = $queryString->first ();
        return $this->buildResponse ( $zone );
    }
    
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getZones($filters) {
        $queryString = $this->generateQuery ( $filters );
        $datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($zone) {
            return $zone->zone_id_pk;
        } )->addColumn ( 'manager_name', function ($zone) {
            return (isset($zone->manager) ? $zone->manager->first_name.' '.$zone->manager->last_name : '');
        } )->addColumn ( 'manager_email', function ($zone) {
            return (isset($zone->manager) ? $zone->manager->email : '');
        } )->addColumn ( 'rejected_by', function ($zone) {
            return (isset($zone->rejectedBy) ? $zone->rejectedBy->first_name.' '.$zone->rejectedBy->last_name : '');
        } )->addColumn ( 'regions_count', function ($zone) {
            return count ( $zone->regions);
        } )->editColumn ( 'created_at', function ($zone) {
            return DateHelper::formatToDateString ( $zone->created_at );
        } )->removeColumn ( 'rejected_by_fk', 'manager_id_fk', 'rejectedBy','manager');
        
        return $datatable->toJson ();
    }
    /**
     *
     * @param unknown $params
     * @return unknown
     */
    public function saveOrEditZone($data){
        if($data['status'] == 'REJ')
            $data['rejected_by_fk'] = UserHelper::getLoggedUserId();
            try {
                $filter = array ();
                if (isset ( $data ['mode'] )) {
                    if ($data ['mode'] !== 'edit') {
                        $filter = array (
                            'name' => $data ['name']
                        );
                    } else {
                        $filter = array (
                            'zone_id_pk' => $data ['zone_id_pk']
                        );
                    }
                }
                $zone = Zone::firstOrNew ( $filter );
                $zone->fill ( $data );
                $zone->save ();
                return $zone;
            } catch ( \Exception $e ) {
                dd ( $e );
            }
    }
    public function syncRegion($params){
        try {
            /**
             * ID contains the region_id_fk
             * @var unknown $region
             */
            $region = Region::find($params['id']);

            if ($params['mode'] == 'associate'){
                $zone = Zone::find($params['zone_id_pk']);
                $region->zone()->associate($zone)->save();
            }else
                $region->zone()->dissociate()->save();
            return $region;
        } catch (\Exception $e) {
            dd($e);
        }
    }
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getZonesForSearch($filters) {
        $queryString = $this->generateSearchQuery ( $filters );
        $response = $queryString->get ();
        foreach ( $response as $zone ) {
            $zone = $this->buildSearchResponse ( $zone );
        }
        return $response;
    }
    public function zoneDelete($id) {
        $zone = Zone::withTrashed ()->where ( 'zone_id_pk', $id )->first ();
        if ($zone->trashed ()) {
            return true;
        }
        /**
         * dissociate all the regions
         */
        $regions = Region::where('zone_id_fk', $zone->zone_id_pk)->get();
        foreach ($regions as $region){
            $region->zone()->dissociate()->save();
        }
        return $zone->delete ();
    }
    public function statusZonesUpdate($data) {
        if ((int) $data['id'] > 0)
            $zones = Zone::where('zone_id_pk', $data['id'])->get();
        else
            $zones = Zone::all();
        foreach ($zones as $zone) {
            if ($data['field'] == 'status') {
                $zone->status = $data['status'];
            }
            $zone->save($data);
        }
        return true;
    }
}