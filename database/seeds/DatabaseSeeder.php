<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	/**
    	 * Temporarily disable mass assignment
    	 */
    	Model::unguard();
    	$this->call(SystemRoleSeeder::class);
//     	$this->call(SystemRoleAllSeeder::class);
    	$this->call(SettingsTableSeeder::class);    	
    	$this->call(AreaTableSeeder::class);
    	$this->call(AnalysisCategoryTableSeeder::class);   
    	$this->call(AnalysesTableSeeder::class);   
    	$this->call(ComplaintModesTableSeeder::class);
    	$this->call(CategoryTableSeeder::class);
//     	$this->call(SubCategoryTableSeeder::class);
//     	$this->call(ZoneTableSeeder::class);
    	$this->call(RegionTableSeeder::class);
    	$this->call(BranchDepartmentSeeder::class);
    	$this->call(RolesTableSeeder::class);
    	$this->call(UserTableSeeder::class);
    	$this->call(BranchDepartmentUserSeeder::class);
//     	$this->call(ComplaintSeeder::class);
        $this->call(UpmServiceSeeder::class);
        $this->call(MailServiceSeeder::class);
    	Model::reguard();
    }
}
