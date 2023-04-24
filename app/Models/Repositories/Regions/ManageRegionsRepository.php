<?php

namespace App\Models\Repositories\Regions;

use DateHelper;
use UserHelper;
use App\Models\Entities\Region;
use App\Models\Entities\Zone;
use App\Models\Entities\BranchDepartment;
use DataTables;

class ManageRegionsRepository implements RegionsInterface {
    public function __construct() {
    }
    private function generateSearchQuery($data) {
        $query = Region::select ( '*' )->whereDoesntHave('zone');
        if(isset($data['name']))
            $query->whereRaw ( 'LOWER(name) like ?', array (
                'name' => '%' . strtolower ( $data ['name'] ) . '%'
            ) );
            return $query->where('status','ACT')->distinct ();
    }
    private function generateQuery($filters) {
        $query = Region::select ( '*' );
        if (isset ( $filters ['branchFilters'] )) {
            $query->WhereHas ( 'branches', function ($q) use ($filters) {
                if (isset ( $filters ['branch_department_id_pk'] ))
                    $q->where ( 'branch_department_id_pk', $filters ['branch_department_id_pk'] );
            } );
        }
        $query->with ( [
            'zone',
            'branches',
            'manager',
            'rejectedBy'
        ] );
        
        if (isset ( $filters ['region_id_pk'] ))
            $query->where ( 'region_id_pk', $filters ['region_id_pk'] );
        if (isset ( $filters ['zone_id_fk'] ))
            $query->where ( 'zone_id_fk', $filters ['zone_id_fk'] );
        if (isset ( $filters ['random'] ))
            $query->inRandomOrder ();
        if (isset ( $filters ['limit'] ))
            $query->limit ( $filters ['limit'] );
        return $query;
    }
    private function buildSearchResponse($region) {
        $region->id = $region->region_id_pk;
        $region->text = $region->name;
        $region->display_region = true;
        return $region;
    }
    private function buildResponse($region) {
        $solIds = [];
        foreach ($region->branches as $branch){
            $solIds[] = $branch->sol_id;
        }
        $region->solIds = implode(',',$solIds);
        return $region;
    }
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getRegion($filters) {
        $queryString = $this->generateQuery ( $filters );
        $region = $queryString->first ();
        return $this->buildResponse ( $region );
    }
    
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getRegions($filters) {
        $queryString = $this->generateQuery ( $filters );
        $datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($region) {
            return $region->region_id_pk;
        } )->addColumn ( 'manager_name', function ($region) {
            return (isset($region->manager) ? $region->manager->first_name.' '.$region->manager->last_name : '');
        } )->addColumn ( 'manager_email', function ($region) {
            return (isset($region->manager) ? $region->manager->email : '');
        } )->addColumn ( 'zone_name', function ($region) {
            return (isset($region->zone) ? $region->zone->name : '');
        } )->addColumn ( 'rejected_by', function ($region) {
            return (isset($region->rejectedBy) ? $region->rejectedBy->first_name.' '.$region->rejectedBy->last_name : '');
        } )->addColumn ( 'branches_count', function ($region) {
            return count ( $region->branches);
        } )->editColumn ( 'created_at', function ($region) {
            return DateHelper::formatToDateString ( $region->created_at );
        } )->removeColumn ( 'rejected_by_fk', 'manager_id_fk', 'rejectedBy','manager','zone_id_fk','zone');
        
        return $datatable->toJson ();
    }
    /**
     *
     * @param unknown $params
     * @return unknown
     */
    public function saveOrEditRegion($data){
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
                            'region_id_pk' => $data ['region_id_pk']
                        );
                    }
                }
                $region = Region::firstOrNew ( $filter );
                $region->fill ( $data );
                $region->save ($data);
                return $region;
            } catch ( \Exception $e ) {
                dd ( $e );
            }
    }
    public function syncBranch($params){
        try {
            /**
             * ID contains the region_id_fk
             * @var unknown $region
             */
            $branch = BranchDepartment::find($params['id']);
            
            if ($params['mode'] == 'associate'){
                $region = Region::find($params['region_id_pk']);
                $branch->region()->associate($region)->save();
            }else
                $branch->region()->dissociate()->save();
                return $branch;
        } catch (\Exception $e) {
            dd($e);
        }
    }
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getRegionForSearch($filters) {
        $queryString = $this->generateSearchQuery ( $filters );
        $response = $queryString->get ();
        foreach ( $response as $region ) {
            $region = $this->buildSearchResponse ( $region );
        }
        return $response;
    }
    public function regionDelete($id) {
        $region = Region::withTrashed ()->where ( 'region_id_pk', $id )->first ();
        if ($region->trashed ()) {
            return true;
        }
        /**
         * dissociate all the branches
         */
        $branches = BranchDepartment::where('region_id_fk', $region->region_id_pk)->get();
        foreach ($branches as $branch){
            $branch->region()->dissociate()->save();
        }
        return $region->delete ();
    }
    public function statusRegionsUpdate($data) {
        if ((int) $data['id'] > 0)
            $regions = Region::where('region_id_pk', $data['id'])->get();
            else
                $regions = Region::all();
                foreach ($regions as $region) {
                    if ($data['field'] == 'status') {
                        $region->status = $data['status'];
                    }
                    $region->save($data);
                }
                return true;
    }
}