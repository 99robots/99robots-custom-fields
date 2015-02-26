/*
 * Created by Kyle Benk
 * http://kylebenkapps.com
 *
 * Contains all functions used with DatePicker and Media Uploader
 */

jQuery(document).ready(function($) {

	/* datepicker fields init */

	if (typeof($(".gabfire_custom_field_datepicker")) != "undefined" && $(".gabfire_custom_field_datepicker") !== null) {
		$(".gabfire_custom_field_datepicker").datepicker({ dateFormat: "yy-mm-dd" });
	}

	if (typeof($(".gabfire_custom_field_datepicker_time")) != "undefined" && $(".gabfire_custom_field_datepicker_time") !== null) {
		$(".gabfire_custom_field_datepicker_time").datetimepicker({ dateFormat: "yy-mm-dd" });
	}

	/* media uploaders fields init */

	$('.gabfire_custom_field_media_uploader_post_type').click(function(e) {
	    e.preventDefault();
		var custom_uploader = wp.media({
	        title: $(this).attr('id').substring(36),
	        button: {
	            text: translation_array.button
	        },
	        multiple: false  // Set this to true to allow multiple files to be selected
	    })
	    .on('select', function () {
		    var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#gabfire_custom_field_media_image_' + $('h1').html()).attr('src', attachment.url);
			$('#gabfire_custom_field_' + $('h1').html()).val(attachment.id);
	    })
	    .open();
	});

	$('#gabfire_custom_field_media_uploader').click(function(e) {
	    e.preventDefault();
		var custom_uploader = wp.media({
	        title: translation_array.text,
	        button: {
	            text: translation_array.button
	        },
	        multiple: false  // Set this to true to allow multiple files to be selected
	    })
	    .on('select',  function() {
	    	var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('#gabfire_custom_field_media_image').attr('src', attachment.url);
			$('#gcf_default').val(attachment.id);
	    })
	    .open();
	});

	/* Change image based off input */

	$("#gcf_default").focusout(function(){
		$("#gabfire_avatar_media_image").attr("src", $("#gcf_default").val());
	});

	$("#gcf_type").change(function(){
		$(".gabfire_custom_field_admin_add_new_default_image").hide();
		$(".gabfire_custom_field_admin_add_new_default_label").text('Default Value');


		$(".gabfire_custom_field_admin_add_new_default").show();
		$(".gabfire_custom_fields_admin_add_new_values").hide();
		$(".gabfire_custom_field_admin_add_new_default_checkbox").hide();
		$(".gabfire_custom_fields_admin_add_new_image").hide();
		$(".gabfire_custom_fields_admin_add_new_image_type").hide();
		$(".gabfire_custom_fields_admin_add_new_datepicker_type").hide();
		$(".gabfire_custom_fields_admin_add_new_datepicker_format").hide();
		$(".gabfire_custom_field_admin_add_new_default_textarea").hide();

		if ($(this).val() == 'select' || $(this).val() == 'radio') {
			$(".gabfire_custom_fields_admin_add_new_values").show();

			if ($(this).val() == 'select') {
				$(".gabfire_custom_fields_admin_add_new_values_select").text('Select Box Values');
			} else {
				$(".gabfire_custom_fields_admin_add_new_values_select").text('Radio Button Values');
			}

		}

		if ($(this).val() == 'checkbox') {
			$(".gabfire_custom_field_admin_add_new_default_checkbox").show();
			$(".gabfire_custom_field_admin_add_new_default").hide();
		}

		if ($(this).val() == 'image') {
			$(".gabfire_custom_field_admin_add_new_default").show();
			$(".gabfire_custom_fields_admin_add_new_image_type").show();

			if ($("#gcf_image_type").val() == 'url') {
				$(".gabfire_custom_fields_admin_add_new_image").hide();
				$(".gabfire_custom_field_admin_add_new_default_label").text('Default Image URL');
				$(".gabfire_custom_field_admin_add_new_default_image").show();
			} else {
				$(".gabfire_custom_field_admin_add_new_default").hide();
			}
		}

		if ($(this).val() == 'datepicker') {
			$(".gabfire_custom_fields_admin_add_new_datepicker_type").show();
			$(".gabfire_custom_fields_admin_add_new_datepicker_format").show();

			if ($('#gcf_datepicker_type').val() == 'date') {
				$("#gcf_default").datepicker();
			}

			if ($('#gcf_datepicker_type').val() == 'date_time') {
				$("#gcf_default").datetimepicker();
			}
		} else {
			$("#gcf_default").datepicker("destroy");
		}

		if ($(this).val() == 'textarea') {
			$(".gabfire_custom_field_admin_add_new_default_textarea").show();
			$(".gabfire_custom_field_admin_add_new_default").hide();
		}
	});

	/* Hide all fields that are used for specific field types */

	$(".gabfire_custom_field_admin_add_new_default_image").hide();
	$(".gabfire_custom_field_admin_add_new_default_label").text('Default Value');


	$(".gabfire_custom_field_admin_add_new_default").show();
	$(".gabfire_custom_fields_admin_add_new_values").hide();
	$(".gabfire_custom_field_admin_add_new_default_checkbox").hide();
	$(".gabfire_custom_fields_admin_add_new_image").hide();
	$(".gabfire_custom_fields_admin_add_new_image_type").hide();
	$(".gabfire_custom_fields_admin_add_new_datepicker_type").hide();
	$(".gabfire_custom_fields_admin_add_new_datepicker_format").hide();
	$(".gabfire_custom_field_admin_add_new_default_textarea").hide();

	if ($("#gcf_type").val() == 'select' || $("#gcf_type").val() == 'radio') {
		$(".gabfire_custom_fields_admin_add_new_values").show();

		if ($("#gcf_type").val() == 'select') {
			$(".gabfire_custom_fields_admin_add_new_values_select").text('Select Box Values');
		} else {
			$(".gabfire_custom_fields_admin_add_new_values_select").text('Radio Button Values');
		}

	}

	if ($("#gcf_type").val() == 'checkbox') {
		$(".gabfire_custom_field_admin_add_new_default_checkbox").show();
		$(".gabfire_custom_field_admin_add_new_default").hide();
	}

	if ($("#gcf_type").val() == 'image') {
		$(".gabfire_custom_field_admin_add_new_default").show();
		$(".gabfire_custom_fields_admin_add_new_image_type").show();

		if ($("#gcf_image_type").val() == 'url') {
			$(".gabfire_custom_fields_admin_add_new_image").hide();
			$(".gabfire_custom_field_admin_add_new_default_label").text('Default Image URL');
			$(".gabfire_custom_field_admin_add_new_default_image").show();
		} else {
			$(".gabfire_custom_field_admin_add_new_default").hide();
		}
	}

	if ($("#gcf_type").val() == 'datepicker') {
		$(".gabfire_custom_fields_admin_add_new_datepicker_type").show();
		$(".gabfire_custom_fields_admin_add_new_datepicker_format").show();

		if ($('#gcf_datepicker_type').val() == 'date') {
			$("#gcf_default").datepicker();
		}

		if ($('#gcf_datepicker_type').val() == 'date_time') {
			$("#gcf_default").datetimepicker();
		}
	} else {
		$("#gcf_default").datepicker("destroy");
	}

	if ($("#gcf_type").val() == 'textarea') {
		$(".gabfire_custom_field_admin_add_new_default_textarea").show();
		$(".gabfire_custom_field_admin_add_new_default").hide();
	}

	/* Image */

	$("#gcf_image_type").change(function(){
		$(".gabfire_custom_field_admin_add_new_default_label").text('Default Value');
		if ($(this).val() == 'url') {
			$(".gabfire_custom_field_admin_add_new_default").show();
			$(".gabfire_custom_fields_admin_add_new_image").hide();
			$(".gabfire_custom_field_admin_add_new_default_label").text('Default Image URL');
			$(".gabfire_custom_field_admin_add_new_default_image").show();
		}

		if ($(this).val() == 'uploader') {
			$(".gabfire_custom_fields_admin_add_new_image").show();
			$(".gabfire_custom_field_admin_add_new_default").hide();
		}
	});

	if ($("#gcf_type").val() == 'image' && $("#gcf_image_type").val() == 'url') {
		$(".gabfire_custom_field_admin_add_new_default").show();
		$(".gabfire_custom_fields_admin_add_new_image").hide();
		$(".gabfire_custom_field_admin_add_new_default_label").text('Default Image URL');
	}

	if ($("#gcf_type").val() == 'image' && $("#gcf_image_type").val() == 'uploader') {
		$(".gabfire_custom_fields_admin_add_new_image").show();
		$(".gabfire_custom_field_admin_add_new_default").hide();
	}


	/* Date Picker */

	if ($("#gcf_type").val() == 'datepicker') {
		if ($('#gcf_datepicker_type').val() == 'date') {
			$("#gcf_default").datepicker();
		}

		if ($('#gcf_datepicker_type').val() == 'date_time') {
			$("#gcf_default").datetimepicker();
		}
	}

	$("#gcf_datepicker_type").change(function(){
		$("#gcf_default").datepicker("destroy");

		if ($(this).val() == 'date') {
			$("#gcf_default").datepicker();
		}

		if ($(this).val() == 'date_time') {
			$("#gcf_default").datetimepicker();
		}
	});
});