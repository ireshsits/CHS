<?php
use Illuminate\Database\Seeder;
use App\Models\Entities\SystemRole;

class SystemRoleAllSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		
		SystemRole::create ( [ 
				'key' => 'ALL',
				'value' => 'admin|ccc|user|zm|rm|bm' 
		] );
	}
}
