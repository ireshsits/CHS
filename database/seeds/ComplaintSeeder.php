<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Complaint;
use App\Models\Entities\ComplaintUser;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$complaint = Complaint::create([
    			'reference_number' => 'E_2005-0002',
    			'category_id_fk' => 1,
    			'sub_category_id_fk' => 1,
    			'complaint_mode_id_fk' => 1,
    	        'account_no' => '573489674875934',
    	        'priority_level' => 'LOW',
    			'complaint' => 'Seeded Complaint',
    			'type' => 'CMPLA',
    			'open_date' => \DateHelper::getDate (),
    			'status' => 'PEN'
    	]);
    	
    	$user1 = ComplaintUser::create([
    			'complaint_id_fk' => $complaint->complaint_id_pk,
    			'user_id_fk' => 3,
    			'branch_department_id_fk' => 1,
    			'user_role' => 'RECPT',
    	        'assigned_by_id_fk' => 3, 
    			'role_index' => 1,
    			'status' => 'ACT'
    	]);
    	
    	$user2 = ComplaintUser::create([
    	    'complaint_id_fk' => $complaint->complaint_id_pk,
    	    'user_id_fk' => 5,
    	    'branch_department_id_fk' => 3,
    	    'user_role' => 'OWNER',
    	    'assigned_by_id_fk' => 3,
    	    'role_index' => 1,
    	    'status' => 'ACT'
    	]);
    	
    	$user2 = ComplaintUser::create([
    			'complaint_id_fk' => $complaint->complaint_id_pk,
    			'user_id_fk' => 7,
    			'branch_department_id_fk' => 4,
    			'user_role' => 'OWNER',
    	        'assigned_by_id_fk' => 3,
    			'role_index' => 1,
    			'status' => 'ACT'
    	]);
    }
}
