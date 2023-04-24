<?php

namespace App\Classes\Ldap;

use Adldap;
use Auth;
// use App\Classes\Ldap\Schemas\ApacheDirectoryStudioServer;

class Connector {
	protected $config;
	protected $adldap;
	public function __construct() {
		/**
		 * Create the configuration array.
		 */
		$this->config = [ 
				// Mandatory Configuration Options
				'hosts' => config ( 'ldap.connections.default.settings.hosts' ),
				'base_dn' => config ( 'ldap.connections.default.settings.base_dn' ),
				'username' => config ( 'ldap.connections.default.settings.username' ),
				'password' => config ( 'ldap.connections.default.settings.password' ),
				
				// Optional Configuration Options
				'schema' => config ( 'ldap.connections.default.settings.schema' ),
				'account_prefix' => config ( 'ldap.connections.default.settings.account_prefix' ),
				'account_suffix' => config ( 'ldap.connections.default.settings.account_suffix' ),
				'port' => config ( 'ldap.connections.default.settings.port' ),
				'follow_referrals' => config ( 'ldap.connections.default.settings.follow_referrals' ),
				'use_ssl' => config ( 'ldap.connections.default.settings.use_ssl' ),
				'use_tls' => config ( 'ldap.connections.default.settings.use_tls' ),
				'version' => config ( 'ldap.connections.default.settings.version' ),
				'timeout' => config ( 'ldap.connections.default.settings.timeout' ),
				
				// Custom LDAP Options
				'custom_options' => [ 
						// See: http://php.net/ldap_set_option
// 						LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_HARD 
				]
		];
		
		$this->adldap = new Adldap\Adldap ();
		$connectionName = 'default';
		$this->adldap->addProvider ( $this->config, $connectionName );
// 		$this->adldap->setSchema(new ApacheDirectoryStudioServer());
		try {
			$this->adldap->connect ( $connectionName );
			$this->adldap->setDefaultProvider ( $connectionName );
		} catch ( Adldap\Auth\BindException $e ) {
			dd ( $e );
		}
	}
	public function getConnection(){
		return $this->adldap;
	}
	public function checkConnection() {
		return $this->adldap->getFacadeRoot ();
	}
}