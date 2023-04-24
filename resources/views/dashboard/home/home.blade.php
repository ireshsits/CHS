@extends('dashboard.layout.master-layout') @section('page-title',
'Complaint Handling System | Dashboard') @section('meta-description')
Complaint Handling System | Home @endsection @section('canonical-url')
<link rel="canonical" href="{{ URL::to('/') }}">
@endsection 

@section('page-css') 
	
	<link href="{{ asset('dashboard/vendors/external_components/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css">			
	<link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">	
	<link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ mix('dashboard/compiled/css/my-custom.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dashboard/compiled/css/plugins/chart.min.css') }}" rel="stylesheet" type="text/css">
 	<link href="{{ asset('dashboard/vendors/external_components/datatables/extensions/rowgroup/rowGroup.dataTables.min.css') }}"></script>
 	<!-- Material Design Bootstrap -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet"> -->
@endsection 

@section('page-header-js')
	<script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/datatables/datatables.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/selectors/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/common/manage.min.js') }}"></script>
<!--  	<script type="text/javascript" src="{{ asset('dashboard/vendors/Chart.js/dist/Chart.min.js') }}"></script> -->
	<script type="text/javascript" src="{{ asset('dashboard/compiled/js/plugins/moment.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/compiled/js/plugins/chart.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('dashboard/compiled/js/plugins/chart.datalabels.min.js')}}"></script>

@endsection 
@section('page-header')  
@endsection 
@section('content')


