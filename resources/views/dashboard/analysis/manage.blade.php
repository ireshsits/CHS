@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Analysis Overview')

@section('meta-description')
    Complaint Handling System | Analysis Overview
@endsection

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/') }}">	
@endsection

@section('page-css')
	<link href="{{ asset('dashboard/vendors/external_components/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css">		
	<link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">	
	<link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ mix('dashboard/compiled/css/my-custom.min.css') }}" rel="stylesheet" type="text/css">
 	<link href="{{ asset('dashboard/vendors/external_components/datatables/extensions/rowgroup/rowGroup.dataTables.min.css') }}"></script>
@endsection

@section('page-header-js')
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/jquery-validation/jquery.validate.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/jszip/jszip.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/buttons.min.js') }}"></script>
<!-- 	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/rowsgroup/dataTables.rowsGroup.js') }}"></script> -->
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/extensions/rowgroup/dataTables.rowGroup.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!--

//-->
</script>
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
                    <h2><i class="fa fa-bars"></i> Manage Analysis </h2>
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
                  	<form id="analysis-form" data-parsley-validate class="form-horizontal form-label-left">
                  		<div class="row">                  		
		                  	<div class="col-md-5">
		                  		<label>Analysis</label>
		                  		<div class="form-group">							        
									<select id="analysis-type" name="analysis_type" data-placeholder="Select Analysis Type" class="form-control select code-error select2-close error-placement">
											<option value="">Select Analysis</option>
											@foreach($analysesCategories as $anaCategory)
											<optgroup label= "{{$anaCategory->name}}">
												@foreach($anaCategory->analyses as $analysis)
												<option value="{{$analysis->code}}">{{$analysis->name}}</option>
												@endforeach
											</optgroup>
											@endforeach
									</select>
								</div>
							</div>
                  			<div class="col-md-2">
	                  			<label>Type</label>
	                  			<div class="form-group">
			                       <select id="select-type" name="type" data-placeholder="Select Type" class="form-control select-type-search select2-close type-error error-placement">
			                       </select>
								</div>
							</div>
							
                  			<div class="col-md-1">
	                  			<label>Year</label>
	                  			<div class="form-group">
									<select id="analysis-year" name="year" data-placeholder="Select Year" class="form-control select-year-search year-error select2-close error-placement">
									</select>
								</div>
							</div>
                  			<div class="col-md-2">
	                  			<label>Status</label>
	                  			<div class="form-group">
			                       <select id="analysis-status" name="status" data-placeholder="Select Status" class="form-control select-report-analysis-status-search select2-close status-error error-placement">
			                       </select>
								</div>
							</div>
								    
		                  	<div class="col-md-2">
		                  		<label>&nbsp;</label>
		                  		<div class="form-group">
									<button class="btn btn-primary" id="analysis-form-submit">View</button>
                    				<!-- <button type="button" class="btn btn-default btn-sm" onclick="exportAnalysis('PDF')"><i class="fa fa-file-pdf-o fa-2x" style="color:red"></i></button> -->
                    				<button type="button" class="btn btn-success btn-sm" onclick="exportAnalysis('EXCEL')"><i class="fa fa-file-excel-o fa-2x"></i></button>
								</div>
							</div>
                  		
                  		</div>        	
                  		<div class="row display-area" style="display: none;">
		                  	<div class="col-md-4">
		                  		<label>Area</label>
		                  		<div class="form-group">		                           
				                    <select data-placeholder="Select Area" style="width: 100% !important;" class="form-control select-area-search select2-close area_id_fk-error error-placement" onselect="onSelect(this)" name="area_id_fk" id="area_id_fk">
				                    	<option></option>
				                    </select>
		                  		</div>
		                  	</div>
                  		</div>
                  		<div class="row display-category" style="display: none;">
		                  	<div class="col-md-4">
		                  		<label>Category</label>
								<div class="form-group">
			                        <select data-placeholder="Select Category" style="width: 100% !important;" class="form-control select-category-search select2-close category_id_fk-error error-placement" onselect="onSelect(this)" name="category_id_fk" id="category_id_fk">
			                        </select>
								</div>
							</div>
                  		</div>
					</form>
					
					<div class="displayTable">
						@include('dashboard.analysis.table_component')
					</div>
					
					<div id="canvas-holder" class="displayChart">
						<div class="row">
							<div class="col-md-7">
								<canvas id="chart-area"></canvas>
							</div>
							<div class="col-md-5">
								<div id="do_legend"></div>
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
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/error_placement.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/errors/server_error_handler.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/selectors/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/common/manage.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/analysis/manage.min.js') }}"></script>
@endsection
@section('modal-js')
@endsection