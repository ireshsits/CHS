@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | {{ucfirst($type)}} Manage')

@section('meta-description')
    Complaint Handling System | {{ucfirst($type)}} Manage
@endsection

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/') }}">	
@endsection

@section('page-css')	
	<link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dashboard/vendors/external_components/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css">	
	<link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('page-header-js')
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/jquery-validation/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/jszip/jszip.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/buttons.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/error_placement.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/server_error_handler.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/selectors/select2.min.js') }}"></script>

@endsection

@section('page-header')
@endsection

@section('content')

@php $PEN = 'active'; @endphp
@php $FORWD = ''; @endphp
@php $INPESC = ''; @endphp
@php $COM = ''; @endphp
@php $CLO = ''; @endphp

@if(isset($table))
	@if($table == 'PEN')
		@php $PEN = 'active'; @endphp
		@php $FORWD = ''; @endphp
		@php $INPESC = ''; @endphp
		@php $COM = ''; @endphp
		@php $CLO = ''; @endphp
	@elseif ($table == 'FORWD')
		@php $PEN = ''; @endphp
		@php $FORWD = 'active'; @endphp
		@php $INPESC = ''; @endphp
		@php $COM = ''; @endphp
		@php $CLO = ''; @endphp
	@elseif ($table == 'INPESC')
		@php $PEN = ''; @endphp
		@php $FORWD = ''; @endphp
		@php $INPESC = 'active'; @endphp
		@php $COM = ''; @endphp
		@php $CLO = ''; @endphp
	@elseif ($table == 'COM')
		@php $PEN = ''; @endphp
		@php $FORWD = ''; @endphp
		@php $INPESC = ''; @endphp
		@php $COM = 'active'; @endphp
		@php $CLO = ''; @endphp
	@elseif ($table == 'CLO')
		@php $PEN = ''; @endphp
		@php $FORWD = ''; @endphp
		@php $INPESC = ''; @endphp
		@php $COM = ''; @endphp
		@php $CLO = 'active'; @endphp
	@else
		@php $PEN = 'active'; @endphp
		@php $FORWD = ''; @endphp
		@php $INPESC = ''; @endphp
		@php $COM = ''; @endphp
		@php $CLO = ''; @endphp
	@endif
@endif

<div class="right_col" role="main">

	<div class="clearfix"></div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
