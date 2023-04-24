<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\User;

class UserTableAddOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
    	$userPcu = User::where('email','pcu@sampath.lk')->update(['first_name' => 'Piyumika', 'last_name' => 'Bandara', 'emp_code' => '70971']);
    	
    	//23
    	$dh15 = User::create ( array (
    			
    			'first_name' => 'Hasitha',
    			'last_name' => 'Bandara',
    			'email' => 'custserv11@sb.sampath.lk',
    			'username' => 'custserv11@sb.sampath.lk',
    			'emp_id' => '61719',
    			'password' => Hash::make ( '123123' ), // hashes our password nicely for us,
    			'active' => 1
    	) );
    	
    	$dh15->assignRole ( 'manager' );
    	//24
    	$dh16 = User::create ( array (
    			
    			'first_name' => 'Vakini',
    			'last_name' => 'Visakan',
    			'email' => 'custserv4@sb.sampath.lk',
    			'username' => 'custserv4@sb.sampath.lk',
    			'emp_id' => '50067',
    			'password' => Hash::make ( '123123' ), // hashes our password nicely for us,
    			'active' => 1
    	) );
    	
    	$dh16->assignRole ( 'manager' );
    	//25
    	$dh17 = User::create ( array (
    			
    			'first_name' => 'Dilakshi',
    			'last_name' => 'Perera',
    			'email' => 'custserv13@sb.sampath.lk',
    			'username' => 'custserv13@sb.sampath.lk',
    			'emp_id' => '49328',
    			'password' => Hash::make ( '123123' ), // hashes our password nicely for us,
    			'active' => 1
    	) );
    	
    	$dh17->assignRole ( 'manager' );
    	//26
    	$dh18 = User::create ( array (
    			
    			'first_name' => 'Mahendra',
    			'last_name' => 'Kamal',
    			'email' => 'pcu01@sb.sampath.lk',
    			'username' => 'pcu01@sb.sampath.lk',
    			'emp_id' => '72370',
    			'password' => Hash::make ( '123123' ), // hashes our password nicely for us,
    			'active' => 1
    	) );
    	
    	$dh18->assignRole ( 'manager' );
    	//27
    	$dh19 = User::create ( array (
    			
    			'first_name' => 'Pramanathan',
    			'last_name' => 'Pathmaruban',
    			'email' => 'premiumcards@sampath.lk',
    			'username' => 'premiumcards@sampath.lk',
    			'emp_id' => '63282',
    			'password' => Hash::make ( '123123' ), // hashes our password nicely for us,
    			'active' => 1
    	) );
    	
    	$dh19->assignRole ( 'manager' );
    }
}
