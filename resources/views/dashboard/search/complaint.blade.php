@extends('dashboard.layout.master-layout')

@section('page-title', 'Complaint Handling System | Complaint')

@section('meta-description')
    Complaint Handling System | Complaint
@endsection

@section('canonical-url')
    <link rel="canonical" href="{{ URL::to('/') }}">	
@endsection

@section('page-css')
    <link href="{{ asset('dashboard/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/vendors/external_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('dashboard/compiled/css/custom-setup.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dashboard/vendors/external_components/ckeditor_4.10.0_full/ckeditor/contents.css') }}">
@endsection

@section('page-header-js')
    <script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/ckeditor_4.10.0_full/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/vendors/external_components/ckeditor_4.10.0_full/ckeditor/styles.js') }}"></script>
<!-- 	<script type="text/javascript" src="{{ asset('dashboard/vendors/dropzone/dist/min/dropzone.min.js') }}"></script> -->
<!-- 	<script type="text/javascript" src="{{ asset('dashboard/vendors/cropper/dist/cropper.min.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('dashboard/vendors/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/js/selectors/select2-dropdownPosition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/vendors/switchery/dist/switchery.min.js') }}"></script>
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

            @php $displaySetup = true; @endphp
            @php $displayDetails = true; @endphp
            @php $solutionAdminActions = false; @endphp
            @php $displayActionInfo = true; @endphp
            @php $displayType = 'Complaint'; @endphp

            @if(isset($complaint->type))
                @if($complaint->type == 'CMPLA')
                        @php $displayType = 'Complaint'; @endphp
                @else
                        @php $displayType = 'Compliment'; @endphp
                @endif
            @endif

            <!-- Only setup display for users having raise role. currently all the roles have this permission neither super admin -->
             @hasanyrole(RoleHelper::getComplaintRaiseRoles())
                    <!-- need to check user has recipeint role in complaint_users table -->
                    <!-- In edit user should be in complaint_users table as recipeient user -->
                    @if($mode == 'edit')
                            <!-- Complaint Recipient -->
                            @if(in_array($complaint->status, ['PEN','REJ']))
                                    @if(isset($complaint->userRoles[UserHelper::getLoggedUserId()]) && $complaint->userRoles[UserHelper::getLoggedUserId()] == 'RECPT')
                                            <!-- Display Details -->
                                            @php $displaySetup = true; $noActionSetup = 1; @endphp
                                    @else
                                    <!-- Complaint Check to display admin options-->
                                    @hasanyrole(RoleHelper::getAdminViewRoles())
                                            @php $displayDetails = true; $noActionSetup = 1; @endphp
                                    @endhasanyrole
                                    <!-- Complaint check if the zonal admin options -->
                                    @hasanyrole(RoleHelper::getZonalAdminRoles())
                                            @php $displayDetails = true; $noActionSetup = 1; @endphp
                                    @endhasanyrole
                                    <!-- Complaint check if the regional admin options -->
                                    @hasanyrole(RoleHelper::getRegionalAdminRoles())
                                            @php $displayDetails = true; $noActionSetup = 1; @endphp
                                    @endhasanyrole
                                    <!-- Complaint check if the branch admin options -->
                                    @hasanyrole(RoleHelper::getBranchAdminRoles())
                                            @php $displayDetails = true; $noActionSetup = 1; @endphp
                                    @endhasanyrole
                                    @endif
                            @else
                                    <!-- Complaint Recipient -->
                                    <!-- Complaint Recipient can be ccc/user -->
                                    @if(isset($complaint->userRoles[UserHelper::getLoggedUserId()]) && $complaint->userRoles[UserHelper::getLoggedUserId()] == 'RECPT')
                                            <!-- Display Details -->
                                            @php $displayDetails = true; @endphp
                                            <!-- Action Setup -->
                                            @php $noActionSetup = 1; @endphp

                                            <!-- Complaint Check if the the ccc display admin options-->
                                            @hasanyrole(RoleHelper::getAdminViewRoles())
                                                    @php $solutionAdminActions = true; @endphp
                                            @if($complaint->status == 'CLO' || $complaint->status == 'COM')
                                                    @php $displayReOpen = true; @endphp
                                            @endif
                                    @endhasanyrole
                                    <!-- Complaint Owner -->
                                    @elseif(isset($complaint->userRoles[UserHelper::getLoggedUserId()]) && $complaint->userRoles[UserHelper::getLoggedUserId()] == 'OWNER')
                                            <!-- Display Details -->
                                            @php $displayDetails = true; @endphp
                                            <!-- Action Setup -->
                                            @if(isset($complaint->solutions) && count($complaint->solutions) > 0)
                                                    @foreach($complaint->solutions as $solution)
                                                            @if(UserHelper::checkUserIsLoggedIn($solution->resolvedByUser->user))
                                                                    @php
                                                                            $noActionSetup = 1; break;
                                                                    @endphp
                                                            @endif
                                                    @endforeach
                                            @endif
                                            @if(isset($complaint->escalations) && count($complaint->escalations) > 0)
                                            @foreach($complaint->escalations as $escalation)
                                                    @if($escalation->status == 'COM')
                                                            @if(UserHelper::checkUserIsLoggedIn($escalation->escalatedBy->user))
                                                                    @php
                                                                            $noActionSetup = 1;
                                                                            $escalatedByInfo = $escalation; break; 
                                                                    @endphp
                                                            @endif
                                                    @endif
                                                    @if(($escalation->status == 'INP' || $escalation->status == 'PAS') && UserHelper::checkUserIsLoggedIn($escalation->escalatedBy->user))
                                                            @php
                                                                    $noActionSetup = 1;
                                                                    $escalatedByInfo = $escalation;  break;
                                                            @endphp
                                                    @endif
                                                    @if($escalation->status == 'REJ')
                                                            @if(UserHelper::checkUserIsLoggedIn($escalation->escalatedBy->user))
                                                                    @php
                                                                            $noActionSetup = 0;
                                                                    @endphp
                                                            @endif
                                                    @endif
                                            @endforeach

                                            @endif
                                            <!-- If user has any status. Very carful with status updates in users . Earlier values was only 'EREP','EREREP','REPTRNFR','EREPTRNFR' -->
                                            @if(in_array($complaint->userStatus[UserHelper::getLoggedUserId()], ['REP','REPP','EREP','EREREP','REPTRNFR','EREPTRNFR']))
                                                    @php
                                                            $noActionSetup = 1;
                                                    @endphp
                                            @endif

                                       <!-- Complaint Check if the the ccc display admin options-->
                                            @hasanyrole(RoleHelper::getAdminViewRoles())
                                                    @php $solutionAdminActions = true; @endphp
                                            @if($complaint->status == 'CLO' || $complaint->status == 'COM')
                                                    @php $displayReOpen = true; @endphp
                                            @endif
                                    @endhasanyrole
                                    <!-- Complaint Escalated To -->
                                    @elseif(isset($complaint->userRoles[UserHelper::getLoggedUserId()]) && $complaint->userRoles[UserHelper::getLoggedUserId()] == 'ESCAL')
                                            <!-- Display Details -->
                                            @php $displayDetails = true; @endphp
                                            <!-- Action Setup -->
                                            @if(isset($complaint->solutions) && count($complaint->solutions) > 0)
                                            @foreach($complaint->solutions as $solution)
                                                    @if(UserHelper::checkUserIsLoggedIn($solution->resolvedByUser->user))
                                                            @php
                                                                    $noActionSetup = 1; break;
                                                            @endphp
                                                    @endif
                                            @endforeach
                                            @endif
                                            @if(isset($complaint->escalations) && count($complaint->escalations) > 0)
                                            @foreach($complaint->escalations as $escalation)
                                                    @if($escalation->status == 'COM')
                                                            @if(UserHelper::checkUserIsLoggedIn($escalation->escalatedBy->user))
                                                                    @php
                                                                            $noActionSetup = 1;
                                                                            $escalatedToInfo = $escalation; break; 
                                                                    @endphp
                                                            @endif
                                                    @endif
                                                    @if($escalation->status == 'INP')
                                                            @if(UserHelper::checkUserIsLoggedIn($escalation->escalatedTo->user))
                                                                    @php
                                                                            $noActionSetup = 0;
                                                                            $escalatedToInfo = $escalation; break; 
                                                                    @endphp
                                                            @elseif(UserHelper::checkUserIsLoggedIn($escalation->escalatedBy->user))
                                                                    @php
                                                                            $noActionSetup = 1;
                                                                            $escalatedToInfo = $escalation; break; 
                                                                    @endphp
                                                            @endif
                                                    @elseif($escalation->status == 'PAS' && (UserHelper::checkUserIsLoggedIn($escalation->escalatedTo->user) || UserHelper::checkUserIsLoggedIn($escalation->escalatedBy->user)))
                                                            @php
                                                                    $noActionSetup = 1;
                                                                    $escalatedToInfo = $escalation; break; 
                                                            @endphp
                                                    @elseif($escalation->status == 'REJ')
                                                            @if(UserHelper::checkUserIsLoggedIn($escalation->escalatedTo->user))
                                                                    @php $noActionSetup = 1; @endphp
                                                                    <script type="text/javascript">
                                                                            var url = "{{ route('dashboard.error.load',['type' => '405']) }}"; 
                                                                            location.href = url;
                                                                    </script>
                                                            @endif
                                                    @endif
                                            @endforeach
                                            @endif
                                            @if(in_array($complaint->userStatus[UserHelper::getLoggedUserId()], ['REP','REPP','EREP','EREREP','REPTRNFR','EREPTRNFR']))
                                                    @php $noActionSetup = 1; @endphp
                                                    @if(in_array($complaint->userStatus[UserHelper::getLoggedUserId()], ['REP','REPP','REPTRNFR']))
                                                            @php $displayActionInfo = false; @endphp
                                                    @endif
                                            @endif

                                            <!-- Complaint Check if the the ccc display admin options-->
                                            @hasanyrole(RoleHelper::getAdminViewRoles())
                                            @php $solutionAdminActions = true; @endphp
                                            @if($complaint->status == 'CLO' || $complaint->status == 'COM')
                                                    @php $displayReOpen = true; @endphp
                                            @endif
                                    @endhasanyrole
                                    @else
                                    <!-- Complaint Check if the the ccc display admin options-->
                                    @hasanyrole(RoleHelper::getAdminViewRoles())
                                            @php $displayDetails = true; $noActionSetup = 1; $solutionAdminActions = true; @endphp
                                            @if($complaint->status == 'CLO' || $complaint->status == 'COM')
                                                    @php $displayReOpen = true; @endphp
                                            @endif
                                    @endhasanyrole
                               @endif
                            @endif
                    @else
                            <!-- New Complaint -->
                            @php $displaySetup = true; $noActionSetup = 1; @endphp
                    @endif
             @else
                 <!-- Complaint Check to display super admin options if no complaint raise permissions. if any role remove from raise, need to add here to view the complaint-->
                 @hasanyrole(RoleHelper::getAdminRoles())
                    @php $displayDetails = true; $noActionSetup = 1; @endphp
             @else
                    @php $noActionSetup = 1; @endphp
                            <script type="text/javascript">
                                    var url = "{{ route('dashboard.error.load',['type' => '405']) }}"; 
                                    location.href = url;
                            </script>
             @endhasanyrole
             @endhasanyrole

             @if(isset($displaySetup) && $displaySetup)
                    <!--  Panel 01 Start -->
                        <!--  Complaint Raise Removed -->
                    <!--  Panel 01 End -->
                    @endif

                    <!--  Panel 02 Start -->

                    @if($displayDetails)
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Complain Details</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form id="demo-form2" data-parsley-validate
                                    class="form-horizontal form-label-left">
                                    <!-- Complain Lodge Detail Panel 01 - Start-->					
                                            <div class="row">
                                            <div class="col-md-5">
                                                    <div class="row">
                                                            <div class="col-md-5">
                                                            <label class="control-label">Reference Number</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">										
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->reference_number?$complaint->reference_number:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="row">
                                                            <div class="col-md-4 col-md-offset-1">
                                                            {{-- <label class="control-label">Complaint Date</label> --}}
                                                            <label class="control-label">{{$displayType}} Date</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->open_date? DateHelper::getLongFormatDateString(false, $complaint->open_date):'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>	
                                    <div class="clearfix">&nbsp;</div>				
                                    <div class="row">
                                            <div class="col-md-5">
                                                    <div class="row">
                                                            <div class="col-md-5">
                                                            <label class="control-label">Account/Card Number</label>
                                                            </div>
                                                            <div class="col-md-7">				
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->account_no?$complaint->account_no:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            @if(isset($complaint->complainant))
                                            <div class="col-md-6">
                                                    <div class="row">
                                                            <div class="col-md-4 col-md-offset-1">
                                                            <label class="control-label">NIC/EIC</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->complainant && $complaint->complainant->nic?$complaint->complainant->nic:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    <!-- Complain Lodge Detail Panel 02 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 03 - Start-->

                                    <div class="row">
                                            <div class="col-md-5">
                                                    <div class="row">
                                                            <div class="col-md-5">
                                                            <label class="control-label">First Name</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->complainant && $complaint->complainant->first_name?$complaint->complainant->first_name:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="row">
                                                            <div class="col-md-4 col-md-offset-1">
                                                            <label class="control-label">Last Name</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->complainant && $complaint->complainant->last_name?$complaint->complainant->last_name:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    <!-- Complain Lodge Detail Panel 02 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 02 - Start-->
                                    <div class="row">
                                            <div class="col-md-5">
                                                    <div class="row">
                                                            <div class="col-md-5">
                                                            <label class="control-label">Contact Number</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->complainant && $complaint->complainant->contact_no?$complaint->complainant->contact_no:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>

                                            @endif
                                            <div class="col-md-6">
                                                    <div class="row">
                                                            <div class="col-md-4 col-md-offset-1">
                                                            <label class="control-label">Priority Level</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->priority_level?$complaint->priority_level_text:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    <!-- Complain Lodge Detail Panel 03 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 04 - Start-->
                                    <div class="row">
                                            <div class="col-md-5">
                                                    <div class="row">
                                                            <div class="col-md-5">
                                                            <label class="control-label">Detailed Category</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->category->name?$complaint->category->name:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="row">
                                                            <div class="col-md-4 col-md-offset-1">
                                                            <label class="control-label">Sub Category</label>
                                                            </div>
                                                            <div class="col-md-7">
                                                                    <div class="complaint-detail-div detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{{($mode == 'edit' && $complaint->category->parentCategory?$complaint->category->parentCategory->name:'')}}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    <!-- Complain Lodge Detail Panel 04 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 05 - Start-->
                                    <!-- CR2 changes -->
                                    @if($complaint->is_reporting_complaint == false)
                                    <div class="row">
                                            <div class="col-md-12">
                                                    <div class="row">
                                                            <div class="col-md-2">
                                                            {{-- <label class="control-label">Complaint users</label> --}}
                                                            <label class="control-label">{{$displayType}} users</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                    <table cellpadding="1" class="table-users detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <tr>
                                                                                    <th>Name</th>
                                                                                    <th>Email</th>
                                                                                    <th>Branch/Dept</th>
                                                                                    <th>Role</th>
                                                                                    <th>Status</th>
                                                                            </tr>
                                                                            @foreach($complaint->complaintUsers as $user)
                                                                            <tr>
                                                                                    <td>{{$user->user->first_name}} {{$user->user->last_name}}</td>
                                                                                    <td>{{$user->user->email}}</td>
                                                                                    <td>{{$user->user->departmentUser->department->name}}</td>
                                                                                    <td>{{($user->user_role == "RECPT"? "Recipient": ($user->user_role == "OWNER"? "Owner" : "Escalate"))}}</td>
                                                                                    <td><span class="detail-tag display-complaint-user-status-{{$user->user->user_id_pk}}"></span></td>
                                                                                    <script type="text/javascript">
                                                                                            $('.display-complaint-user-status-'+{{$user->user->user_id_pk}}).html(actionStatus("{{($user->status? $user->status:'') }}"));
                                                                                    </script>
                                                                            </tr>
                                                                            @endforeach
                                                                    </table>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    @endif
                                    <!-- Complain Lodge Detail Panel 05 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 06 - Start-->
                                    <div class="row">
                                            <div class="col-md-12">
                                                    <div class="row">
                                                            <div class="col-md-2">
                                                            {{-- <label class="control-label">Complaint</label> --}}
                                                            <label class="control-label">{{$displayType}}</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                    <div class="complaint-detail-div cuz-col detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <span class="detail-tag">{!! ($mode == 'edit' && $complaint->complaint?$complaint->complaint:'') !!}</span> <br>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    <!-- Complain Lodge Detail Panel 06 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 07 - Start-->
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    {{-- <label class="control-label">Complaint Status</label> --}}
                                                    <label class="control-label">{{$displayType}} Status</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="complaint-detail-div hide-border">
                                                                <span class="detail-tag display-complaint-details-status"></span> <br>
                                                                <script type="text/javascript">
                                                                    $('.display-complaint-details-status').html(actionStatus("{{ ($mode == 'edit' && $complaint->complaint? ($complaint->reopened['status']? $complaint->reopened['type'].'ROPEN' : $complaint->status):'') }}"));
                                                                </script>
                                                            </div>
                                                        </div>
