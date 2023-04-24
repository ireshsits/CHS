<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\ComplaintMode;

class ComplaintModesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$mode = ComplaintMode::create([ //CCC
    			'name' => 'Dedicated Complaint Email',
    	    	'short_name' => 'Email',
    	    	'code' => 'E',
    			'color' => '#16A085'
    	]);
    	
    	$mode= ComplaintMode::create([ //CCC
    			'name' => 'Dedicated Complaint Mobile',
    			'short_name' => 'Mobile',
    	    	'code' => 'M',
    			'color' => '#9B59B6'
    	]);
    	
    	$mode= ComplaintMode::create([ //CCC
    	    	'name' => 'Inbound Calls to CCC',
    	    	'short_name' => 'Calls to CCC',
    	    	'code' => 'CC',
    	    	'color' => '#ff8f00'
    	]);
    	
    	$mode = ComplaintMode::create([ //CCC
    			'name' => 'Social Media',
    			'short_name' => 'S. Media',
    	    	'code' => 'SM',
    			'color' => '#2C3E50'
    	]);
    	
    	$mode = ComplaintMode::create([
    			'name' => 'Branch/Department',
    			'short_name' => 'Brn/Dept.',
    	    	'code' => 'B',
    			'color' => '#E74C3C'
    	]);
    	
    	$mode= ComplaintMode::create([ //Manually By CCC
    			'name' => 'CSAT',
    			'short_name' => 'CSAT',
    	    	'code' => 'CS',
    			'color' => '#F1C40F'
    	]);
    	
    	$mode= ComplaintMode::create([
    			'name' => 'Corporate Management',
    			'short_name' => 'C. Mgt.',
    	    	'code' => 'CM',
    			'color' => '#7F8C8D'
    	]);
    	
    	$mode= ComplaintMode::create([
    			'name' => 'Post',
    			'short_name' => 'Post',
    	    	'code' => 'P',
    			'color' => '#2980B9'
    	]);
    }
}
