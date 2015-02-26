/*
 * Created by Kyle Benk
 * http://kylebenkapps.com
 */

jQuery(document).ready(function($) {

	/* Admin form make the name field required */

	$("#gabfire_custom_field_group_form").submit(function(e){
		$("#gcfg_custom_field_group_id").removeClass('gabfire_custom_fields_admin_required');
		$("#gcfg_label").removeClass('gabfire_custom_fields_admin_required');
		$(".gcfg_post_type").removeClass('gabfire_custom_fields_admin_required');

		/* Field group */

		if ($("#gcfg_custom_field_group_id").val() == '') {
			$("#gcfg_custom_field_group_id").addClass('gabfire_custom_fields_admin_required');
			e.preventDefault();
		}

		/* Field group label */

		if ($("#gcfg_label").val() == '') {
			$("#gcfg_label").addClass('gabfire_custom_fields_admin_required');
			e.preventDefault();
		}

		/* Build into post types */

		if (!$(".gcfg_post_type").is(':checked')) {
			$(".gcfg_post_type").addClass('gabfire_custom_fields_admin_required');
			e.preventDefault();
		}
	});

	$("#gabfire_custom_field_form").submit(function(e){

		$("#gcf_custom_field_id").removeClass('gabfire_custom_fields_admin_required');
		$("#gcf_label").removeClass('gabfire_custom_fields_admin_required');

		/* Field */

		if ($("#gcf_custom_field_id").val() == '') {
			$("#gcf_custom_field_id").addClass('gabfire_custom_fields_admin_required');
			e.preventDefault();
		}

		/* Field label */

		if ($("#gcf_label").val() == '') {
			$("#gcf_label").addClass('gabfire_custom_fields_admin_required');
			e.preventDefault();
		}

		/* Group */

		if (!$(".gcf_builtin_group").is(':checked')) {
			$(".gcf_builtin_group").addClass('gabfire_custom_fields_admin_required');
			e.preventDefault();
		}
	});

	$("." + gab_cf_data.prefix + "delete").click(function(){
		gabfire_custom_fields_delete($(this).attr('id').substring(29), $("#gabfire_custom_fields_delete_url_" + $(this).attr('id').substring(29)).text());
	});
});

function gabfire_custom_fields_delete(message, url) {

	var c = confirm("Are you sure you want to delete: " + message);

	if (c == true) {
		window.location = url;
	}
}