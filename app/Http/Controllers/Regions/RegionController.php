<?php
namespace App\Http\Controllers\Regions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Regions\ManageRegionsRepository;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->manageRegionsRepo = new ManageRegionsRepository();
    }

    public function index($encodedURI = null, Request $request)
    {
        try {
            $params = substr($encodedURI, strrpos($encodedURI, '&') + 1);
            $paramsList = explode('&', $encodedURI);
            if ($params) {
                $region = $this->manageRegionsRepo->getRegion([
                    'region_id_pk' => explode('=', $paramsList[1])[1], //explode('=', $params)[1]
                ]);
                return view('dashboard.regions.setup', [
                    'mode' => 'edit',
                    'region' => $region
                ]);
            } else {
                return view('dashboard.regions.setup', [
                    'mode' => 'new',
                    'region' => collect()
                ]);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function syncBranch(Request $request)
    {
        return $this->manageRegionsRepo->syncBranch($request->all());
    }
}