<!--                                                            @if(isset($displayReOpen) && $displayReOpen)
                                                            @if($complaint->status == 'COM')
                                                                <div class="col-md-2">
                                                                    <a data-popup="tooltip" title="Close" data-original-title="Close" href="javascript:void(0);" 
                                                                                onclick="updateStatus('dashboard/complaints','{{$complaint->complaint_id_pk}}',true,'status','CLO',true)"><i class="fa fa-check-square-o fa-2x"></i></a>
                                                                </div>
                                                            @endif
                                                            <div class="col-md-3">
                                                                <a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" 
                                                                            onclick="updateStatus('dashboard/complaints','{{$complaint->complaint_id_pk}}',true,'status','INP',true)"><i class="fa fa-folder-open fa-2x"></i></a>
                                                            </div>
                                                        @endif-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Complaint Notify To Users -->
						@if(count($complaint->complaintNotificationOtherUsers) > 0)
                                                <div class="row" style="margin-top: 25px;">
                                                        <div class="col-md-12">
                                                                <div class="row">
                                                                        <div class="col-md-2">
                                                                                <label class="control-label">Notify To</label>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                                <table cellpadding="1" class="table-users detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                                        <tr>
                                                                                                <th>Name</th>
                                                                                                <th>Email</th>
                                                                                        </tr>
                                                                                        @foreach($complaint->complaintNotificationOtherUsers as $user)
                                                                                        <tr>
                                                                                                <td>{{$user->user->first_name}} {{$user->user->last_name}}</td>
                                                                                                <td>{{$user->user->email}}</td>
                                                                                        </tr>
                                                                                        @endforeach
                                                                                </table>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                </div>
                                        @endif
                                        <!-- Complaint Notify To Users End -->

                                    <!-- Complain Lodge Detail Panel 07 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 08-->
                                    @if(count($complaint->complaintReopens) > 0)
                                    <div class="row">
                                            <div class="col-md-12">
                                                    <div class="row">
                                                            <div class="col-md-2">
                                                            {{-- <label class="control-label">Complaint reopens</label> --}}
                                                            <label class="control-label">{{$displayType}} reopens</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                    <table cellpadding="1" class="table-users detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <tr>
                                                                                    <th>Opened/Rejected By</th>
                                                                                    <th width="50%">Reason</th>
                                                                                    <th width="20%">Date</th>
                                                                                    <th width="12%">Status</th>
                                                                            </tr>
                                                                            @foreach($complaint->complaintReopens as $reopen)
                                                                            <tr>
                                                                                    <td>{{$reopen->reopnedBy->first_name}} {{$reopen->reopnedBy->last_name}}</td>
                                                                                    <td>{{$reopen->reopen_reason}}</td>
                                                                                    <td>{{$reopen->created_at}}</td>
                                                                                    <td><span class="detail-tag display-complaint-reopen-status-{{$reopen->complaint_reopen_id_pk}}"></span></td>
                                                                                    <script type="text/javascript">
                                                                                            $('.display-complaint-reopen-status-'+{{$reopen->complaint_reopen_id_pk}}).html(actionStatus("{{($reopen->status? $reopen->status:'') }}"));
                                                                                    </script>
                                                                            </tr>
                                                                            @endforeach
                                                                    </table>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    @endif
                                    <!-- Complain Lodge Detail Panel 08 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Lodge Detail Panel 09 End-->
                                    @if(count($complaint->complaintCloses) > 0)
                                    <div class="row">
                                            <div class="col-md-12">
                                                    <div class="row">
                                                            <div class="col-md-2">
                                                            {{-- <label class="control-label">Complaint closes</label> --}}
                                                            <label class="control-label">{{$displayType}} closes</label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                    <table cellpadding="1" class="table-users detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                                            <tr>
                                                                                    <th>Closed By</th>
                                                                                    <th width="50%">Remarks</th>
                                                                                    <th width="20%">Date</th>
                                                                                    <th width="12%">Status</th>
                                                                            </tr>
                                                                            @foreach($complaint->complaintCloses as $close)
                                                                            <tr>
                                                                                    <td>{{$close->closedBy->first_name}} {{$close->closedBy->last_name}}</td>
                                                                                    <td>{{$close->remarks}}</td>
                                                                                    <td>{{$close->created_at}}</td>
                                                                                    <td><span class="detail-tag display-complaint-close-status-{{$close->complaint_close_id_pk}}"></span></td>
                                                                                    <script type="text/javascript">
                                                                                            $('.display-complaint-close-status-'+{{$close->complaint_close_id_pk}}).html(actionStatus("{{($close->status? $close->status:'') }}"));
                                                                                    </script>
                                                                            </tr>
                                                                            @endforeach
                                                                    </table>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                                    @endif
                                    <!-- Complain Lodge Detail Panel 09 End-->
                                    <div class="clearfix">&nbsp;</div>
                                    <!-- Complain Reject Detail Panel 10 End-->                                                
                                    @if(!$complaint_rejections->isEmpty())
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    {{-- <label class="control-label">Complaint Rejection</label> --}}
                                                    <label class="control-label">{{$displayType}} Rejection</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <table cellpadding="1" class="table-users detail-border-{{($mode == 'edit' && $complaint->priority_level_text?$complaint->priority_level_text:'Normal')}}">
                                                        <tr>
                                                            <th>Rejected By</th>
                                                            <th>Email</th>
                                                            <th>Remarks</th>
                                                            <th>Date</th>
                                                            <!--<th width="12%">Status</th>-->
                                                        </tr>
                                                        @foreach($complaint_rejections as $reject)
                                                            <tr>
                                                                <td>{{$reject->user_info->first_name}} {{$reject->user_info->last_name}}</td>
                                                                <td>{{$reject->user_info->email}}</td>
                                                                <td>{{$reject->reject_reason}}</td>
                                                                <td>{{$reject->created_at}}</td>
                                                                <td><span class="detail-tag display-complaint-close-status-{{$reject->complaint_id_fk}}"></span></td>
                                                                <script type="text/javascript">
