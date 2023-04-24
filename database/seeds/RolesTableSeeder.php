<?php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Entities\SystemRole;

class RolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * CR2
         */
        $role = Role::create([
            'name' => SystemRole::where('key','ADMIN_ROLE')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','ADMIN_CCC_ROLE')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','SDGM')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','DGM')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','AGM')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','CHIEF_MNGR')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','ZONAL_MNGR')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','REGIONAL_MNGR')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','BRANCH_MNGR')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','MKTG')->first()->value
        ]);
        $role = Role::create([
            'name' => SystemRole::where('key','USER_ROLE')->first()->value
        ]);
    }
}
