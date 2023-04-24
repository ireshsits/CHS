<?php

namespace App\Models\Repositories\Areas;

interface AreasInterface {
    
    public function getArea($filters);
    public function getAreas($filters);
    public function saveOrEditArea($data);
    public function getAreaForSearch($filters);
    public function areaDelete($filters);
    public function statusAreaUpdate($filters);
    
}