window.serverErrorHandler = function(res) {
	/**
	 * Use a list to identify select2 input boxes
	 */
	var ele_array = ['.nic-error', '.title-error', '.branch_department_id_fk-error', '.branch_department_user-error', '.owner_role-error', '.complaint_mode_id_fk-error', '.category_id_fk-error', '.priority_level-error',
		'.sub_category_id_fk-error', '.escalated_to-error','.status-error','.area_id_fk-error','.report_date-from-error','report_date-to-error', '.code-error', '.year-error', '.manager_id_fk-error', 'zone_id_fk-error', 'type-error',
		'.complaint_user_id_pk-error', '.system_role-error', 'category_level-error', 'parent_category_id-error'];
	if(res && res.errors){
		if (res.errors.nic) {
			errorPlacement(ele_array, res.errors.nic, '.nic-error');
		}
		if (res.errors.branch_department_id_fk) {
			errorPlacement(ele_array, res.errors.branch_department_id_fk, '.branch_department_id_fk-error');
		}
		if (res.errors.branch_department_user) {
			errorPlacement(ele_array, res.errors.branch_department_user, '.branch_department_user-error');
		}
		if (res.errors.owner_role) {
			errorPlacement(ele_array, res.errors.owner_role, '.owner_role-error');
		}
		if (res.errors.category_id_fk) {
			errorPlacement(ele_array, res.errors.category_id_fk, '.category_id_fk-error');
		}
		if (res.errors.sub_category_id_fk) {
			errorPlacement(ele_array, res.errors.sub_category_id_fk, '.sub_category_id_fk-error');
		}
		if (res.errors.complaint_mode_id_fk) {
			errorPlacement(ele_array, res.errors.complaint_mode_id_fk, '.complaint_mode_id_fk-error');
		}
		if (res.errors.account_no) {
			errorPlacement(ele_array, res.errors.account_no, '.account_no-error');
		}
		if (res.errors.open_date) {
			errorPlacement(ele_array, res.errors.open_date, '.open_date-error');
		}
		if (res.errors.title) {
			errorPlacement(ele_array, res.errors.title, '.title-error');
		}
		if (res.errors.first_name) {
			errorPlacement(ele_array, res.errors.first_name, '.first_name-error');
		}
		if (res.errors.last_name) {
			errorPlacement(ele_array, res.errors.last_name, '.last_name-error');
		}
		if (res.errors.contact_no) {
			errorPlacement(ele_array, res.errors.contact_no, '.contact_no-error');
		}
		if (res.errors.complaint) {
			errorPlacement(ele_array, res.errors.complaint, '.complaint-error');
		}
		if (res.errors.attachment) {
			errorPlacement(ele_array, res.errors.attachment, '.attachment-error');
		}
		if (res.errors.action_taken) {
			errorPlacement(ele_array, res.errors.action_taken, '.action_taken-error');
		}
		if (res.errors.escalated_to_fk) {
			errorPlacement(ele_array, res.errors.escalated_to_fk, '.escalated_to_fk-error');
		}
		if (res.errors.remarks) {
			errorPlacement(ele_array, res.errors.remarks, '.remarks-error');
		}
		if (res.errors.name) {
			errorPlacement(ele_array, res.errors.name, '.name-error');
		}
		if (res.errors.status) {
			errorPlacement(ele_array, res.errors.status, '.status-error');
		}
		if (res.errors.color) {
			errorPlacement(ele_array, res.errors.color, '.color-error');
		}
		if (res.errors.area_id_fk) {
			errorPlacement(ele_array, res.errors.area_id_fk, '.area_id_fk-error');
		}
		if (res.errors.report_date_from_id) {
			errorPlacement(ele_array, res.errors.report_date_from_id, '.report_date_to-error');
		}
		if (res.errors.report_date_to_id) {
			errorPlacement(ele_array, res.errors.report_date_to_id, '.report_date_to-error');
		}
		if (res.errors.code) {
			errorPlacement(ele_array, res.errors.report_date_to_id, '.code-error');
		}
		if (res.errors.year) {
			errorPlacement(ele_array, res.errors.report_date_to_id, '.year-error');
		}
		if (res.errors.reason) {
			errorPlacement(ele_array, res.errors.reason, '.reason-error');
		}
		if(res.errors.priority_level) {
			errorPlacement(ele_array, res.errors.priority_level, '.priority_level-error');
		}
		if(res.errors.amendment) {
			errorPlacement(ele_array, res.errors.amendment, '.amendment-error');
		}
		if(res.errors.manager_id_fk) {
			errorPlacement(ele_array, res.errors.manager_id_fk, '.manager_id_fk-error');
		}
		if(res.errors.number) {
			errorPlacement(ele_array, res.errors.number, '.number-error');
		}
		if(res.errors.zone_id_fk) {
			errorPlacement(ele_array, res.errors.zone_id_fk, '.zone_id_fk-error');
		}
		if(res.errors.type) {
			errorPlacement(ele_array, res.errors.type, '.type-error');
		}
		if(res.errors.complaint_user_id_pk) {
			errorPlacement(ele_array, res.errors.complaint_user_id_pk, '.complaint_user_id_pk-error');
		}
		if(res.errors.system_role) {
			errorPlacement(ele_array, res.errors.system_role, '.system_role-error');
		}
		if(res.errors.category_level) {
			errorPlacement(ele_array, res.errors.category_level, '.category_level-error');
		}
		if(res.errors.parent_category_id) {
			errorPlacement(ele_array, res.errors.parent_category_id, '.parent_category_id-error');
		}
	}
}
