<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Area;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){    	
    	Area::truncate();
    	$areas=array(
    	    array(
    	        'name'=> 'ADMINISTRATION',
    	        'short_name'=> 'Adminis.',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'BRANCH CREDIT',
    	        'short_name' => 'Brn. Crdt',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'BRANCH SERVICE LAPSES',
    	        'short_name' => 'B. S. Lapses',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'CARD CENTER',
    	        'short_name' => 'C. Center',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'CCC',
    	        'short_name'=> 'CCC',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'COMPLIANCE RELATED ISSUES',
    	        'short_name'=> 'C.R. Issues',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'DIGITAL BANKING UNIT',
    	        'short_name'=> 'D. Ban. Unit',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'EBU',
    	        'short_name'=> 'EBU',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'EREM',
    	        'short_name'=> 'EREM',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'HR',
    	        'short_name'=> 'HR',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'INWARD REMITTANCES',
    	        'short_name'=> 'In. Remitt.',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'IT',
    	        'short_name'=> 'IT',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'MKTG',
    	        'short_name'=> 'Mktg',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'OPERATIONS',
    	        'short_name'=> 'Operations',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'RECOVERIES',
    	        'short_name'=> 'Recoveries',
    	        'status' => 'ACT'
    	    ),
    	    array(
    	        'name'=> 'TRADE SERVICES',
    	        'short_name'=> 'T. Services',
    	        'status' => 'ACT'
    	    ),
    	);
    	foreach ( $areas as $area ) {
    	    $area['color'] = ColorGenerator::generate();
    	    Area::create ( $area );
    	}
    }
}
