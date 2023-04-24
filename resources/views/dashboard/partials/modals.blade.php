<!-- Category form modal -->
<div id="modal_category_form" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Category Setup</h5>
            </div>

            <form id="category-setup-form">
            	<input type="hidden" name="mode" id="mode"/>
            	<input type="hidden" name="id" id="category_id_pk"/>
            	<input type="hidden" name="" id="refresh"/>
            	<input type="hidden" name="" id="refresh_table"/>
                <div class="modal-body">
                        <div class="row">
                        	<!-- CR2 changed Recepeient_user role to user -->
                    		@hasanyrole(RoleHelper::getAdminRoles())
                    		<div class="col-md-6">
	                            <div class="form-group">
	                                <label>Name</label>
	                                <input type="text" placeholder="Category Name" id="category_name" class="form-control name-error error-placement" name="name">
	                            </div>
                           	</div>
                    		<div class="col-md-3">
                    		<label>Status</label>
		                        <div class="form-group">		                            
				                    <select data-placeholder="Select Status" style="width: 100% !important;" class="form-control select-categorystatus-search select2-close status-error error-placement" onselect="onSelect(this)" name="status" id="category_status">
				                    <option></option>
				                    </select>
								</div>
							</div>
							<div class="col-md-3">
							<label>Color</label>
								<div class="form-group">
									<input id="simple-color-picker" type="text" class="form-control" name="color"/>
								</div>
							</div>
<!-- 							<div class="card"> -->
<!-- 	                            <div class="card-body text-center d-flex justify-content-center align-items-center flex-column"> -->
<!-- 	                            <div id="color-picker-1" class="mx-auto"></div> -->
<!-- 	                            </div> -->
<!--                             </div> -->
							@endhasanyrole
						</div>
						<div class="row">
                    		<div class="col-md-5">
                    		<label>Category Level</label>
		                        <div class="form-group">		                            
				                    <select data-placeholder="Select Status" style="width: 100% !important;" class="form-control select select2-close category_level-error error-placement" onselect="onSelect(this)" name="category_level" id="category_level">
    				                    @for($i = 1; $i<=$category_settings->CATEGORY_LEVELS+1; $i++)
    				                    <option value="{{$i}}">Level {{$i}}</option>
    				                    @endfor
				                    </select>
								</div>
							</div>
                    		<div class="col-md-7" id="displayParentCategory">
                    		<label>Parent Category</label>
								<div class="form-group">
			                        <select data-placeholder="Select Category" style="width: 100% !important;" class="form-control select-category-search select2-close parent_category_id-error error-placement" onselect="onSelect(this)" name="parent_category_id" id="parent_category_id">
			                        </select>
								</div>
							</div>
						</div>
						
						 <div class="row">
                    		<div class="col-md-12">
	                            <div class="form-group" id="displayRejectReason"  style="display:none">
	                                <label>Reject Reason</label>
	                                 <textarea  class="form-control" name="reject_reason"  id="category_reject_reason" class="category_reject_reason"></textarea>
	                            </div>
                           	</div>
                    		
						</div>
										
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" id="category-setup-submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Category form modal -->
<!-- Mode form modal -->
<div id="modal_mode_form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Mode Setup</h5>
            </div>

            <form id="mode-setup-form">
            	<input type="hidden" name="mode" id="mode"/>
            	<input type="hidden" name="id" id="complaint_mode_id_pk"/>
            	<input type="hidden" name="" id="refresh"/>
            	<input type="hidden" name="" id="refresh_table"/>
                <div class="modal-body">
                        <div class="row">
                        	<!-- CR2 changed Recepeient_user role to user -->
                    		@hasanyrole(RoleHelper::getAdminRoles())
                    		<div class="col-md-6">
	                            <div class="form-group">
	                                <label>Name</label>
	                                <input type="text" placeholder="Mode Name" id="mode_name" class="form-control name-error error-placement" name="name">
	                            </div>
                           	</div>
                           	<div class="col-md-6">
	                            <div class="form-group">
	                                <label>Code</label>
	                                <input type="text" placeholder="Mode Code" id="mode_code" class="form-control code-error error-placement" name="code">
	                            </div>
                           	</div>
                        </div>
                        <div class="row">
                    		<div class="col-md-6">
                    		<label>Status</label>
		                        <div class="form-group">		                            
				                    <select data-placeholder="Select Status" style="width: 100% !important;" class="form-control select-modestatus-search select2-close status-error error-placement" onselect="onSelect(this)" name="status" id="mode_status">
				                    <option></option>
				                    </select>
								</div>
							</div>
							<div class="col-md-6">
							<label>Color</label>
								<div class="form-group">
									<input id="mode-color-picker" type="text" class="form-control" name="color"/>
								</div>
							</div>
