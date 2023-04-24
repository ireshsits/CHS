@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Zones Manage')

@section('meta-description')
    Complaint Handling System | Zones Manage
@endsection

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/') }}">	
@endsection

@section('page-css')
	<link href="{{ asset('dashboard/vendors/external_components/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css">	
	<link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
<!-- 	<link href="{{ mix('dashboard/compiled/css/plugins/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css"> -->
	<link href="{{ asset('dashboard/vendors/external_components/color-picker/octicons.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dashboard/vendors/external_components/color-picker/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css">	
	<link href="{{ asset('dashboard/vendors/external_components/color-picker/main.css') }}" rel="stylesheet" type="text/css">	
@endsection

@section('page-header-js')
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/jquery-validation/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/jszip/jszip.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/buttons.min.js') }}"></script>
<!-- 	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/color-picker/bootstrap-colorpicker.js') }}"></script> -->
@endsection

@section('page-header')
@endsection

@section('content')
<div class="right_col" role="main">

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
			<div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Manage Zones </h2>
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
                    
                    <div style="text-align: right;">
                    @hasanyrole(RoleHelper::getAdminRoles())
                    <button type="button" id="active-zone" class="btn btn-success btn-sm" onclick="updateStatus('dashboard/zones',0,false,'status','ACT')">Active All</button>
                    <button type="button" id="inactive-zone" class="btn btn-warning btn-sm" onclick="updateStatus('dashboard/zones',0,false,'status','INACT')">Inactive All</button>
                    @endhasanyrole
                    <button type="button" class="btn btn-default btn-sm" onclick="actionNew('dashboard/zones',false)">Setup +</button>
                    </div>
                    
                      <table class="table table-striped zones-table jambo_table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" style="width: 3%"></th>
                            <th class="column-title" style="width: 15%">Name</th>
                            <th class="column-title" style="width: 5%">Number</th>
                            <th class="column-title" style="width: 20%">Manager Name</th>
                            <th class="column-title" style="width: 20%">Manager Email</th>
                            <th class="column-title" style="width: 10%">Regions</th>
                            <th class="column-title" style="width: 10%">Status</th>
                            <th class="column-title no-link last" style="width: 17%"><span class="nobr">Action</span>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  
             		</div>
          </div>
    </div>
    </div>
</div>
@endsection


@section('page-js')
    @include('dashboard.partials.ajax-setup')
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/error_placement.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/server_error_handler.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/selectors/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/common/manage.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/zones/manage.min.js') }}"></script>
@endsection

@section('modal-js')
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/zones/setup.min.js') }}"></script>
@endsection