<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Setting;

class UpmServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Y,CHS,http://192.125.125.237:9080/UPMWebServiceWeb/services/UPMService?wsdl,1.1,http://192.125.125.143:7003,50-40-30
        // Y,CHS,http://192.168.10.10:5566/UPMService.asmx,1.2,http://127.0.0.1:8001/api/1.0,50-40-30
        Setting::whereRaw ( 'UPPER(name) like ?', array( 'name' => 'UPM_SERVICE'))->update(['value' => 'Y,CHS,http://192.125.125.237:9080/UPMWebServiceWeb/services/UPMService?wsdl,1.1,http://192.125.125.143:7003,50-40-30']);
    }
}
