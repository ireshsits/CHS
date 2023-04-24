<?php

namespace App\Helpers;

use Spatie\Permission\Models\Role;
use Auth;
use App\Models\Entities\SystemRole;
use Cache;
use Log;

class RoleHelper {
    /**
     * Only one role assigned for one user. no multiple roles can be assigned with current version.
     * Any person can be either admin, ccc, zm, rm, bm or user.
     * Othwise will face condition
     * 
     */
    
	public static function getAdminRoles($mode='ROLES') {
	    if (Cache::has ('ADMIN_ROLES')) {
	        $roleString = self::getFromCache('ADMIN_ROLES');
	    }else{
    	    $roleString = SystemRole::where('key','ADMIN_ROLES')->first()->value;
    	    self::putInCache('ADMIN_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
    	   return $roleString; 
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	/**
	 * CR2
	 */
	public static function getAllRoles($mode='ROLES') {
	    if(Cache::has('ALL')){
	        $roleString = self::getFromCache('ALL');
	    }else{
	        $roleString = SystemRole::where('key','ALL')->first()->value;
	        self::putInCache('ALL', $roleString, 'ROLE-HELPER');
	    }
		if($mode=='RAW_NAMES'){
			return $roleString;
		}
		if($mode=='NAMES'){
			return explode ( '|', $roleString);
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	public static function getUserRoles($mode='ROLES') {
	    if(Cache::has('USER_ROLES')){
	        $roleString = self::getFromCache('USER_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','USER_ROLES')->first()->value;
	        self::putInCache('USER_ROLES', $roleString, 'ROLE-HELPER');
	    }
		if($mode=='RAW_NAMES'){
		    return $roleString;
		}
		if($mode=='NAMES'){
		    return explode ( '|', $roleString);
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	public static function getAdminViewRoles($mode='ROLES') {
	    if(Cache::has('ADMIN_VIEW_ROLES')){
	        $roleString = self::getFromCache('ADMIN_VIEW_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','ADMIN_VIEW_ROLES')->first()->value;
	        self::putInCache('ADMIN_VIEW_ROLES', $roleString, 'ROLE-HELPER');
	    }
		if($mode=='RAW_NAMES'){
		    return $roleString;
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	/**
	 * End CR2
	 */
	public static function getComplaintRaiseRoles($mode='ROLES') {
	    if(Cache::has('COMPLAINT_RAISE_ROLES')){
	        $roleString = self::getFromCache('COMPLAINT_RAISE_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','COMPLAINT_RAISE_ROLES')->first()->value;
	        self::putInCache('COMPLAINT_RAISE_ROLES', $roleString, 'ROLE-HELPER');
	    }
		if($mode=='RAW_NAMES'){
		    return $roleString;
		}
		if($mode=='NAMES'){
		    return explode ( '|', $roleString);
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	public static function getComplaintViewRoles($mode='ROLES') {
	    if(Cache::has('COMPLAINT_VIEW_ROLES')){
	        $roleString = self::getFromCache('COMPLAINT_VIEW_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','COMPLAINT_VIEW_ROLES')->first()->value;	        
	        self::putInCache('COMPLAINT_VIEW_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
		    return $roleString;
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	public static function getSolutionViewRoles($mode='ROLES') {
	    if(Cache::has('SOLUTION_VIEW_ROLES')){
	        $roleString = self::getFromCache('SOLUTION_VIEW_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','SOLUTION_VIEW_ROLES')->first()->value;
	        self::putInCache('SOLUTION_VIEW_ROLES', $roleString, 'ROLE-HELPER');
	    }
		if($mode=='RAW_NAMES'){
		    return $roleString;
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	public static function getCCCRoles($mode='ROLES') {
	    if(Cache::has('CCC_ROLES')){
	        $roleString = self::getFromCache('CCC_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','CCC_ROLES')->first()->value;
	        self::putInCache('CCC_ROLES', $roleString, 'ROLE-HELPER');
	    }
		if($mode=='RAW_NAMES'){
		    return $roleString;
		}
		foreach ( explode ( '|', $roleString ) as $role ) {
			$roles [] = Role::findByName ( $role );
		}
		return $roles ?? [ ];
	}
	public static function getZonalAdminRoles($mode='ROLES') {
	    if(Cache::has('ZONAL_ADMIN_ROLES')){
	        $roleString = self::getFromCache('ZONAL_ADMIN_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','ZONAL_ADMIN_ROLES')->first()->value;
	        self::putInCache('ZONAL_ADMIN_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}
	public static function getRegionalAdminRoles($mode='ROLES') {
	    if(Cache::has('REGIONAL_ADMIN_ROLES')){
	        $roleString = self::getFromCache('REGIONAL_ADMIN_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','REGIONAL_ADMIN_ROLES')->first()->value;
	        self::putInCache('REGIONAL_ADMIN_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}	
	public static function getBranchAdminRoles($mode='ROLES') {
	    if(Cache::has('BRANCH_ADMIN_ROLES')){
	        $roleString = self::getFromCache('BRANCH_ADMIN_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','BRANCH_ADMIN_ROLES')->first()->value;
	        self::putInCache('BRANCH_ADMIN_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}	
	public static function getZonalRoles($mode='ROLES') {
	    if(Cache::has('ZONAL_ROLES')){
	        $roleString = self::getFromCache('ZONAL_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','ZONAL_ROLES')->first()->value;
	        self::putInCache('ZONAL_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}
	public static function getRegionalRoles($mode='ROLES') {
	    if(Cache::has('REGIONAL_ROLES')){
	        $roleString = self::getFromCache('REGIONAL_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','REGIONAL_ROLES')->first()->value;
	        self::putInCache('REGIONAL_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}
	public static function getBranchRoles($mode='ROLES') {
	    if(Cache::has('BRANCH_ROLES')){
	        $roleString = self::getFromCache('BRANCH_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','BRANCH_ROLES')->first()->value;
	        self::putInCache('BRANCH_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}
	public static function getAuthRoles($mode='ROLES'){
		if($mode=='RAW_NAMES'){
			
		}
		return Auth::user()->getRoleNames();
	}
	public static function getResolvedByRoles($mode='ROLES'){
	    if(Cache::has('RESOLVED_BY_ROLES')){
	        $roleString = self::getFromCache('RESOLVED_BY_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','RESOLVED_BY_ROLES')->first()->value;
	        self::putInCache('RESOLVED_BY_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}
	
	public static function getProjectExtraRoles($mode='ROLES'){
	    if(Cache::has('PROJECT_EXTRA_ROLES')){
	        $roleString = self::getFromCache('PROJECT_EXTRA_ROLES');
	    }else{
	        $roleString = SystemRole::where('key','PROJECT_EXTRA_ROLES')->first()->value;
	        self::putInCache('PROJECT_EXTRA_ROLES', $roleString, 'ROLE-HELPER');
	    }
	    if($mode=='RAW_NAMES'){
	        return $roleString;
	    }
	    foreach ( explode ( '|', $roleString ) as $role ) {
	        $roles [] = Role::findByName ( $role );
	    }
	    return $roles ?? [ ];
	}
	
	
	/**
	 * get each single role
	 */
	public static function getAdminCCCRole(){
	    if(Cache::has('ADMIN_CCC_ROLE')){
	        return self::getFromCache('ADMIN_CCC_ROLE');
	    }else{
	        $roleString = SystemRole::where('key','ADMIN_CCC_ROLE')->first()->value;
	        self::putInCache('ADMIN_CCC_ROLE', $roleString, 'ROLE-HELPER');
	        return $roleString;
	    }
	}
	public static function getUserRole(){
	    if(Cache::has('USER_ROLE')){
	       return self::getFromCache('USER_ROLE');
	    }else{
	        $roleString = SystemRole::where('key','USER_ROLE')->first()->value;
	        self::putInCache('USER_ROLE', $roleString, 'ROLE-HELPER');
	        return $roleString;
	    }
	}
	public static function getZonalManagerRole() {
	    if(Cache::has('ZONAL_MNGR')){
	        return self::getFromCache('ZONAL_MNGR');
	    }else{
	        $roleString = SystemRole::where('key','ZONAL_MNGR')->first()->value;
	        self::putInCache('ZONAL_MNGR', $roleString, 'ROLE-HELPER');
	        return $roleString;
	    }
	}
	public static function getRegionalManagerRole() {
	    if(Cache::has('REGIONAL_MNGR')){
	        return self::getFromCache('REGIONAL_MNGR');
	    }else{
	        $roleString = SystemRole::where('key','REGIONAL_MNGR')->first()->value;
	        self::putInCache('REGIONAL_MNGR', $roleString, 'ROLE-HELPER');
	        return $roleString;
	    }
	}

	private static function putInCache($key, $content, $tag) {
	    Log::info('Put in Cache >>'.json_encode($key));
	    Cache::put ( $key, json_encode ( $content ), 1800 ); //30 minutes
	}
	private static function getFromCache($key) {
// 	    Log::info('Fetch from Cache >>'.json_encode($key));
	    return json_decode ( Cache::get ( $key ) );
	}
}