<div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Manage {{ucfirst($type)}} </h2>
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
                  
                  	@hasanyrole(RoleHelper::getComplaintRaiseRoles())
	                    <div style="text-align: right;">
	                    	<button type="button" class="btn btn-default btn-sm" onclick="actionNew('dashboard/complaints',false)">Setup +</button>
	                    </div>
                    @endhasanyrole
                    
                	@php $raiseRole = false; @endphp
                    @hasanyrole(RoleHelper::getComplaintRaiseRoles())
                     	@php $raiseRole = true; @endphp
                    @endhasanyrole

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
							
						
	                    <li role="presentation" class="{{$PEN}}"><a href="#tab_content1" id="pending-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-hourglass"></i> Draft <span class="badge badge-pending bg-gray">0</span></a>
	                    </li>
						@if($raiseRole)
	                        <li role="presentation" class="{{$FORWD}}"><a href="#tab_content2" id="forward-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-mail-forward fa-lg"></i> Sent <span class="badge badge-forward bg-blue">0</span></a>
	                        </li>
	                    @endif
	                    <li role="presentation" class="{{$INPESC}}"><a href="#tab_content3" id="inprogress-escalated-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-spinner fa-lg"></i> Received <span class="badge badge-inprogress-escalated bg-blue">0</span></a>
	                    </li>
                        <li role="presentation" class="{{$COM}}"><a href="#tab_content4" id="completed-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-check-square-o fa-lg"></i> Completed <span class="badge badge-completed bg-blue-sky">0</span></a>
                        </li>
                        <li role="presentation" class="{{$CLO}}"><a href="#tab_content5" id="closed-tab" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-folder fa-lg"></i> Closed <span class="badge badge-closed bg-green">0</span></a>
                        </li>                        
                      </ul>
                      <!--  CR2 removed branch/Dept name from main table columns -->
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{$PEN}} in" id="tab_content1" data-ttype="PEN" aria-labelledby="pending-tab">
							{{-- <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="pending-tab"> --}}
		                    <div class="table-responsive">
		                    
		                      <table class="table table-striped {{$type}}-table-pending lodged-tables jambo_table">
		                        <thead>
		                          <tr class="headings">
                    				<th style="display: none;"></th>
		                            <th class="column-title" style="width: 3%"></th>
		                            <th class="column-title" style="width: 5%">Ref_Number </th>
		                            <th class="column-title" style="width: 20%">Area </th>
		                            <th class="column-title" style="width: 30%">Category </th>
		                            <th class="column-title" style="width: 10%">Open_Date </th>
		                            <th class="column-title" style="width: 5%">Mode </th>
		                            <th class="column-title" style="width: 5%">Source</th>
		                            <th class="column-title" style="width: 5%">Status</th>
		                            <th class="column-title no-link last" style="width: 17%"><span class="nobr">Action</span></th>
		                          </tr>
		                        </thead>
		
		                        <tbody>
		                        </tbody>
		                      </table>
		                    </div>
                        </div>
                        @if($raiseRole)
                        <div role="tabpanel" class="tab-pane fade {{$FORWD}} in" id="tab_content2" data-ttype="FORWD" aria-labelledby="forward-tab">
						{{-- <div role="tabpanel" class="tab-pane fade in" id="tab_content2" aria-labelledby="forward-tab"> --}}
		                    <div class="table-responsive">
		                    
		                      <table class="table table-striped {{$type}}-table-forward lodged-tables jambo_table">
		                        <thead>
		                          <tr class="headings">
                    				<th style="display: none;"></th>
		                            <th class="column-title" style="width: 3%"></th>
		                            <th class="column-title" style="width: 5%">Ref_Number </th>
		                            <th class="column-title" style="width: 20%">Area </th>
		                            <th class="column-title" style="width: 30%">Category </th>
		                            <th class="column-title" style="width: 10%">Open_Date </th>
		                            <th class="column-title" style="width: 5%">Mode </th>
		                            <th class="column-title" style="width: 5%">Source</th>
		                            <th class="column-title" style="width: 5%">Status</th>
		                            <th class="column-title no-link last" style="width: 17%"><span class="nobr">Action</span></th>
		                          </tr>
		                        </thead>
		
		                        <tbody>
		                        </tbody>
		                      </table>
		                    </div>
                        </div>
                      @endif
                      	<div role="tabpanel" class="tab-pane fade {{$INPESC}} in" id="tab_content3" data-ttype="INPESC" aria-labelledby="inprogress-escalated-tab">
		                    <div class="table-responsive">
		                    
		                      <table class="table table-striped {{$type}}-table-inprogress-escalated lodged-tables jambo_table">
		                        <thead>
		                          <tr class="headings">
                    				<th style="display: none;"></th>
		                            <th class="column-title" style="width: 3%"></th>
		                            <th class="column-title" style="width: 5%">Ref_Number </th>
		                            <th class="column-title" style="width: 20%">Area </th>
		                            <th class="column-title" style="width: 30%">Category </th>
		                            <th class="column-title" style="width: 10%">Open_Date </th>
		                            <th class="column-title" style="width: 5%">Mode </th>
		                            <th class="column-title" style="width: 5%">Source</th>
		                            <th class="column-title" style="width: 5%">Status</th>
		                            <th class="column-title no-link last" style="width: 17%"><span class="nobr">Action</span></th>
		                          </tr>
		                        </thead>
		
		                        <tbody>
		                        </tbody>
		                      </table>
		                    </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{$COM}} in" id="tab_content4" data-ttype="COM" aria-labelledby="completed-tab">
		                    <div class="table-responsive">
		                    
		                      <table class="table table-striped {{$type}}-table-completed lodged-tables jambo_table">
		                        <thead>
		                          <tr class="headings">
                    				<th style="display: none;"></th>
		                            <th class="column-title" style="width: 3%"></th>
		                            <th class="column-title" style="width: 5%">Ref_Number </th>
		                            <th class="column-title" style="width: 20%">Area </th>
		                            <th class="column-title" style="width: 30%">Category </th>
		                            <th class="column-title" style="width: 10%">Open_Date </th>
		                            <th class="column-title" style="width: 5%">Mode </th>
		                            <th class="column-title" style="width: 5%">Source</th>
		                            <th class="column-title" style="width: 5%">Status</th>
		                            <th class="column-title no-link last" style="width: 17%"><span class="nobr">Action</span></th>
		                          </tr>
		                        </thead>
		
		                        <tbody>
		                        </tbody>
		                      </table>
		                    </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade {{$CLO}} in" id="tab_content5" data-ttype="CLO" aria-labelledby="closed-tab">
		                    <div class="table-responsive">
		                    
		                      <table class="table table-striped {{$type}}-table-closed lodged-tables jambo_table">
		                        <thead>
		                          <tr class="headings">
                    				<th style="display: none;"></th>
		                            <th class="column-title" style="width: 3%"></th>
		                            <th class="column-title" style="width: 5%">Ref_Number </th>
		                            <th class="column-title" style="width: 20%">Area </th>
		                            <th class="column-title" style="width: 30%">Category </th>
		                            <th class="column-title" style="width: 10%">Open_Date </th>
		                            <th class="column-title" style="width: 5%">Mode </th>
		                            <th class="column-title" style="width: 5%">Source</th>
		                            <th class="column-title" style="width: 5%">Status</th>
		                            <th class="column-title no-link last" style="width: 17%"><span class="nobr">Action</span></th>
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
                
		</div>
	</div>
</div>
@endsection


@section('page-js')
    @include('dashboard.partials.ajax-setup')
    <script type="text/javascript">
    	var type = '{{$type}}';
    	var typeCode = '{{$typeCode}}';
    </script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/common/manage.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/manage.min.js') }}"></script>
@endsection
@section('modal-js')
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/action.min.js') }}"></script>
@endsection