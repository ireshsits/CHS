<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\AnalysisCategory;

class AnalysisCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {    	
    	$analyses = array (
    		array (
                'name' => 'SUMMARY'
    		),
    		array (
                'name' => 'DETAILED ANALYSIS'
    		),    		
    	    array (
    		    'name' => 'RESOLUTION ANALYSIS'
    	    ),
    	    array (
    	        'name' => 'BRANCH SERVICE LAPSES'
    	    ),
    	    array (
    	        'name' => 'GRAPHS'
    	    )
    	);
    	
    	foreach ($analyses as $analysis){
    		AnalysisCategory::create($analysis);
    	}
    	
    }
}
