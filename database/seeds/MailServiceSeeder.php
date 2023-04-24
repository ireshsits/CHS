<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Setting;

class MailServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::whereRaw ( 'UPPER(name) like ?', array( 'name' => 'MAIL_SETTINGS'))->update(['value' => 'smtp,smtp.cumulus.lk,586,chs@sits.lk,Sits_chs@123,TLS,chs@sits.lk,Help Desk']);
//        Setting::updateOrCreate([
//            'name' => 'MAIL_SETTINGS',
//            'value' => 'smtp,smtp.cumulus.lk,586,chs@sits.lk,Sits_chs@123,TLS,chs@sits.lk,Help Desk'
//        ]);
    }
}
