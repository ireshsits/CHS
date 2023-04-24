<?php

namespace App\Models\Repositories\Areas;

use DateHelper;
use UserHelper;
use App\Models\Entities\Area;
use DataTables;

class ManageAreaRepository implements AreasInterface {
    public function __construct() {
    }
    private function generateAreaSearchQuery($data) {
        $query = Area::select ( '*' );
        if(isset($data['name']))
            $query->whereRaw ( 'LOWER(name) like ?', array (
                'name' => '%' . strtolower ( $data ['name'] ) . '%'
            ) );
            return $query->where('status','ACT')->distinct ();
    }
    private function generateQuery($filters) {
        $query = Area::select ( '*' );
        $query->with ( [
            'rejectedBy'
        ] );
        
        if (isset ( $filters ['area_id_pk'] ))
            $query->where ( 'area_id_pk', $filters ['area_id_pk'] );
        if (isset ( $filters ['random'] ))
            $query->inRandomOrder ();
        if (isset ( $filters ['limit'] ))
            $query->limit ( $filters ['limit'] );
        return $query;
    }
    private function buildResponse($area) {
        return $area;
    }
    private function buildSearchResponse($area) {
        $area->id = $area->area_id_pk;
        $area->text = $area->name;
        $area->display_area = true;
        return $area;
    }
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getArea($filters) {
        $queryString = $this->generateQuery ( $filters );
        $area = $queryString->first ();
        return $this->buildResponse ( $area );
    }
    
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getAreas($filters) {
        $queryString = $this->generateQuery ( $filters );
        $datatable = DataTables::of ( $queryString )->addColumn ( 'id', function ($area) {
            return $area->area_id_pk;
        } )->addColumn ( 'rejected_by', function ($area) {
            return (isset($area->rejectedBy) ? $area->rejectedBy->first_name.' '.$area->rejectedBy->last_name : '');
        } )->editColumn ( 'created_at', function ($area) {
            return DateHelper::formatToDateString ( $area->created_at );
        } )->removeColumn ( 'rejected_by_fk');
        
        return $datatable->toJson ();
    }
    /**
     *
     * @param unknown $params
     * @return unknown
     */
    public function saveOrEditArea($data){
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
                            'area_id_pk' => $data ['id']
                        );
                    }
                }
                $area = Area::firstOrNew ( $filter );
                $area->fill ( $data );
                $area->save ();
                return $area;
            } catch ( \Exception $e ) {
                dd ( $e );
            }
    }
    /**
     *
     * @param unknown $filters
     * @return unknown
     */
    public function getAreaForSearch($filters) {
        $queryString = $this->generateAreaSearchQuery ( $filters );
        $response = $queryString->get ();
        foreach ( $response as $area ) {
            $area = $this->buildSearchResponse ( $area );
        }
        return $response;
    }
    public function areaDelete($id) {
        $area = Area::withTrashed ()->where ( 'area_id_pk', $id )->first ();
        if ($area->trashed ()) {
            return true;
        }
        return $area->delete ();
    }
    public function statusAreaUpdate($data) {
        if((int) $data['id'] > 0)
            $areas = Area::where ( 'area_id_pk', $data ['id'] )->get ();
            else
                $areas = Area::all();
                foreach ($areas as $area){
                    if ($data ['field'] == 'status'){
                        $area->status = $data ['status'];
                    }
                    $area->save ( $data );
                }
                return true;
    }
}