//                                                                                    $('.display-complaint-close-status-'+{{$reject->complaint_id_fk}}).html(actionStatus("{{($reject->status? $reject->status:'') }}"));
                                                                </script>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                <!-- Complain Reject Detail Panel 10 End-->
                                <!-- Complain Other Details Panel -->
                                @if($complaint->is_reporting_complaint == true)
                                <div class="row">
                                        <div class="col-md-5">
                                                <div class="row">
                                                        <div class="col-md-5">
                                                                <label class="control-label">Other Details</label>
                                                        </div>
                                                        <div class="col-md-7">
                                                                <div class="row attribute-value">
                                                                        <div class="col-md-6">
                                                                                <div class="complaint-detail-div hide-border">
                                                                                        <span class="badge badge-info badge-custom">Reporting</span>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                @endif
                                <!-- Complain Other Details Panel End-->
                                <div class="ln_solid"></div>
                            </form>
                        </div>
                    </div>

                    <!--  Panel 02 End -->

                    @hasanyrole(RoleHelper::getSolutionViewRoles()) <!-- CCC Role Included -->
                    <!-- Panel 03 Start -->
                    <div class="x_panel">
                        <div class="x_title">
                                <h2>{{$displayType}} Solutions</h2>
                            {{-- <h2>Complaint Solutions</h2> --}}
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                            <div class="x_content">
                                    <br />
                                    <form id="demo-form2" data-parsley-validate
                                            class="form-horizontal form-label-left">

                                            <div class="row">
                                                    <div class="col-md-12">

                                                            <div class="container">
                                                                    <div class="row">
                                                                            <div class="col-md-12">
                                                                                    <section class="comment-list">
                                                                                            <!-- First Comment -->
                                                                                            @if(isset($complaint->solutions) && count($complaint->solutions) > 0)
                                                                                                    @foreach($complaint->solutions as $solution)
                                                                                                            <!-- Initially we set display solution to false -->
                                                                                                            @php $displaySolution[$solution->complaint_solution_id_pk] = false; @endphp
                                                                                                            <!-- If the solution is going to edit, solution will not display in solutions panel --> 
                                                                                                            @if(isset($subMode) && $subMode = 'solutionEdit' && (int)$solution->complaint_solution_id_pk == (int)$complaint->edit_solution_id_pk)
                                                                                                            <!-- If the solution is in DRAFT/Not Accepted, solution will only display to solution added user --> 
                                                                                                            @elseif(in_array($solution->status, ['PEN','NACP']) && isset($solution->resolvedBy) && UserHelper::checkUserIsLoggedIn($solution->resolvedBy->user))
                                                                                                                    @php $displaySolution[$solution->complaint_solution_id_pk] = true; @endphp
                                                                                                            <!-- If the solution ACP/VFD will display the solution in panel -->
                                                                                                            @elseif(in_array($solution->status, ['VFD','ACP']))
                                                                                                                    @php $displaySolution[$solution->complaint_solution_id_pk] = true; @endphp
                                                                                                            @endif

                                                                                                    @if($displaySolution[$solution->complaint_solution_id_pk])
                                                                                                        <article class="row">
                                                                                                            <div class="col-md-1 col-sm-2 hidden-xs">
                                                                                                                <figure class="thumbnail user-avat">
                                                                                                                    <img class="img-responsive" src="{{ asset('/storage/images/common/user-ava.jpg') }}" />
                                                                                                                    <figcaption class="text-center"></figcaption>
                                                                                                                </figure>
                                                                                                            </div>
                                                                                                            <div class="col-md-11 col-sm-10">
                                                                                                                <div class="panel panel-default arrow left panel-custom-width">
                                                                                                                    <div class="panel-body">
                                                                                                                        <header class="text-left" style="line-height: 1.8em;">
                                                                                                                            <div class="comment-user">
                                                                                                                                <i class="fa fa-user" style="padding-right: 5px;"></i> <span style="font-weight: 800;">{{$solution->resolvedByUser->user->first_name}} {{$solution->resolvedByUser->user->last_name}}</span> <span style="float: right" class="solution-status-tag solution-status-{{$solution->complaint_solution_id_pk}}"></span>
                                                                                                                                <script type="text/javascript"> $('.solution-status-'+{{$solution->complaint_solution_id_pk}}).html(actionStatus("{{ $solution->status }}"));</script>
                                                                                                                            </div>
                                                                                                                            <time class="comment-date" datetime="16-12-2014 01:05">
                                                                                                                                <i class="fa fa-clock-o" style="padding-right: 7px;font-size: 10px;"></i>  
                                                                                                                                <span style="font-size: 11px;"> {{DateHelper::getLongFormatDateString(true, $solution->updated_at)}}</span>
                                                                                                                            </time>
                                                                                                                        </header>
                                                                                                                        <div class="comment-post">
                                                                                                                            <p>{!! $solution->action_taken !!}</p>
                                                                                                                        </div>
