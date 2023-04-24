<?php

namespace App\Http\Controllers\Configurations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entities\Setting;

class ConfigController extends Controller
{
    /**
     * Show the application author manage page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {
            return view ( 'dashboard.configurations.manage', [
                'mailSettings' => Setting::getMailSettings(),
                'upmSettings' => Setting::getUPMSettings()
            ] );
        } catch ( \Exception $e ) {
            abort ( 404 );
        }
    }
    
    public function updateConfigurations(Request $request){
        foreach ($request->all() as $key=>$value){
            $this->updateEnv($key, $value);
        }
        return back()->withInput();
    }
    protected function updateEnv($key, $newValue, $delim = '')
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            // get old value from current env
            if(is_bool(env($key))){
                $oldValue = env($key)? true : false;
                $newValue = $newValue == 'true'? true : false;
            }elseif(env($key)===null){
                $oldValue = null;
            }else
                $oldValue = env($key);
            
            // was there any change?
            if ($oldValue === $newValue)
                return;
            
            // rewrite file content with changed data
            if (file_exists($path)) {
                // replace current value with new value
//                 file_put_contents($path, str_replace($key . '=' . $delim . $oldValue . $delim, $key . '=' . $delim . $newValue . $delim, file_get_contents($path)));
                   file_put_contents($path, str_replace($key . '=' .$oldValue, $key . '=' .$newValue , file_get_contents($path)));
            }
        }
    }
}
