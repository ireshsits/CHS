<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\Entities\Setting;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (\Schema::hasTable('settings')) {
            $mail = explode ( ',', DB::table('settings')->where('name','MAIL_SETTINGS')->first()->value);
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver'     => $mail[0],
                    'host'       => $mail[1],
                    'port'       => $mail[2],
                    'username'   => $mail[3],
                    'password'   => $mail[4],
                    'encryption' => $mail[5]??'TLS',
                    'from'       => array('address' => $mail[6]??null, 'name' => $mail[7]??null),
//                     'sendmail'   => '/usr/sbin/sendmail -bs',
//                     'pretend'    => false,
                );
                Config::set('mail', $config);
            }
        }
    }
}