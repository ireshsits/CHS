<?php
use Illuminate\Database\Seeder;
use App\Models\Entities\BranchDepartment;

class BranchDepartmentSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         /**
//          * CR2
//          */
//         $branch1 = BranchDepartment::create([
//             'region_id_fk' => 1,
//             'sol_id' => '01',
//             'name' => 'wellawatte',
//             'type' => 'BRN'
//         ]);
//         $branch2 = BranchDepartment::create([
//             'region_id_fk' => 1,
//             'sol_id' => '02',
//             'name' => 'Nugegoda',
//             'type' => 'BRN'
//         ]);
//         $branch3 = BranchDepartment::create([
//             'region_id_fk' => 2,
//             'sol_id' => '03',
//             'name' => 'Gampaha Super Branch',
//             'type' => 'BRN'
//         ]);
//         $branch4 = BranchDepartment::create([
//             'region_id_fk' => 2,
//             'sol_id' => '04',
//             'name' => 'Negombo Super Branch',
//             'type' => 'BRN'
//         ]);
//         $branch5 = BranchDepartment::create([
//             'region_id_fk' => 1,
//             'sol_id' => '05',
//             'name' => 'Nugegoda Special Dept.',
//             'type' => 'SDEPT'
//         ]);
//         $branch6 = BranchDepartment::create([
//             'region_id_fk' => 1,
//             'sol_id' => '06',
//             'name' => 'Customer Call Centre',
//             'type' => 'SDEPT'
//         ]);
//         $branch7 = BranchDepartment::create([
//             'region_id_fk' => 1,
//             'sol_id' => '07',
//             'name' => 'Card Centre',
//             'type' => 'HDEPT'
//         ]);

        /**
         * Call center test details
         * 
         */
        
        $ccc = BranchDepartment::create([
            'sol_id' => '831',
            'name' => 'Customer Care Center',
            'type' => 'DEPT'
        ]);
        $card = BranchDepartment::create([
            'sol_id' => '960',
            'name' => 'Card Center',
            'type' => 'DEPT'
        ]);
    }
}
