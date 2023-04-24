<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Classes\Ldap\Connector;
use App\Models\Repositories\Users\UserRepository;
use Log;

class LdapSync implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $tries = 3;
	protected $ldap;
	protected $userRepo;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->userRepo = new UserRepository();
	}
	
	/**
	 * Determine the time at which the job should timeout.
	 *
	 * @return \DateTime
	 */
	public function retryUntil()
	{
		return now()->addSeconds(5);
	}
	
	/**
	 * Delete the job if its models no longer exist.
	 *
	 * @var bool
	 */
	public $deleteWhenMissingModels = true;
	
	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$con = new Connector ();
		$this->ldap = $con->getConnection ();
		
		Log::info ( '----------------Sync Ldap Users---------------------' );
		$arr = [];
		$groups = $this->ldap->search ()->groups ()->get ();
		foreach ( $groups as $group ) {
			$role = $group->cn [0];
			$users = $group->getMembers ();
			foreach ( $users as $user ) {
				$arr [] = array (
						'first_name' => $user->getDisplayName(), //$user->displayname [0],
						'last_name' => $user->sn [0],
						'user_no' => $user->uid [0],
						'email' => $user->mail [0],
						'password' => $user->userpassword[0],
						'role' => $group->cn [0],
						'active' => 1
				);
			}
		}

		if(!empty($arr)){
			$this->userRepo->createOrUpdate($arr);
		}
	}
}