<div class="right_col" role="main">

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">

			<div class="x_panel">
				<div class="x_title">
					
					<div class="row">
						<div class="col-md-5 cuz-width">
							<h2> Complaints As at {{DateHelper::getLongFormatDateString()}} </h2>
						</div>
						<div class="col-md-7">
							<form id="dashboard" data-parsley-validate class="form-horizontal form-label-left">
    							<input type="hidden" name="mode" id="mode" value="" />
                				<div class="row">        		
        		                  	<div class="col-md-12">
                						<div class="col-md-3">
                                  			<div class="form-group">
                		                       <select id="select-type" name="type" data-placeholder="Select Type" class="form-control select-type-search select2-close type-error error-placement">
                		                       </select>
                							</div>
                						</div>
        		                  		<div class="form-group col-md-4"> <!--  select-cuz-al -->					        
        									<select id="raise_by" name="raise_by" data-placeholder="Select Raise" class="form-control select-no-clear select2-close">
    											@hasanyrole(RoleHelper::getAdminViewRoles())
        			                        		<option value="all" selected>Raise by All</option>
        			                        	@endhasanyrole
        			                        	@hasanyrole(RoleHelper::getComplaintRaiseRoles())
        			                        		<option value="others_to_me">Raise by others to me</option>
        			                        		<option value="me">Raise by me</option>
        			                        	@endhasanyrole
        									</select>
        								</div>
        		                  		<div class="form-group col-md-5">							        
        									<select id="month" name="month" data-placeholder="Select Month" class="form-control select select2-close">
        										@foreach(DateHelper::getMonthList(DateHelper::getYear(), 'Dropdown') as $month)
        			                        		<option value="{{$month['value']}}"  @if(DateHelper::getMonth(true) == $month['value']) selected @endif>{{$month['text']}}</option>
        			                        	@endforeach
        									</select>
        								</div>
        							</div>
        						</div>
							</form>
						</div>
					</div>
					
					<!--  Box Section Start -->
					
					<div class="clearfix"></div>
					<hr>
					
					<div class="container">
					    <div class="row">
					    
					     <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-gray order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 m-title">{{DateHelper::getMonthLongFormat()}}</h6>
					                    <i class="fa fa-hourglass f-left cuz-icon"></i>
					                    <p class="m-b-0">Draft<span class="f-right m-box-pending" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
							
							<div class="col-md-3 col-xl-2">
								<div class="card bg-c-blue order-card">
									<div class="card-block">
										<h6 class="m-b-20 m-title">{{DateHelper::getMonthLongFormat()}}</h6>
										<i class="fa fa-sum f-left cuz-icon "></i>
										<p class="m-b-0">
											Total<span class="f-right m-box-total"
												style="font-weight: bold; color: #fff;">0</span>
										</p>
									</div>
								</div>
							</div>
							
					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-gray order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 a-title">{{DateHelper::getYear()}}</h6>
					                    <i class="fa fa-hourglass f-left cuz-icon"></i>
					                    <p class="m-b-0">Draft<span class="f-right a-box-pending" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					        
					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-blue order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 a-title">{{DateHelper::getYear()}}</h6>
					                   <i class="fa fa-sum f-left cuz-icon"></i>
					                    <p class="m-b-0">Total<span class="f-right a-box-total" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					        </div>
					    <div class="row">
					    
					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-pink order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 m-title">{{DateHelper::getMonthLongFormat()}}</h6>
					                    <i class="fa fa-spinner f-left cuz-icon"></i>
					                    <p class="m-b-0">Inprogress<span class="f-right m-box-inprogress" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					        
							<div class="col-md-3 col-xl-2">
								<div class="card bg-c-green order-card">
									<div class="card-block">
										<h6 class="m-b-20 m-title">{{DateHelper::getMonthLongFormat()}}</h6>
										<i class="fa fa-folder f-left cuz-icon"></i>
										<p class="m-b-0">
											Closed<span class="f-right m-box-closed"
												style="font-weight: bold; color: #fff;">0</span>
										</p>
									</div>
								</div>
							</div>
							

					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-pink order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 a-title">{{DateHelper::getYear()}}</h6>
					                    <i class="fa fa-spinner f-left cuz-icon"></i>
					                    <p class="m-b-0">Inprogress<span class="f-right a-box-inprogress" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					        
					        
					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-green order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 a-title">{{DateHelper::getYear()}}</h6>
					                   <i class="fa fa-folder f-left cuz-icon"></i>
					                    <p class="m-b-0">Closed<span class="f-right a-box-closed" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					    
					    </div>
					    <div class="row">
					    
					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-blue order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 m-title">{{DateHelper::getMonthLongFormat()}}</h6>
					                    <i class="fa fa-spinner f-left cuz-icon"></i>
					                    <p class="m-b-0">Replied<span class="f-right m-box-replied" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>

					    	<div class="col-md-3 col-xl-2">
					            <div class="card bg-c-yellow order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 m-title">{{DateHelper::getMonthLongFormat()}}</h6>
					                    <i class="fa fa-check-circle f-left cuz-icon"></i>
					                    <p class="m-b-0">Completed<span class="f-right m-box-completed" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>

					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-blue order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 a-title">{{DateHelper::getYear()}}</h6>
					                    <i class="fa fa-spinner f-left cuz-icon"></i>
					                    <p class="m-b-0">Replied<span class="f-right a-box-replied" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					        
 
					        <div class="col-md-3 col-xl-2">
					            <div class="card bg-c-yellow order-card">
					                <div class="card-block">
					                    <h6 class="m-b-20 a-title">{{DateHelper::getYear()}}</h6>
					                   <i class="fa fa-check-circle f-left cuz-icon"></i>
					                    <p class="m-b-0">Completed<span class="f-right a-box-completed" style="font-weight: bold; color: #fff;">0</span></p>
					                </div>
					            </div>
					        </div>
					        
						</div>
					</div>
			
