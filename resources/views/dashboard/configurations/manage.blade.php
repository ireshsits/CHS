@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Configurations')

@section('meta-description')
    Complaint Handling System | Configurations
@endsection

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/') }}">	
@endsection

@section('page-css')
	<link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dashboard/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/vendors/external_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page-header-js')
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/jquery-validation/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/js/selectors/select2-dropdownPosition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
	
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/error_placement.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/server_error_handler.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/selectors/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/common/manage.min.js') }}"></script>
@endsection

@section('page-header')
@endsection

@section('content')
<div class="right_col" role="main">

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
            @hasanyrole(RoleHelper::getAdminRoles())
    			<form id="config-setup-form" method="post" action="{{ route('dashboard.config.update') }}" accept-charset="UTF-8">
    			<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    			<!--  Panel 01 Start -->
    			<div class="x_panel">
    				<div class="x_title">
    					<h2>Mail Configurations</h2>
    					<ul class="nav navbar-right panel_toolbox">
    						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
    						</li>
    					</ul>
    					<div class="clearfix"></div>
    				</div>
    				<div class="x_content">
    				<br />
                        <div class="row">                    		
                        	<div class="col-md-2">
                                <div class="form-group">
                                    <label>Mail Driver</label>
                                    <input type="text" placeholder="Mail Driver" id="mail_driver" value="{{env('MAIL_DRIVER')}}" class="form-control MAIL_DRIVER-error error-placement" name="MAIL_DRIVER">
                                </div>
                           	</div>
                    		<div class="col-md-2">
                                <div class="form-group">
                                    <label>Mail Host</label>
                                    <input type="text" placeholder="Mail Host" id="mail_host" value="{{env('MAIL_HOST')}}" class="form-control MAIL_HOST-error error-placement" name="MAIL_HOST">
                                </div>
                           	</div>
                           	<div class="col-md-2">
                                <div class="form-group">
                                    <label>Mail Port</label>
                                    <input type="text" placeholder="Mail Port" id="mail_port" value="{{env('MAIL_PORT')}}" class="form-control number-error error-placement" name="MAIL_PORT">
                                </div>
                           	</div>
                           	<div class="col-md-3">
                                <div class="form-group">
                                    <label>Mail Usernme</label>
                                    <input type="text" placeholder="Mail Username" id="mail_username" value="{{env('MAIL_USERNAME')}}" class="form-control number-error error-placement" name="MAIL_USERNAME">
                                </div>
                           	</div>
                           	<div class="col-md-3">
                                <div class="form-group">
                                    <label>Mail Password</label>
                                    <input type="text" placeholder="Mail Password" id="mail_password" value="{{env('MAIL_PASSWORD')}}" class="form-control number-error error-placement" name="MAIL_PASSWORD">
                                </div>
                           	</div>             		
                           	<div class="col-md-2">
                    		<label>Mail Encryption</label>
    	                        <div class="form-group">		                            
    			                    <select data-placeholder="Mail Encryption" style="width: 100% !important;" class="form-control select2-close MAIL_ENCRYPTION-error error-placement" onselect="onSelect(this)" name="MAIL_ENCRYPTION" id="zone_manager">
    			                    	<option value="TLS" {{(env('MAIL_ENCRYPTION') == 'TLS' ? 'selected' : '')}}>TLS</option>
    			                    </select>
    							</div>
    						</div>
                           	<div class="col-md-3">
                                <div class="form-group">
                                    <label>Mail From Address</label>
                                    <input type="text" placeholder="Mail From Address" id="mail_from_address" value="{{env('MAIL_FROM_ADDRESS')}}" class="form-control MAIL_FROM_ADDRESS-error error-placement" name="MAIL_FROM_ADDRESS">
                                </div>
                           	</div>
                           	<div class="col-md-3">
                                <div class="form-group">
                                    <label>Mail From Name</label>
                                    <input type="text" placeholder="Mail From Name" id="mail_from_name" value="{{env('MAIL_FROM_NAME')}}" class="form-control MAIL_FROM_NAME-error error-placement" name="MAIL_FROM_NAME">
                                </div>
                           	</div>    
    					</div>
    				</div>
    			</div>
    			<!-- Panel 01 End -->
    			<!-- Panel 02 Start -->
    			   <div class="x_panel">
    				<div class="x_title">
    					<h2>Adldap Configurations</h2>
    					<ul class="nav navbar-right panel_toolbox">
    						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
    						</li>
    					</ul>
    					<div class="clearfix"></div>
    				</div>
    				<div class="x_content">
    				<br />
                        <div class="row">
                    		<div class="col-md-2">
                                <div class="form-group">
                                    <label>Host</label>
                                    <input type="text" placeholder="Ldap Host" id="ldap_host" value="{{env('LDAP_HOSTS')}}" class="form-control MAIL_HOST-error error-placement" name="LDAP_HOSTS">
                                </div>
                           	</div>
                           	<div class="col-md-2">
                                <div class="form-group">
                                    <label>Port</label>
                                    <input type="text" placeholder="Ldap Port" id="ldap_port" value="{{env('LDAP_PORT')}}" class="form-control number-error error-placement" name="LDAP_PORT">
                                </div>
                           	</div>
                           	<div class="col-md-4">
                                <div class="form-group">
                                    <label>Base DN</label>
                                    <input type="text" placeholder="Ldap Base DN" id="ldap_base_dn" value="{{env('LDAP_BASE_DN')}}" class="form-control number-error error-placement" name="LDAP_BASE_DN">
                                </div>
                           	</div>            		
                           	<div class="col-md-2">
                    		<label>Use SSL</label>
    	                        <div class="form-group">		                            
    			                    <select data-placeholder="Select Manager" style="width: 100% !important;" class="form-control select2-close LDAP_USE_SSL-error error-placement" onselect="onSelect(this)" name="LDAP_USE_SSL" id="ldap_use_ssl">
    			                    	<option value="true" {{(env('LDAP_USE_SSL') == true ? 'selected' : '')}}>True</option>
    			                  		<option value="false" {{(env('LDAP_USE_SSL') == false ? 'selected' : '')}}>False</option>
    			                    </select>
    							</div>
    						</div>            		
                           	<div class="col-md-2">
                    		<label>Use TLS</label>
    	                        <div class="form-group">		                            
    			                    <select data-placeholder="Select Manager" style="width: 100% !important;" class="form-control select2-close LDAP_USE_TLS-error error-placement" onselect="onSelect(this)" name="LDAP_USE_TLS" id="ldap_use_tls">
    			                    	<option value="true" {{(env('LDAP_USE_TLS') == true ? 'selected' : '')}}>True</option>
    			                  		<option value="false" {{(env('LDAP_USE_TLS') == false ? 'selected' : '')}}>False</option>
    			                    </select>
    							</div>
    						</div>
                           	<div class="col-md-4">
                                <div class="form-group">
                                    <label>Ldap Username</label>
                                    <input type="text" placeholder="Ldap Username" id="ldap_username" value="{{env('LDAP_USERNAME')}}" class="form-control number-error error-placement" name="LDAP_USERNAME">
                                </div>
                           	</div>
                           	<div class="col-md-4">
                                <div class="form-group">
                                    <label>Ldap Password</label>
                                    <input type="text" placeholder="Ldap Password" id="ldap_password" value="{{env('LDAP_PASSWORD')}}" class="form-control number-error error-placement" name="LDAP_PASSWORD">
                                </div>
                           	</div>
                           	<div class="col-md-4">
                                <div class="form-group">
                                    <label>Ldap User Attribute</label>
                                    <input type="text" placeholder="Ldap User Attribute" id="ldap_user_attribute" value="{{env('LDAP_USER_ATTRIBUTE')}}" class="form-control number-error error-placement" name="LDAP_USER_ATTRIBUTE">
                                </div>
                           	</div> 
    					</div>
    				</div>
    			</div>
    			<!-- Panel 02 End -->
    			<!-- Panel 03 Start -->
    			   <div class="x_panel">
    				<div class="x_title">
    					<h2>UPM Configurations</h2>
    					<ul class="nav navbar-right panel_toolbox">
    						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
    						</li>
    					</ul>
    					<div class="clearfix"></div>
    				</div>
    				<div class="x_content">
    				<br />
                        <div class="row">
                           	<div class="col-md-6">
                                <div class="form-group">
                                    <label>SOAP Service</label>
                                    <input type="text" placeholder="SOAP Service" id="upm_soap_service" value="{{$upmSettings->SOAP_URL}}" class="form-control UPM_SOAP_SERVICE-error error-placement" name="UPM_SOAP_SERVICE">
                                </div>
                           	</div>
                           	<div class="col-md-6">
                                <div class="form-group">
                                    <label>REST Service</label>
                                    <input type="text" placeholder="REST Service" id="upm_rest_service" value="{{$upmSettings->REST_URL}}" class="form-control UPM_REST_SERVICE-error error-placement" name="UPM_REST_SERVICE">
                                </div>
                           	</div>
                           	<div class="col-md-4">
                                <div class="form-group">
                                    <label>SOAP Version</label>		                            
    			                    <select data-placeholder="Select Version"  id="soap_version" style="width: 100% !important;" class="form-control select2-close SOAP_VERSION-error error-placement" onselect="onSelect(this)" name="SOAP_VERSION">
    			                    	<option value="1.1" {{($upmSettings->SOAP_VERSION == 1.1? 'selected' : '')}}>1.1</option>
    			                  		<option value="1.2" {{($upmSettings->SOAP_VERSION == 1.2? 'selected' : '')}}>1.2</option>
    			                    </select>
                                </div>
                           	</div>
                    		<div class="col-md-4">
                                <div class="form-group">
                                    <label>App Code</label>
                                    <input type="text" placeholder="UPM App code" id="upm_app_code" value="{{$upmSettings->APPLICATION_CODE}}" class="form-control UPM_APP_CODE-error error-placement" name="UPM_APP_CODE">
                                </div>
                           	</div>
                           	<div class="col-md-4">
                                <div class="form-group">
                                    <label>Application Secirity Classes</label>		                            
    			                    <input type="text" placeholder="UPM App security class" id="upm_app_security_class" value="{{$upmSettings->RAW_SECURITY_CLASSES}}" class="form-control UPM_APP_SECURITY_CLASS-error error-placement" name="UPM_APP_SECURITY_CLASS">
                                </div>
                           	</div>    					
                        </div>
    				</div>
    			</div>
    			<!-- Panel 03 End -->
    			<div class="modal-footer">
                    <button type="submit" id="config-submit" class="btn btn-primary col-md-2">Save</button>
                </div>
    		</form>
		@endhasanyrole
		</div>
	</div>
</div>
@endsection


@section('page-js')
    @include('dashboard.partials.ajax-setup')
<!-- 	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/setup.min.js') }}"></script> -->
@endsection
@section('modal-js')
@endsection