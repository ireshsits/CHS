<?php

namespace App\Http\Controllers\Zones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Zones\ManageZonesRepository;

class ZoneController extends Controller
{
    public function __construct() {
        $this->manageZonesRepo = new ManageZonesRepository ();
    }
    
    public function index($encodedURI = null, Request $request)
    {
        try {
            $params = substr($encodedURI, strrpos($encodedURI, '&') + 1);
            $paramsList = explode('&', $encodedURI);
            if($params){
                $zone = $this->manageZonesRepo->getZone ( [
                    'zone_id_pk' => explode('=', $paramsList[1])[1], //explode('=', $params)[1],
                ] );
                return view('dashboard.zones.setup', [
                    'mode' => 'edit',
                    'zone' => $zone
                ]);
            }else{
                return view('dashboard.zones.setup', [
                    'mode' => 'new',
                    'zone' => collect()
                ]);
            }
        }catch(\Exception $e){
            abort ( 404 );
        }
    }
    
    public function syncRegion(Request $request){
        return $this->manageZonesRepo->syncRegion($request->all());
    }
}

