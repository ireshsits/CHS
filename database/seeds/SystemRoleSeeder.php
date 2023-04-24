<?php
use Illuminate\Database\Seeder;
use App\Models\Entities\SystemRole;

class SystemRoleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemRole::truncate();
        SystemRole::create([
            'key' => 'ALL',
            'value' => 'admin|ccc|cm|zm|rm|bm|user'
        ]);
        SystemRole::create([
            'key' => 'ADMIN_ROLE',
            'value' => 'admin'
        ]);
        SystemRole::create([
            'key' => 'ADMIN_CCC_ROLE',
            'value' => 'ccc'
        ]);
        SystemRole::create([
            'key' => 'USER_ROLE',
            'value' => 'user'
        ]);
        /**
         * Zonal manager is responsible for regions
         * Chief manager is respnsible for departments
         */
        SystemRole::create([
            'key' => 'CHIEF_MNGR',
            'value' => 'cm'
        ]);
        SystemRole::create([
            'key' => 'ZONAL_MNGR',
            'value' => 'zm'
        ]);
        SystemRole::create([
            'key' => 'REGIONAL_MNGR',
            'value' => 'rm'
        ]);
        SystemRole::create([
            'key' => 'BRANCH_MNGR',
            'value' => 'bm'
        ]);
        /**
         * Added with CR3
         */
        SystemRole::create([
            'key' => 'AGM',
            'value' => 'agm'
        ]);
        SystemRole::create([
            'key' => 'DGM',
            'value' => 'dgm'
        ]);
        SystemRole::create([
            'key' => 'SDGM',
            'value' => 'sdgm'
        ]);
        SystemRole::create([
            'key' => 'HODEPT',
            'value' => 'hodept'
        ]);
        SystemRole::create([
            'key' => 'MKTG',
            'value' => 'mktg'
            
        ]);
        /**
         * Added with CR3 End
         */
        
        SystemRole::create([
            'key' => 'ADMIN_ROLES',
            'value' => 'admin'
        ]);
        SystemRole::create([
            'key' => 'CCC_ROLES',
            'value' => 'ccc'
        ]);
        SystemRole::create([
            'key' => 'USER_ROLES',
            'value' => 'user'
        ]);
        SystemRole::create([
            'key' => 'ZONAL_ADMIN_ROLES',
            'value' => 'zm'
        ]);
        SystemRole::create([
            'key' => 'ZONAL_ROLES',
            'value' => 'zm'
        ]);
        SystemRole::create([
            'key' => 'REGIONAL_ADMIN_ROLES',
            'value' => 'rm'
        ]);
        SystemRole::create([
            'key' => 'REGIONAL_ROLES',
            'value' => 'rm'
        ]);        
        SystemRole::create([
            'key' => 'BRANCH_ADMIN_ROLES',
            'value' => 'bm'
        ]);
        SystemRole::create([
            'key' => 'BRANCH_ROLES',
            'value' => 'bm'
        ]);
        
        SystemRole::create([
            'key' => 'COMPLAINT_RAISE_ROLES',
            'value' => 'ccc|cm|zm|rm|bm|user'
        ]);
        SystemRole::create([
            'key' => 'COMPLAINT_VIEW_ROLES',
            'value' => 'admin|ccc|cm|zm|rm|bm|user'
        ]);
        SystemRole::create([
            'key' => 'SOLUTION_VIEW_ROLES',
            'value' => 'admin|ccc|cm|zm|rm|bm|user'
        ]);
        SystemRole::create([
            'key' => 'ADMIN_VIEW_ROLES',
            'value' => 'admin|ccc'
        ]);
        SystemRole::create([
            'key' => 'RESOLVED_BY_ROLES',
            'value' => 'bm|rm|cm|agm|dgm|sdgm|hodept|mktg|ccc'
        ]);
        /**
		* Roles define from the system
		*/
        SystemRole::create([
            'key' => 'PROJECT_EXTRA_ROLES',
            'value' => 'cm|zm|rm|bm'
        ]);
    }
}