<!-- 							<div class="card"> -->
<!-- 	                            <div class="card-body text-center d-flex justify-content-center align-items-center flex-column"> -->
<!-- 	                            <div id="color-picker-1" class="mx-auto"></div> -->
<!-- 	                            </div> -->
<!--                             </div> -->
							@endhasanyrole
						</div>
										
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" id="mode-setup-submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Mode form modal -->
<!-- Sub Category form modal -->
<div id="modal_sub_category_form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Sub Category Setup</h5>
            </div>

            <form id="sub-category-setup-form">
            	<input type="hidden" name="mode" id="mode"/>
            	<input type="hidden" name="id" id="sub_category_id_pk"/>
            	<input type="hidden" name="" id="refresh"/>
            	<input type="hidden" name="" id="refresh_table"/>
                <div class="modal-body">
                    <!-- CR2 changed Recepeient_user role to user -->
					@hasanyrole(RoleHelper::getAdminRoles())
					<div class="row">
                    		<div class="col-md-6">
		                     <div class="form-group">
	                                <label>Name</label>
	                                <input type="text" placeholder="Sub Category Name" id="sub_category_name" class="form-control name-error error-placement" name="name">
	                            </div>   
							</div>
                    		<div class="col-md-3">
                    		<label>Status</label>
		                        <div class="form-group">		                            
				                    <select data-placeholder="Select Status" style="width: 100% !important;" class="form-control select-sub-categorystatus-search select2-close status-error error-placement" onselect="onSelect(this)" name="status" id="sub_category_status">
				                    <option></option>
				                    </select>
								</div>
							</div>
							<div class="col-md-3">
							<label>Color</label>
								<div class="form-group">
									<input id="simple-color-picker1" type="text" class="form-control" name="color"/>
								</div>
							</div>
						</div>
						<div class="row">
						<div class="col-md-6">
							<label>Category Name</label>
							<div class="form-group">
								<select data-placeholder="Select Category" style="width: 100% !important;"
									class="form-control select-category-search select2-close category_id_fk-error error-placement"
									onselect="onSelect(this)" name="category_id_fk"
									id="category_id_fk">
								<option></option>	
								</select>
							</div>
						</div>
						
						<div class="col-md-6">
                    		 <label>Area</label>
		                        <div class="form-group">		                           
				                    <select data-placeholder="Select Area" style="width: 100% !important;" class="form-control select-area-search select2-close area_id_fk-error error-placement" onselect="onSelect(this)" name="area_id_fk" id="area_id_fk">
				                    <option></option>
				                    </select>
								</div>
							</div>

					</div>
					@endhasanyrole
					
					 <div class="row">
                    		<div class="col-md-12">
	                            <div class="form-group" id="displayRejectReasonSub" style="display:none">
	                                <label>Reject Reason</label>
	                                 <textarea class="form-control" name="reject_reason" id="sub_category_reject_reason" class="sub_category_reject_reason" ></textarea>
	                            </div>
                           	</div>
                    		
						</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" id="sub-category-setup-submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Sub Category form modal -->
