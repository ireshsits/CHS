@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Reports Manage')

@section('meta-description')
    Complaint Handling System | Reports Manage
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
<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/vendors/switchery/dist/switchery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/buttons.min.js') }}"></script>
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
                    <h2><i class="fa fa-bars"></i> Manage Reports </h2>
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
                  
                  <form id="report-form" data-parsley-validate class="form-horizontal form-label-left">
                  	<input type="hidden" id="category_levels" value="{{$category_levels}}"/>
					<div class="row">
						
						<div class="col-md-3">
		                  	<label>From</label>
		                  	<div class="form-group">
								<input class="form-control report_date-from report_date-from-error error-placement" type="date" name="report_date_from" id="report_date_from">
							</div>
						</div>
						<div class="col-md-3">
		                  	<label>To</label>
		                  	<div class="form-group">
								<input class="form-control report_date-to report_date-to-error error-placement" type="date" name="report_date_to" id="report_date_to">
							</div>
						</div>
						<div class="col-md-2">
                  			<label>Type</label>
                  			<div class="form-group">
		                       <select id="select-type" name="type" data-placeholder="Select Type" class="form-control select-type-search select2-close type-error error-placement">
		                       </select>
							</div>
						</div>
						<div class="col-offset-1 col-md-1">
		                  	<label>Filters</label>
		                  	<div class="form-group">
							    <input type="checkbox" name="filter" id="filter" class="js-switch filter-switchery" />
							</div>
						</div>
						<div class="col-md-2">
		                  	<label>&nbsp;</label>
		                  	<div class="form-group">
		                    	<button type="button" id="report-submit" class="btn btn-primary btn-sm">Generate</button>
		                    	<!-- <button type="button" class="btn btn-default btn-sm" onclick="exportReport('PDF')"><i class="fa fa-file-pdf-o fa-2x" style="color:red"></i></button> -->
		                    	<button type="button" class="btn btn-success btn-sm" onclick="exportReport('EXCEL')"><i class="fa fa-file-excel-o fa-2x"></i></button>
							</div>
						</div>
					</div>
                    </form>
                 
                    <span class="filter-display">
							<div class="ln_solid"></div>
							
							<div class="row">
							<div class="col-md-6 col-xs-12">
							<label for="branch_dept_name">Branch / Department:</label>
								<div class="form-group">									
			                        <select data-placeholder="Select Branch / Dept" style="width: 100% !important;" class="form-control select-branch-search select2-close branch_department_id_fk-error error-placement" onselect="onSelect(this)" name="branch_department_id_fk" id="branch_department_id_fk">
			                        </select>
								</div>
							</div>
							
							<div class="col-md-2 col-xs-12">
							<label for="branch_dept_mode">Mode:</label> 
								<div class="form-group">									
			                        <select data-placeholder="Select Mode" style="width: 100% !important;" class="form-control select-complaint-mode-search select2-close complaint_mode_id_fk-error error-placement" onselect="onSelect(this)" name="complaint_mode_id_fk" id="complaint_mode_id_fk" value="">
			                        <option></option>
			                        </select>
								</div>
							</div>
							
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="branch_dept_code">Area:</label> 
			                       <select data-placeholder="Select Area" style="width: 100% !important;" class="form-control select-area-search select2-close area_id_fk-error error-placement" onselect="onSelect(this)" name="area_id_fk" id="area_id_fk" value="">
			                       <option></option>
			                       </select>
								</div>
							</div>
							
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="branch_dept_code">Status:</label> 
			                       <select data-placeholder="Select Status" style="width: 100% !important;" class="form-control select-report-analysis-status-search select2-close status-error error-placement" onselect="onSelect(this)" name="status" id="status" value="">
			                       <option></option>
			                       </select>
								</div>
							</div>
							
						    </div>
						    
						    
						<div class="row categoryFilterRow">
						</div>
						</span>
					
					<div class="clearfix">&nbsp;</div>
                    
                      <div class="table-responsive">
	                      <table id="reports-table" class="table table-striped reports-table jambo_table">
	                        <thead>
	                          <tr class="headings">
	                            <th class="column-title" style="width: 3%"></th>
	                            <th class="column-title" style="width: 10%">Date</th>
	                            <th class="column-title" style="width: 7%">Ref_Number</th>
	                            <th class="column-title" style="width: 7%">Type</th>
	                            <th class="column-title" style="width: 10%">Branch/Dept</th>
	                            <th class="column-title" style="width: 10%">Area</th>
	                            @for($i = 1; $i<=$category_levels; $i++)
	                            	<th class="column-title" style="width: 12%">Category_Level_{{$i}}</th>
	                            @endfor
	                            <th class="column-title" style="width: 12%">NIC</th>
	                            <th class="column-title" style="width: 10%">Name_of_the_Complainant</th>
	                           
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
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/reports/manage.min.js') }}"></script>
@endsection
@section('modal-js')
@endsection