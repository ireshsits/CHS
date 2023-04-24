<?php

namespace App\Http\Controllers\Areas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Areas\AreasInterface;

class AreaController extends Controller
{
    protected $area;
    public function __construct(AreasInterface $interface){
        $this->area = $interface;
    }
    
    public function getAreaForSearch(Request $request) {
        return json_encode ( array (
            "result" => "success",
            'items' => $this->area->getAreaForSearch( $request->all () )
        ) );
    }
}