<!-- Complaint Reject form modal -->
<div id="modal_complaint_action_form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Action Remarks</h5>
            </div>

            <form id="complaint-action-status-form">
            	<input type="hidden" name="mode" id="mode"/>
            	<input type="hidden" name="id" id="complaint_id_pk"/>
            	<input type="hidden" name="flow"/>
            	<input type="hidden" name="field"/>
            	<input type="hidden" name="status"/>
            	<input type="hidden" name="" id="refresh"/>
            	<input type="hidden" name="" id="refresh_table"/>
            	<input type="hidden" name="" id="refresh_type"/>
				<input type="hidden" name="table"/>
                <div class="modal-body">
			
					
					 <div class="row">
                    		<div class="col-md-12">
	                            <div class="form-group" id="displayComplaintRejectReason">
	                                <label>Remark</label>
	                                 <textarea class="form-control reason-error error-placement" name="reason" id="complaint_action_reason"></textarea>
	                            </div>
                           	</div>
                    		
						</div>
                
                	<div class="row displayCloseOptions">
                		<div class="col-md-8">
							<div class="form-group">
								<label for="fullname">Primary User <span class="required">*</span>  :</label> 
		                        <select data-placeholder="Select User" class="form-control select-complaint-user-search select2-close complaint_user_id_pk-error error-placement" onselect="onSelect(this)" name="complaint_user_id_pk" id="complaint_user_id_pk">
		                        </select>
							</div>
                       </div>
                		<div class="col-md-4">
							<div class="form-group">
								<label for="fullname">Primary Role <span class="required">*</span>  :</label> 
		                        <select data-placeholder="Select Role" class="form-control select-system-role-search select2-close system_role-error error-placement" onselect="onSelect(this)" name="system_role" id="system_role">
		                        </select>
							</div>
                       </div>		
					</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                    <button type="button" id="complaint-action-status-submit" class="btn btn-primary">Continue</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Complaint Reject form modal -->

<!-- Complaint Reporting form modal -->
<div id="modal_reporting_complaint_action_form" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Action Remarks</h5>
            </div>

            <form id="reporting-complaint-action-status-form">
            	<input type="hidden" name="mode" id="mode"/>
            	<input type="hidden" name="id" id="complaint_id_pk"/>
            	<input type="hidden" name="flow"/>
            	<input type="hidden" name="field"/>
            	<input type="hidden" name="status"/>
            	<input type="hidden" name="" id="refresh"/>
            	<input type="hidden" name="" id="refresh_table"/>
            	<input type="hidden" name="" id="refresh_type"/>
				<input type="hidden" name="table"/>
				<input type="hidden" name="is_reporting"/>
                <div class="modal-body">
			
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" id="displayComplaintRejectReason">
								<label>Remark</label>
								<textarea class="form-control reason-error error-placement" name="reason" id="complaint_action_reason"></textarea>
							</div>
						</div>
					</div>
                
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                    <button type="button" id="reporting-complaint-action-status-submit" class="btn btn-primary">Continue</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Complaint Reporting form modal -->

<!-- Complaint Reject form modal -->
<div id="modal_solution_amendment_form" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Amendment Setup</h5>
            </div>

            <form id="solution-amendment-form">
            	<input type="hidden" name="mode" id="mode"/>
            	<input type="hidden" name="id" id="complaint_solution_id_pk"/>
            	<input type="hidden" name="flow"/>
<!--             	<input type="hidden" name="field"/> -->
<!--             	<input type="hidden" name="status"/> -->
<!--             	<input type="hidden" name="" id="refresh"/> -->
<!--             	<input type="hidden" name="" id="refresh_table"/> -->
                <div class="modal-body">
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
							<label for="fullname">Amendment <span class="required">*</span>  :</label>
								<textarea id="amendment" class="form-control amendment-error error-placement" row='5'></textarea>
							</div>
						</div>
					</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="button" id="solution-amendment-submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Complaint Reject form modal -->