<!--                                                                                                                        @if(isset($solution->resolvedBy) && UserHelper::checkUserIsLoggedIn($solution->resolvedByUser->user) && $complaint->status !== 'CLO')
                                                                                                                            <p class="text-right">
                                                                                                                                @if($solution->status == 'PEN' || $solution->status == 'NACP')
                                                                                                                                    <a data-popup="tooltip" title="Verify" data-original-title="Verify" href="javascript:void(0);" onclick="updateStatus('dashboard/complaints/solutions','{{$solution->complaint_solution_id_pk}}',false,'status','VFD')">
                                                                                                                                        <i class="fa fa-check-square-o fa-2x"></i>
                                                                                                                                    </a>
                                                                                                                                    <a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow('dashboard/complaints','{{$solution->complaint_id_fk}}',false,'solutionEdit','{{$solution->complaint_solution_id_pk}}')">
                                                                                                                                        <i class="fa fa-pencil fa-2x"></i>
                                                                                                                                    </a>
                                                                                                                                    <a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow('dashboard/complaints/solutions','{{$solution->complaint_solution_id_pk}}','page')">
                                                                                                                                        <i class="fa fa-trash fa-2x"></i>
                                                                                                                                    </a>
                                                                                                                                @endif
                                                                                                                            </p>
                                                                                                                        @elseif(isset($solution->complaintOwner) && UserHelper::checkUserIsLoggedIn($solution->complaintOwner->user) && $complaint->status !== 'CLO')
                                                                                                                            <p class="text-right">
                                                                                                                                <a data-popup="tooltip" title="Amendments" data-original-title="Amendments"  href="javascript:void(0);" onclick="amendmentByFlow('dashboard/complaints/solutions/amendment','{{$solution->complaint_solution_id_pk}}',true)">
                                                                                                                                    <i class="fa fa-commenting fa-2x"></i>
                                                                                                                                </a>
                                                                                                                                @if($solution->status !== 'ACP')
                                                                                                                                    <a data-popup="tooltip" title="Accept" data-original-title="Accept"  href="javascript:void(0);" onclick="updateStatus('dashboard/complaints/solutions','{{$solution->complaint_solution_id_pk}}',false,'status','ACP')">
                                                                                                                                        <i class="fa fa-check-square-o fa-2x"></i>
                                                                                                                                    </a>
                                                                                                                                @endif
                                                                                                                                @if($solution->status !== 'NACP')
                                                                                                                                    <a data-popup="tooltip" title="Reject" data-original-title="Reject" href="javascript:void(0);" onclick="updateStatus('dashboard/complaints/solutions','{{$solution->complaint_solution_id_pk}}',false,'status','NACP')">
                                                                                                                                        <i class="fa fa-times-circle fa-2x"></i>
                                                                                                                                    </a>		
                                                                                                                                @endif
                                                                                                                            </p>
                                                                                                                        @else
                                                                                                                            @if($solutionAdminActions)
                                                                                                                                <p class="text-right">																		
                                                                                                                                    @if($solution->status !== 'NACP' && $complaint->status !== 'CLO')
                                                                                                                                        <a data-popup="tooltip" title="Amendments" data-original-title="Amendments"  href="javascript:void(0);" onclick="amendmentByFlow('dashboard/complaints/solutions/amendment','{{$solution->complaint_solution_id_pk}}',true)">
                                                                                                                                            <i class="fa fa-commenting fa-2x"></i>
                                                                                                                                        </a>
                                                                                                                                        <a data-popup="tooltip" title="Reject" data-original-title="Reject" href="javascript:void(0);" onclick="updateStatus('dashboard/complaints/solutions','{{$solution->complaint_solution_id_pk}}',false,'status','NACP')">
                                                                                                                                            <i class="fa fa-times-circle fa-2x"></i>
                                                                                                                                        </a>
                                                                                                                                    @endif
                                                                                                                                </p>
                                                                                                                            @endif
                                                                                                                        @endif-->

                                                                                                                        @if(isset($solution->histories) && count($solution->histories) > 0)
                                                                                                                            <div class="x_panel">
                                                                                                                                <div class="x_title">
                                                                                                                                    <span>Solution History <span class="badge badge-pending bg-gray">{{count($solution->histories)}}</span></span>
                                                                                                                                    <ul class="nav navbar-right panel_toolbox">
                                                                                                                                        <li>
                                                                                                                                            <a class="collapse-link"><i class="fa fa-chevron-down"></i></a>
                                                                                                                                        </li>
                                                                                                                                    </ul>
                                                                                                                                    <div class="clearfix"></div>
                                                                                                                                </div>
                                                                                                                                <div class="x_content" style="display: none;">
                                                                                                                                    @foreach($solution->histories as $history)
                                                                                                                                        <div class="history-row">
                                                                                                                                            <header class="text-left" style="line-height: 1.8em;">
                                                                                                                                                <time class="comment-date" datetime="16-12-2014 01:05">
                                                                                                                                                    <i class="fa fa-clock-o" style="padding-right: 7px;font-size: 10px;"></i>  
                                                                                                                                                    <span style="font-size: 11px;"> {{DateHelper::getLongFormatDateString(true, $history->updated_at)}}</span>
                                                                                                                                                </time>
                                                                                                                                            </header>                                                           					
                                                                                                                                            <div class="comment-post">
                                                                                                                                                <p>{!! $history->action_taken !!}</p>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    @endforeach
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        @endif

                                                                                                                        @if(isset($solution->amendments) && count($solution->amendments) > 0)
                                                                                                                            <div class="ln_solid"></div>
                                                                                                                            @foreach($solution->amendments as $amendment)
                                                                                                                                <article class="row">
                                                                                                                                    <div class="col-md-1 col-sm-2 hidden-xs">
                                                                                                                                        <figure class="thumbnail user-avat">
                                                                                                                                            <img class="img-responsive" src="{{ asset('/storage/images/common/user-ava.jpg') }}" />
                                                                                                                                            <figcaption class="text-center"></figcaption>
                                                                                                                                        </figure>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-md-11 col-sm-10">
                                                                                                                                    <div class="panel panel-default arrow left panel-custom-width">
                                                                                                                                        <div class="panel-body">
                                                                                                                                            <header class="text-left" style="line-height: 1.8em;">
                                                                                                                                                <div class="comment-user">
                                                                                                                                                    <i class="fa fa-user" style="padding-right: 5px;"></i>
                                                                                                                                                    <span style="font-weight: 800;">{{$amendment->amendmentBy->first_name}} {{$amendment->amendmentBy->last_name}}</span> 
                                                                                                                                                    <span style="float: right" class="solution-status-tag solution-amendment-status-{{$amendment->solution_amendment_id_pk}}"></span>
                                                                                                                                                    <script type="text/javascript"> $('.solution-amendment-status-'+{{$amendment->solution_amendment_id_pk}}).html(actionStatus("{{ $amendment->status }}"));</script>
                                                                                                                                                </div>
                                                                                                                                                <time class="comment-date" datetime="16-12-2014 01:05">
                                                                                                                                                    <i class="fa fa-clock-o" style="padding-right: 7px;font-size: 10px;"></i>
                                                                                                                                                    <span style="font-size: 11px;"> {{DateHelper::getLongFormatDateString(true, $amendment->updated_at)}}</span>
                                                                                                                                                </time>
                                                                                                                                            </header>
                                                                                                                                            <div class="comment-post">
                                                                                                                                                <p>{!! $amendment->amendment !!}</p>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </article>
                                                                                                                            @endforeach
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </article>
                                                                                                    @endif
                                                                                                    @if(!in_array(true, $displaySolution))
                                                                                                        <div align="center">No Solutions Found !</div>
                                                                                                    @endif
                                                                                                    @endforeach
                                                                                            @else
                                                                                                <div align="center">No Solutions Found !</div>
                                                                                            @endif

                                                                                            <!-- Fourth Comment -->
                                                                                    </section>
                                                                            </div>
                                                                    </div>
                                                            </div>

                                                    </div>

                                            </div>


                                            <div class="ln_solid"></div>

                                    </form>
                            </div>
                    </div>
                    <!-- Panel 03 End -->
                    @endhasanyrole
                    @endif

<!--            @if((isset($displayActionInfo) && $displayActionInfo) || isset($subMode))
                @if(isset($noActionSetup) && $noActionSetup == 1 && !(isset($subMode)))
                    @if(isset($escalatedByInfo) || isset($escalatedToInfo))
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Complaint Action Info</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li>
                                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                @if(isset($escalatedByInfo))
                                    You have been escalated complaint to {{$escalatedByInfo->escalatedTo->user->first_name}} {{$escalatedByInfo->escalatedTo->user->last_name}}
                                @elseif(isset($escalatedToInfo))
                                    You have been escalated complaint to {{$escalatedToInfo->escalatedTo->user->first_name}} {{$escalatedToInfo->escalatedTo->user->last_name}}
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            @endif-->
                    
            <!--  Panel 04 End -->
        </div>
    </div>
</div>
        
@endsection


@section('page-js')
    @include('dashboard.partials.ajax-setup')
    <script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/setup.min.js') }}"></script>
@endsection

@section('modal-js')
    <script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/action.min.js') }}"></script>
    <script type="text/javascript" src="{{ mix('dashboard/compiled/js/complaints/amendment.min.js') }}"></script>
@endsection