<!-- 						<div class="container"> -->
<!-- 						<div class="row"> -->
<!-- 							<div class="col-md-3"> -->
<!-- 								<div class="card text-center"> -->
<!-- 									<div class="card-header bg-primary text-white"> -->
<!-- 								         <div class="row align-items-center"> -->
<!-- 								         	<div class="col"> -->
<!-- 								         		<i class="fa fa-list fa-4x"></i> -->
<!-- 								         	</div> -->
<!-- 								         	<div class="col"> -->
<!-- 								         		<h3 class="display-3">08</h3> -->
<!-- 								         		<h6>August</h6> -->
<!-- 								         	</div> -->
<!-- 								         </div> -->
<!-- 							        </div> -->
<!-- 							        <div class="card-footer"> -->
<!-- 								         <h5><a href="#" class="text-primary">View Details</a> </h5> -->
<!-- 							        </div> -->
<!-- 							    </div> -->
<!-- 							</div> -->
<!-- 							<div class="col-md-4">&nbsp;</div> -->
<!-- 							<div class="col-md-4">&nbsp;</div> -->
<!-- 						</div> -->
<!-- 					</div>	 -->
					
					
					
					
					
				    <!-- Box Section End -->
					
					
					
					
				   <!-- Section 01 -->
					<div class="clearfix"></div>
					<hr>					
					<h4 style="padding-bottom: 10px; font-size: 14px;"><b>Number of Customer Complaints</b></h4>
					
					<div class="row">
						<div class="col-md-6">						
							<canvas id="myChart1"></canvas>
						</div>
						<div class="col-md-6">
							<canvas id="myChart2"></canvas>
						</div>
					</div>
					
					<!-- Section 01 -->					
					
				   <!-- Section 02 -->
				   
					<div class="clearfix"></div>					
					<hr>
					<h4 style="padding-bottom: 10px; font-size: 14px;"><b>STATUS OF COMPLAINTS</b> </h4>
					
					<div class="row">
						<div class="col-md-6">			
							<canvas id="myChart3"></canvas>
							<div id="chart3_legend" class="legend"></div>
						</div>
						<div class="col-md-6">
							<canvas id="myChart4"></canvas>
							<div id="chart4_legend" class="legend"></div>
						</div>
					</div>
					
					<!-- Section 02 -->					
					  
					 <!-- Section 03 -->
				   
					<div class="clearfix"></div>					
					<hr>
					<h4 style="padding-bottom: 10px; font-size: 14px;"><b>AREA WISE ANALYSIS</b> </h4>
					
					<div class="row">
						<div class="col-md-6">						
							<canvas id="myChart5"></canvas>
						</div>
						<div class="col-md-6">
							<canvas id="myChart6"></canvas>
						</div>
					</div>
					
					<!-- Section 03 -->						
					
					   <hr>
					     
					<!-- Section 04 -->
				   
					<div class="clearfix" ></div>
					
					<!--
					<hr>
					 <h4 style="padding-bottom: 10px; font-size: 14px;">
					  <u>STATUS OF COMPLAINTS</u> - <span style="font-size: small; color: #34495e;">
					  As at 11 September 2019</span>
					  </h4>  -->

					<div class="row">
						<div class="col-md-4">
							<div class="displayTable">  
								<div class="table-responsive">
							      <table class="table table-striped m-complaint-time-resolution-table jambo_table">
							         <thead>
							            <tr class="headings">
							              	<th class="column-title">Description</th>
							              	<th class="column-title table-Header-Month">{{DateHelper::getMonthLongFormat()}}</th>
							            </tr>
							         </thead>
							         <tbody>
							         </tbody>
							      </table>
							 	</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="displayTable">  
								<div class="table-responsive">
							      <table class="table table-striped a-complaint-time-resolution-table jambo_table">
									<thead>
							            <tr class="headings">
											<th class="column-title">Month</th>
											<th class="column-title">Complaint_Received</th>
											<th class="column-title">Attended</th>
											<th class="column-title">Resolved</th>
											<th class="column-title">Unresolved</th>
										</tr>
									</thead>
							         <tbody>
							         </tbody>
								</table>
								</div>
							</div>
						</div>
					</div>
					<!-- Section 04 -->			
				</div>

			</div>
		
		
		</div>
	</div>

</div>


@endsection @section('page-js')
@include('dashboard.partials.ajax-setup')
	<script type="text/javascript" src="{{ mix('dashboard/compiled/js/dashboard/manage.min.js') }}"></script>
@endsection
