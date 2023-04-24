<?php

namespace App\Classes\Ldap\Quries;

use Auth;
use App\Classes\Ldap\Connector;
use Adldap;
use Adldap\Schemas\ActiveDirectory;


class Fetch {
	
	protected $bankDn;
	protected $params;
	public function __construct(){
		$con = new Connector ();
		$this->ldap = $con->getConnection ();
		// 		$this->params->$params;
		
		$config = Adldap::getConfiguration();
		$baseDn = new Adldap\Models\Attributes\DistinguishedName(env ( 'LDAP_BASE_DN', '' ));
// 		$this->bankDn = $baseDn->addOu('Sampath Bank');
// 		dd($this->bankDn);
	}
	
	public function getBranches(){
		// 		$search = Adldap::search()->setDn($branchDn->get());
		// 		$search = Adldap::search()->setDn($baseDn);
		// 		$entries = $search->whereEquals(ActiveDirectory::OBJECT_CATEGORY, ActiveDirectory::ORGANIZATIONAL_UNIT_LONG)->get();
		// $entries=$search->where('objectClass','=','top;organizationalUnit');
		// 		$ous = $this->ldap->search()->ous()->where(['objectclass' => 'organizationalUnit'])->setDn($branchDn->get())->get();
		// 		foreach($ous as $ou){
		// 			if($ou->ou [0] == 'Users'){
		// 				$UserDn = $baseDn->addOu('Users');
		// 			}
		// 		}
		// 		$entries = $this->ldap->search()->users()->where(['objectclass' => 'organizationalUnit'])->setDn($branchDn->get())->get();
		// 		dd($entries);
		
		// 		$groups = $this->ldap->search ()->groups ()->get ();
		// 		foreach ( $groups as $group ) {
		// 			if($group->cn [0] == 'Users'){
		// 				$users = $group->getMembers ();
		// 				foreach ( $users as $user ) {
		// 				}
		// 			}
		// 		}
		
		// 		$users = $this->ldap->search()->findByDn('ou=Branches,dc=test,dc=local');
		// 		$branchOu = $this->ldap->search()->setDn('ou=Branches');
		// 		if($branchOu) {
		// Retrieve users by the OU's DN
		
		
		$branchDn = $this->ldap->search()->setDn('ou=Branches');
		// 		dd($branchDn);
		$bankBranchDn = $this->bankDn->addOu('ou=Branches');//Working
		// 		dd($bankBranchDn);
		$BranchUsersDn=$this->ldap->search()->ous()->where(['objectclass' => 'organizationalUnit'])->setDn('ou=Branches,ou=Sampath Bank,dc=test,dc=local')->get();//Working
		// 		dd($BranchUsersDn);
		$branches = $this->ldap->search()->ous()->where(['objectclass' => 'organizationalUnit'])->setDn('ou=Branches,dc=test,dc=local')->get();
		// 		dd($branches);
		$bank = $this->ldap->search()->ous()->where(['objectclass' => 'organizationalUnit'])->setDn('ou=Branches,ou=Sampath Bank,dc=test,dc=local')->get();//Working
		// 		$users = $this->ldap->search()->users()->where(['distinguishedname','Like' ,'%ou=Branches,dc=test,dc=local'])->get();
		// 		dd($users);
		// 		$BranchUserOUs = $this->ldap->search()->ous()->where(['ou'=> 'Users'])->setDn($branchDn->get())->get();
		$BranchUserOUs = $this->ldap->search()->ous()->where(['ou'=> 'Users'])->setDn($branchDn->get())->get();
		// 		dd($BranchUserOUs);
		// 		$users = $this->ldap->search()->findByDn('ou=Branches,dc=test,dc=local');
		// 		dd($users);
		$users = $this->ldap->search()->users()->setDn('ou=Branches,ou=Sampath Bank,dc=test,dc=local')->get();//Users of Branches W
		// 		dd($users);
		$groups = $this->ldap->search ()->groups()->setDn('ou=Branches,ou=Sampath Bank,dc=test,dc=local')->get ();//Groups of Branches W
		// 		dd($groups);
		// 		$test=$this->ldap->search()->users()->getDisplayname();
		// 		dd($test);
		// 		$SampathBranchUserOUs = $this->ldap->search()->ous()->where(['ou'=> 'Users'])->setDn($bankDn->get())->get();
		
		foreach ($users as $user){
			$Branchusers = $user->getdistinguishedname();
			dd($Branchusers);
		}
		$users->save();
		foreach ($groups as $group){
			$Branchgroups = $group->getdistinguishedname();
			// 			dd($Branchgroups);
		}
		$groups->save();
		foreach ( $BranchUserOUs as $ou ){
			/**
			 * One Branch Iteration
			 * @var unknown $users
			 */
			$users = $this->ldap->search()->users()->setDn($ou->getDn())->get();
			/**
			 * Add/Update Branch From First User
			 */
			
			foreach ($users as $user){
				if($user->dn [0] == 'Users'){
					$Branchusers = $user->getdistingushedname();
					// 					foreach ( $users as $user ) {
					// 						}
					
				}
			}
			// 			$users = $this->ldap->search()->where(['distinguishedname','Like' ,'%ou=Users,dc=test,dc=local'])->setDn($ou->getDn())->get();
			
			$users = $this->ldap->search()->setDn($ou->getObjectCategoryDn())->get();
			
			
		}
		
		
		
	}
	
}