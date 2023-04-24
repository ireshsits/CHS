@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Search')

@section('meta-description')
    Complaint Handling System | Search
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
<div class="right_col" role="main">

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-bars"></i> Search </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
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

                        <div class="row">
                            <form id="complaint-search-form" data-parsley-validate class="form-horizontal form-label-left">
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="reference_number">Complaint Ref Number</label>
                                        <select data-placeholder="Select / Insert Ref No." style="width: 100% !important;" class="form-control select-reference-number-search select2-close reference_number-error error-placement" onselect="onSelect(this)" name="reference_number" id="reference_number">
                                            <option></option>
                                            <!--                                            <option value="D_0621-0007">Select REF</option>
                                            <option value="">Select REF</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="1">5</option>-->
                                        </select>
                                    </div>
                                    <!--<input type="text" class="form-control" id="reference_number" name="reference_number" placeholder="Enter REF No.">-->
                                </div>
                            
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    
                                    <div class="form-group">	
                                        <label for="nic">NIC</label>
                                        <select data-placeholder="Select / Insert NIC" style="width: 100% !important;" class="form-control select-nic-search select2-close nic-error error-placement" onselect="onSelect(this)" name="nic" id="nic">
                                            <option></option>
                                            <!--                                            <option value="958895642V">Select NIC</option>
                                            <option value="">Select NIC</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="1">5</option>-->
                                        </select>
                                    </div>
                                    <!--<input type="text" class="form-control" id="nic" name="nic" placeholder="Enter NIC">-->
                                </div>
                            
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="account_no">Account/Card Number</label>
                                        <select data-placeholder="Select / Insert Account/Card No." style="width: 100% !important;" class="form-control select-account-no-search select2-close account_no-error error-placement" onselect="onSelect(this)" name="account_no" id="account_no">
                                            <option></option>
<!--                                            <option value="111122220000">Select ACCOUNT NO</option>
                                            <option value="">Select ACCOUNT NO</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="1">5</option>-->
                                        </select>
                                    </div>
                                    <!--<input type="text" class="form-control" id="account_no" name="account_no" placeholder="Enter Account No.">-->
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label for="contact_no">Contact Number</label>
                                        <select data-placeholder="Select / Insert Contact No." style="width: 100% !important;" class="form-control select-contact-no-search select2-close contact_no-error error-placement" onselect="onSelect(this)" name="contact_no" id="contact_no">
                                            <option></option>
<!--                                            <option value="111122220000">Select ACCOUNT NO</option>
                                            <option value="">Select ACCOUNT NO</option>
                                            <option value="1">2</option>
                                            <option value="1">3</option>
                                            <option value="1">4</option>
                                            <option value="1">5</option>-->
                                        </select>
                                    </div>
                                    <!--<input type="text" class="form-control" id="account_no" name="account_no" placeholder="Enter Account No.">-->
                                </div>
                            
                                <div class="col-md-3 col-sm-8 col-xs-12">
                                    <label>&nbsp;</label>
                                    <div class="form-group">
                                        <button type="submit" id="btn-search" class="btn btn-primary btn-block" style="height: 38px;">Search</button>
                                    </div>
                                </div>
                            
                                <div class="col-md-1 col-sm-4 col-xs-12">
                                    <label>&nbsp;</label>
                                    <div class="form-group">
                                        <button type="button" id="btn-search-clear" class="btn btn-danger btn-block" style="height: 38px;">Clear</button>
                                    </div>
                                </div>
                            
    <!--                            <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="input-search" placeholder="Search by NIC, Account No.">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" id="btn-search" type="button" style="height: 38px;">Search</button>
                                        </span>
                                    </div> /input-group 
                                </div> /.col-lg-6 -->
                            </form>
                        </div><!-- /.row -->

                        @hasanyrole(RoleHelper::getComplaintRaiseRoles())
<!--                            <div style="text-align: right;">
                                <button type="button" class="btn btn-default btn-sm" onclick="actionNew('dashboard/complaints',false)">Setup +</button>
                            </div>-->
                        @endhasanyrole

                        @php $raiseRole = false; @endphp
                    @hasanyrole(RoleHelper::getComplaintRaiseRoles())
                        @php $raiseRole = true; @endphp
                    @endhasanyrole

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
<!--                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="pending-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-hourglass"></i> Draft <span class="badge badge-pending bg-gray">0</span></a>
                            </li>
                            @if($raiseRole)
                            <li role="presentation" class="">
                                <a href="#tab_content2" id="forward-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-mail-forward fa-lg"></i> Sent <span class="badge badge-forward bg-blue">0</span></a>
                            </li>
                            @endif
                            <li role="presentation" class="">
                                <a href="#tab_content3" id="inprogress-escalated-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-spinner fa-lg"></i> Received <span class="badge badge-inprogress-escalated bg-blue">0</span></a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content4" id="completed-tab" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-check-square-o fa-lg"></i> Completed <span class="badge badge-completed bg-blue-sky">0</span></a>
                            </li>
                            <li role="presentation" class="">
                                <a href="#tab_content5" id="closed-tab" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-folder fa-lg"></i> Closed <span class="badge badge-closed bg-green">0</span></a>
                            </li>                        
                        </ul>-->
                        <!--  CR2 removed branch/Dept name from main table columns -->
                        <div role="tabpanel" class="tab-pane fade in" id="tab_content5" aria-labelledby="closed-tab">
                            <div class="table-responsive">
                                <table class="table table-striped {{$type}}-table-search lodged-tables jambo_table">
                                    <thead>
                                        <tr class="headings">
                                            <th style="display: none;"></th>
                                            <th class="column-title" style="width: 3%"></th>
                                            <th class="column-title" style="width: 5%">Ref. Number</th>
                                            <th class="column-title" style="width: 5%">Customer</th>
                                            <th class="column-title" style="width: 5%">NIC</th>
                                            <th class="column-title" style="width: 5%">Account/Card No.</th>
                                            <th class="column-title" style="width: 20%">Area</th>
                                            <th class="column-title" style="width: 30%">Category </th>
                                            <th class="column-title" style="width: 10%">Open Date </th>
                                            <th class="column-title" style="width: 5%">Mode </th>
                                            <!--<th class="column-title" style="width: 5%">Source</th>-->
                                            <th class="column-title" style="width: 5%">Status</th>
                                            <th class="column-title no-link last" style="width: 17%">
                                                <span class="nobr">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
<!--                        <div id="myTabContent" class="tab-content">
                            
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="pending-tab">
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
                            <div role="tabpanel" class="tab-pane fade in" id="tab_content2" aria-labelledby="forward-tab">
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
                            
                            <div role="tabpanel" class="tab-pane fade in" id="tab_content3" aria-labelledby="inprogress-escalated-tab">
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

                            <div role="tabpanel" class="tab-pane fade in" id="tab_content4" aria-labelledby="completed-tab">
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

                            <div role="tabpanel" class="tab-pane fade in" id="tab_content5" aria-labelledby="closed-tab">
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
                        </div>-->
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
    <script type="text/javascript" src="{{ mix('dashboard/compiled/js/search/manage.min.js') }}"></script>
@endsection

@section('modal-js')
    <script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/action.min.js') }}"></script>
@endsection