<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\Repositories\Users\UserRepository;
use Adldap; 
use App\Classes\Ldap\Quries\Fetch;
use App\Classes\Ldap\Connector;
use Adldap\Auth\Events\Failed;
use App\Classes\UPM\Sync;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $userRepo;
    protected $upmService;
    public function __construct()
    {
    	$this->middleware('guest')->except('logout');
    	$this->userRepo = new UserRepository();
    	if (config('app.authentication') == 'LDAP') {
//         	$con = new Connector ();
//         	$this->ldap = $con->getConnection ();
    	}else if (config('app.authentication') == 'UPM') {
    	    $this->upmService = new Sync();
    	}
    }
    
    public function showLoginForm() {
//     	$fetch = new Fetch();
//     	$fetch->getBranches();
    	return view ( 'auth.login' );
    }
    
    /**
     * Get a validator for an incoming login request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
    	/**
    	 * Choose username /email
    	 */
    	if (! filter_var ( $data ["userNameOrEmail"], FILTER_VALIDATE_EMAIL )) {
    		return Validator::make ( $data, [
    				'userNameOrEmail' => 'required|max:255|exists:users,username',
    				'password' => 'required|min:3|'
    		] );
    	} else {
    		return Validator::make ( $data, [
    				'userNameOrEmail' => 'required|email|max:255|exists:users,email',
    				'password' => 'required|min:3|'
    		] );
    	}
    }
    
    public function username() {
    	return config ( 'ldap_auth.identifiers.database.username_column' );
// 		return config('ldap_auth.usernames.eloquent');
    }
    protected function ldapValidator(array $data) {
    	/**
    	 * Choose user_no /email
    	 */
    	return Validator::make ( $data, [
    			$this->username () => 'required|string|',
    			'password' => 'required|min:3|'
    	] );
    }
    protected function upmValidator(array $data){
        /**
         * Choose user_no /email
         */
        return Validator::make ( $data, [
            'empId' => 'required|string|',
            'password' => 'required|min:3|'
        ] );
    }
    
    
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request) {
    	/**
    	 * Validate
    	 */
    	if (config('app.authentication') == 'LDAP') {

    		$this->ldapValidator ( $request->all () )->validate ();
    		$credentials = $request->only ( $this->username (), 'password' );
    		
    		$username = $credentials [$this->username ()];
    		$password = $credentials ['password'];
    		
    		//openLdap
    		//$user_format = env ( 'LDAP_USER_FORMAT', 'uid=%s,' . env ( 'LDAP_USER_DN', '' ) . ',' . env ( 'LDAP_BASE_DN', '' ) );
    		
    		//AD
//     		$user_format = env('LDAP_USER_FORMAT');
//     		$userdn = sprintf ( $user_format, $username );
    		if((Adldap::auth()->attempt($username, $password, $bindAsUser = true))){
// 			if(Adldap::auth()->attempt($userdn, $password, $bindAsUser = true)){
				
    			$user = $this->userRepo->loginUserSync ( $this->username (), $username );
    			// by logging the user we create the session, so there is no need to login again (in the configured time).
    			// pass false as second parameter if you want to force the session to expire when the user closes the browser.
    			// have a look at the section 'session lifetime' in `config/session.php` for more options.
    			
    			$this->guard ()->login ( $user, true );
    			Auth::logoutOtherDevices($credentials['password']);
    			return redirect ()->intended ( '/' );
    		} else {

//     			$d = Adldap::getEventDispatcher();
    			
//     			$d->listen(Failed::class, function (Failed $event) {
//     				$conn = $event->connection;
    				
// //     				echo $conn->getLastError(); // 'Invalid credentials'
// //     				echo $conn->getDiagnosticMessage(); // '80090308: LdapErr: DSID-0C09042A, comment: AcceptSecurityContext error, data 532, v3839'
    				
//     				if ($error = $conn->getDetailedError()) {
//     					$error->getErrorCode(); // 49
//     					$error->getErrorMessage(); // 'Invalid credentials'
//     					$error->getDiagnosticMessage(); // '80090308: LdapErr: DSID-0C09042A, comment: AcceptSecurityContext error, data 532, v3839'
//     				}
    				
    				
    				return redirect ()->back ()->withInput ( $request->only ( $this->username () ) )->withErrors ( [
    						'password' => 'Wrong password or not exists in LDAP server.'
    				] );
//     			});
    		}
    	}else if (config('app.authentication') == 'UPM') {
    	    $this->upmValidator ( $request->all () )->validate ();
    	    $credentials = array (
    	        'empId' => $request->get ( "empId" ),
    	        'password' => $request->input ( 'password' )
    	    );
    	    $remember = ($request->has ( 'remember' ) && $request->input ( 'remember' ) != '' ? true : false);
    	    
    	    $this->upmService->signOff($credentials);
    	    $response = $this->upmService->signOn($credentials);
            
            if (isset($response['success']) && $response['success'] == false) {
                return redirect()->back()->withInput($request->only('empId'))->withErrors([
    	            'password' => ucwords($response['message']),
    	        ]); 
            } else {
            
                if(isset($response['success']) && $response['success']){
                    $user = $this->userRepo->getUserInstance(array('USER_ID' => $response['userInfo'][0]['USER_ID']));
                    Auth::login($user, $remember);
                    Auth::logoutOtherDevices($credentials['password']);
                    return redirect ()->intended ( '/' );
                }else{
                    return redirect()->back()->withInput($request->only('empId'))->withErrors([
                        'password' => 'Wrong password or UPM Sign On error.',
                    ]);
                }
                
            }
            
    	} else {
    		
    		$this->validator ( $request->all () )->validate ();
    		/**
    		 * Choose username /email
    		 */
    		$identity = $request->get ( "userNameOrEmail" );
    		$credentials = array (
    				filter_var ( $identity, FILTER_VALIDATE_EMAIL ) ? 'email' : 'username' => $identity,
    				'password' => $request->input ( 'password' )
    		);

    		$remember = ($request->has ( 'remember' ) && $request->input ( 'remember' ) != '' ? true : false);
    		if (Auth::attempt ( $credentials, $remember )) {
    		    Auth::logoutOtherDevices($credentials['password']);
    			return redirect ()->intended ( '/' );
    		}else{
    			// if unsuccessful -> redirect back
    			return redirect()->back()->withInput($request->only('userNameOrEmail'))->withErrors([
    					'password' => 'Wrong password or this account not approved yet.',
    			]);
    		}
    	}
    }
    
//     /**
//      * Auth log out
//      */
//     public function logout(Request $request) {
//     	if (Auth::check ()) {
//     		Auth::logout ();
//     		return redirect ( 'login' );
//     	}
//     }
    protected function loggedOut(Request $request) {
        Session::flush();
    	return redirect ( 'login' );
    }
}
