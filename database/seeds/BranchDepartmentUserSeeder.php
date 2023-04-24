<?php
use Illuminate\Database\Seeder;
use App\Models\Entities\BranchDepartmentUser;

class BranchDepartmentUserSeeder extends Seeder
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
//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 6,
//             'user_id_fk' => 2
//         ]);

//         $branchManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 1,
//             'user_id_fk' => 3
//         ]);

//         $branchManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 1,
//             'user_id_fk' => 4
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 3,
//             'user_id_fk' => 5
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 3,
//             'user_id_fk' => 6
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 4,
//             'user_id_fk' => 7
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 4,
//             'user_id_fk' => 8
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 2,
//             'user_id_fk' => 9
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 2,
//             'user_id_fk' => 10
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 7,
//             'user_id_fk' => 11
//         ]);

//         $branchAManageUser = BranchDepartmentUser::create([
//             'branch_department_id_fk' => 7,
//             'user_id_fk' => 12
//         ]);
    
        /**
         * Call center test details
         */
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 1,
            'user_id_fk' => 1
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 1,
            'user_id_fk' => 2
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 2,
            'user_id_fk' => 3
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 2,
            'user_id_fk' => 4
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 2,
            'user_id_fk' => 5
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 2,
            'user_id_fk' => 6
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 2,
            'user_id_fk' => 7
        ]);
        BranchDepartmentUser::create([
            'branch_department_id_fk' => 2,
            'user_id_fk' => 8
        ]);
        
    }
}
