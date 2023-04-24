@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Zone setup')

@section('meta-description')
    Complaint Handling System | Zone setup
@endsection

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/') }}">	
@endsection

@section('page-css')	
	<link href="{{ asset('dashboard/vendors/external_components/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css">	
	<link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dashboard/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page-header-js')
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/jquery-validation/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/js/selectors/select2-dropdownPosition.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/jszip/jszip.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/buttons.min.js') }}"></script>
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
		
			<!--  Panel 01 Start -->
			<div class="x_panel">
				<div class="x_title">
					<h2>Zone Setup</h2>
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
						</li>
<!-- 						<li><a class="close-link"><i class="fa fa-close"></i></a></li> -->
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
				<br />
				<form id="zone-setup-form">
                	<input type="hidden" name="mode" id="mode" value="{{$mode}}"/>
                	<input type="hidden" name="zone_id_pk" id="zone_id_pk" value="{{($mode == 'edit' && $zone->zone_id_pk ? $zone->zone_id_pk : '')}}"/>
                        	@hasanyrole(RoleHelper::getAdminRoles())
                            <div class="row">
                            	<!-- CR2 changed Recepeient_user role to user -->
                        		<div class="col-md-3">
    	                            <div class="form-group">
    	                                <label>Name</label>
    	                                <input type="text" placeholder="Zone Name" id="zone_name" class="form-control name-error error-placement" name="name">
    	                            </div>
                               	</div>
                               	<div class="col-md-3">
    	                            <div class="form-group">
    	                                <label>Number</label>
    	                                <input type="text" placeholder="Zone Number" id="zone_number" class="form-control number-error error-placement" name="number">
    	                            </div>
                               	</div>             		
                               	<div class="col-md-3">
                        		<label>Manager</label>
    		                        <div class="form-group">		                            
    				                    <select data-placeholder="Select Manager" style="width: 100% !important;" class="form-control select-user-search select2-close manager_id_fk-error error-placement" onselect="onSelect(this)" name="manager_id_fk" id="zone_manager">
    				                    <option></option>
    				                    </select>
    								</div>
    							</div>
                        		<div class="col-md-2">
                        		<label>Status</label>
    		                        <div class="form-group">		                            
    				                    <select data-placeholder="Select Status" style="width: 100% !important;" class="form-control select-zonestatus-search select2-close status-error error-placement" onselect="onSelect(this)" name="status" id="zone_status">
    				                    <option></option>
    				                    </select>
    								</div>
    							</div>
                        		<div class="col-md-1">
                        		<label>&nbsp;</label>
    		                        <div class="form-group">
                        				<button type="submit" id="zone-setup-submit" class="btn btn-primary">Save</button>
                        			</div>
                        		</div>
    						</div>
    						@endhasanyrole
    						
<!--     						 <div class="row"> -->
<!--                         		<div class="col-md-12"> -->
<!--    	                            <div class="form-group" id="displayRejectReason"  style="display:none"> -->
<!--     	                                <label>Reject Reason</label> -->
<!--     	                                 <textarea  class="form-control" name="reject_reason"  id="zone_reject_reason" class="zone_reject_reason"></textarea> -->
<!--     	                            </div> -->
<!--                                	</div> -->
                        		
<!--     						</div> -->	
            	</form>
				</div>
			</div>
			<!--  Panel 01 End -->
			@if($mode == 'edit')
			<!--  Panel 02 Start -->
			<div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Manage Zone Regions </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
<!--                       <li><a class="close-link"><i class="fa fa-close"></i></a> -->
<!--                       </li> -->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  	
                  	<div class="table-responsive">
                     	
                    <form id="region-select-form">
                		<input type="hidden" name="zone_id_pk" id="zone_id_pk" value="{{($mode == 'edit' && $zone->zone_id_pk ? $zone->zone_id_pk : '')}}"/>
                       	<div class="col-md-3">
                		<label>Select Region</label>
                            <div class="form-group">		                            
    		                    <select data-placeholder="Select Region" style="width: 100% !important;" class="form-control select-region-search select2-close region_id_fk-error error-placement" onselect="onSelect(this)" name="region_id_pk" id="zone_region">
    		                    <option></option>
    		                    </select>
    						</div>
    					</div>
					</form>
                    <div class="clearfix"></div>
                      <table class="table table-striped regions-table jambo_table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" style="width: 20%">Name</th>
                            <th class="column-title" style="width: 5%">Number</th>
                            <th class="column-title" style="width: 20%">Manager Name</th>
                            <th class="column-title" style="width: 20%">Manager Email</th>
                            <th class="column-title" style="width: 15%">Status</th>
                            <th class="column-title no-link last" style="width: 20%"><span class="nobr">Action</span>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  
             		</div>
          </div>
			<!--  Panel 02 End -->
		 @endif
		</div>
	</div>
</div>
@endsection


@section('page-js')
    @include('dashboard.partials.ajax-setup')
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/zones/setup.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/regions/manage.min.js') }}"></script>
@endsection
@section('modal-js')
@endsection