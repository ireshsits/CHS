@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Mode Manage')

@section('meta-description')
    Complaint Handling System | Mode Manage
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
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/color-picker/bootstrap-colorpicker.js') }}"></script>
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
                    <h2><i class="fa fa-bars"></i> Manage Modes </h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
<!--                       <li class="dropdown"> -->
<!--                         <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a> -->
<!--                         <ul class="dropdown-menu" role="menu"> -->
<!--                           <li><a href="#">Settings 1</a> -->
<!--                           </li> -->
<!--                           <li><a href="#">Settings 2</a> -->
<!--                           </li> -->
<!--                         </ul> -->
<!--                       </li> -->
<!--                       <li><a class="close-link"><i class="fa fa-close"></i></a> -->
<!--                       </li> -->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  	
                  	<div class="table-responsive">
                    
                    <div style="text-align: right;">
                    @hasanyrole(RoleHelper::getAdminRoles())
                    <button type="button" id="active-mode" class="btn btn-success btn-sm" onclick="updateStatus('dashboard/modes',0,false,'status','ACT')">Active All</button>
                    <button type="button" id="inactive-mode" class="btn btn-warning btn-sm" onclick="updateStatus('dashboard/modes',0,false,'status','INACT')">Inactive All</button>
                    @endhasanyrole
                    <button type="button" class="btn btn-default btn-sm" onclick="actionNew('dashboard/modes',true)">Setup +</button>
                    </div>
                    
                      <table class="table table-striped modes-table jambo_table">
                        <thead>
                          <tr class="headings">
                            <th class="column-title" style="width: 30%">Name</th>
                            <th class="column-title" style="width: 10%">Code</th>
                            <th class="column-title" style="width: 20%">Color </th>
                            <th class="column-title" style="width: 20%">Status</th>
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
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/modes/manage.min.js') }}"></script>
@endsection

@section('modal-js')
<script type="text/javascript" src="{{ mix('dashboard/compiled/js/modes/setup.min.js') }}"></script>
@endsection

