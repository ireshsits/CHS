<?php

namespace App\Models\Repositories\Users;

use DateHelper;
use App\Models\Entities\User;
use App\Models\Entities\BranchDepartmentUser;
use App\Models\Entities\BranchDepartment;
use App\Models\Entities\SystemRole;
use Log;
use UserHelper;
use RoleHelper;
use Adldap;
use App\Classes\UPM\Sync;

class UserRepository {
    public function __construct() {
    }
    private function generateUsersSearchQuery($data){
        $query = User::select('*')->whereHas('roles', function ($q) {
            $q->whereIn('name', ['user']);
        });
        if(isset($data ['name']))
            $query->whereRaw('LOWER(first_name) like ?', array (
                    'first_name' => '%' . strtolower ( $data ['name'] ) . '%'
                ))->orWhereRaw('LOWER(last_name) like ?', array (
                    'last_name' => '%' . strtolower ( $data ['name'] ) . '%'
                ))->orWhere ('email', 'like', '%' . $data ['name'] . '%' );
            
            /**
             * Omit logged in user for search select lists
             */
            $query->where('user_id_pk', '!=', UserHelper::getLoggedUserId());
            return $query->where('active', true)->distinct();
    }

    private function generateAllUsersSearchQuery($data) {
        $query = User::select('*');
        // $query->whereHas('roles', function ($q) {
        //     $q->whereIn('name', ['user']);
        // });
        if(isset($data ['name']))
            $query->whereRaw('LOWER(first_name) like ?', array (
                    'first_name' => '%' . strtolower ( $data ['name'] ) . '%'
                ))->orWhereRaw('LOWER(last_name) like ?', array (
                    'last_name' => '%' . strtolower ( $data ['name'] ) . '%'
                ))->orWhere ('email', 'like', '%' . $data ['name'] . '%' );
        /**
         * Omit logged in user for search select lists
         */
        $query->where('user_id_pk', '!=', UserHelper::getLoggedUserId());
        return $query->where('active', true)->distinct();
    }

