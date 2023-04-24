<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Zone;

class ZoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//
    	
    	$zone = Zone::create ( [
    			'name' => 'Western',
    	        'number' => 1
    	] );
    	
    	$zone = Zone::create ( [
    			'name' => 'Sabaragamuwa',
    	        'number' => 2
    	] );
    }
}
