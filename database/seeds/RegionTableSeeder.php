<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Region;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$region = Region::create ( [
    			'name' => 'Region 1',
    	        'number' => 1
    	] );
    	
    	$region = Region::create ( [
    	    'name' => 'Region 2',
    	    'number' => 2
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 3',
    	    'number' => 3
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 4',
    	    'number' => 4
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 5',
    	    'number' => 5
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 6',
    	    'number' => 6
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 7',
    	    'number' => 7
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 8',
    	    'number' => 8
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 9',
    	    'number' => 9
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 10',
    	    'number' => 10
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 11',
    	    'number' => 11
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 12',
    	    'number' => 12
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 13',
    	    'number' => 13
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 14',
    	    'number' => 14
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 15',
    	    'number' => 15
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 16',
    	    'number' => 16
    	] );
    	$region = Region::create ( [
    	    'name' => 'Region 17',
    	    'number' => 17
    	] );
    	
    }
}