    private function buildUsersSearchResponse($user) {
        $user->id = $user->user_id_pk;
        $user->text = $user->first_name.' '.$user->last_name.' - <code>'.$user->email.'</code>';
        $user->display_user = true;
        return $user;
    }
    public function getUsersForManagerSearch(array $filters) {
        $queryString = $this->generateUsersSearchQuery ( $filters );
        $branchDepartmentsUsers = $queryString->get ();
        foreach ( $branchDepartmentsUsers as $ukey => $user ) {
            $users[] = $this->buildUsersSearchResponse( $user );
        }
        return $users;
    }
    public function getAllUsers(array $filters) {

        try {

            $queryString = $this->generateAllUsersSearchQuery ( $filters );
            $Users = $queryString->get ();
            foreach ( $Users as $ukey => $user ) {
                $users[] = $this->buildUsersSearchResponse( $user );
            }
            return json_encode(array(
                "result" => "success",
                'items' => $users
            ));
        
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    
    public function loginUserSync($attrbute, $username){
    	$user = User::where($attrbute, $username)->first();
    	if (!$user) {
    		
    		$user = new User();
    		$user->username = $username;
    		$user->password = '';
    		
    		$sync_attrs = $this->retrieveSyncAttributes($username);
    		foreach ($sync_attrs as $field => $value) {
    			$user->$field = $value !== null ? $value : '';
    		}
    	}
    	if(!$user->hasAnyRole(RoleHelper::getAllRoles('NAMES'))){
    		$user->assignRole('user');
    	}
    	return $user;
    }
    
    protected function retrieveSyncAttributes($username)
    {
    	$ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();
    	if (!$ldapuser) {
    		// log error
    		return false;
    	}
    	$ldapuser_attrs = null;
    	$attrs = [];
    	foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
    		if ($local_attr == 'username') {
    			continue;
    		}
    		$method = 'get' . $ldap_attr;
    		if (method_exists($ldapuser, $method)) {
    			$attrs[$local_attr] = $ldapuser->$method();
    			continue;
    		}
    		if ($ldapuser_attrs === null) {
    			$ldapuser_attrs = self::accessProtected($ldapuser, 'attributes');
    		}
    		if (!isset($ldapuser_attrs[$ldap_attr])) {
    			// an exception could be thrown
    			$attrs[$local_attr] = null;
    			continue;
    		}
    		if (!is_array($ldapuser_attrs[$ldap_attr])) {
    			$attrs[$local_attr] = $ldapuser_attrs[$ldap_attr];
    		}
    		if (count($ldapuser_attrs[$ldap_attr]) == 0) {
    			// an exception could be thrown
    			$attrs[$local_attr] = null;
    			continue;
    		}
    		$attrs[$local_attr] = $ldapuser_attrs[$ldap_attr][0];
    		//$attrs[$local_attr] = implode(',', $ldapuser_attrs[$ldap_attr]);
    	}
    	
    	return $attrs;
    }
    
    protected static function accessProtected($obj, $prop)
    {
    	$reflection = new \ReflectionClass($obj);
    	$property = $reflection->getProperty($prop);
    	$property->setAccessible(true);
    	return $property->getValue($obj);
    }
    
    
    /**
     * UPM Sync .................................
     */
    
    public function getUserInstance($params){
        return User::where('emp_id', $params['USER_ID'])->first();
    }
    
    public function upmUserSync($users){
    
        // for email existing validation
//        if ($this->validateEmailExist($users, 'EMAIL') == false) {
//            return false;
//        }
    
        foreach($users as $user){
//            $userModel = User::firstOrNew(array (
//                'emp_id' => $user ['USER_ID'],
//            ));
//            $userModel->fill([
//                'emp_id' => $user['USER_ID'],
//                'first_name' => $user['FIRST_NAME'],
//                'last_name' => $user['LAST_NAME'],
//                'email' => $user['EMAIL']??null,
//                'active' => $user['STATUS'] == 1? true : false
//            ]);
//            $userModel->save();
            
            $userModel = User::where('emp_id', $user ['USER_ID'])->first();
            
            if (isset($userModel)) {
                if ($userModel->email != $user['EMAIL']) {
                    $userModel->fill([
                        'email' => $user['EMAIL']
                    ]);
                }

                $userModel->fill([
                    'emp_id' => $user['USER_ID'],
                    'first_name' => $user['FIRST_NAME'],
                    'last_name' => $user['LAST_NAME'],
                    // 'email' => $user['EMAIL']??null,
                    'active' => $user['STATUS'] == 1? true : false
                ]);
                $userModel->save();
            } else {
                $userModel = User::firstOrNew([
                    'emp_id' => $user ['USER_ID'],
                ]);
                $userModel->fill([
                    'emp_id' => $user['USER_ID'],
                    'first_name' => $user['FIRST_NAME'],
                    'last_name' => $user['LAST_NAME'],
                    'email' => $user['EMAIL']??null,
                    'active' => $user['STATUS'] == 1? true : false
                ]);
                $userModel->save();
            }
            
            $userModel->syncRoles([$user['ROLE']]);
            if($user['ROLE'] == SystemRole::where('key','USER_ROLE')->first()->value && !$userModel->hasAnyRole(RoleHelper::getProjectExtraRoles('NAMES')))
                $userModel->syncRoles([$user['ROLE']]);
            else
                $userModel->syncRoles([$user['ROLE']]);
            
            if(isset($user['SOL_ID'])){
                $branch = BranchDepartment::where('sol_id', (int)$user['SOL_ID'])->first();
                if(!$branch){
                    $sync = new Sync();
                    $sync->getSolInformationByEmployeeID($user['USER_ID']);
                    $branch = BranchDepartment::where('sol_id', (int)$user['SOL_ID'])->first();
                }
                $branchUserModel = BranchDepartmentUser::firstOrNew(array(
                    'user_id_fk' => $userModel->user_id_pk
                ));
                $branchUserModel->fill([
                    'branch_department_id_fk' => $branch->branch_department_id_pk,
                    'user_id_fk' => $userModel->user_id_pk
                ])->save();
            }
        }
    }
    
    function validateEmailExist($array, $key) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key])
                return true;
        return false;
    }
    
}