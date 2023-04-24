<?php

namespace App\Models\Repositories\Modes;

interface ModesInterface {
    
    public function getMode($filters);
    public function getModes($filters);
    public function saveOrEditMode($data);
    public function statusModeUpdate($data);
    public function modeDelete($id);
    
}