<?php

	//if uninstall not called from WordPress exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
		exit ();

	$group_option_name = 'gabfire_custom_field_group_settings';
	$field_option_name = 'gabfire_custom_field_settings';
	$version_option_name = 'gabfire_custom_fields_version';

	if (function_exists("is_multisite") && is_multisite()) {

		delete_site_option($group_option_name);
		delete_site_option($field_option_name);
		delete_site_option($version_option_name);

	} else {

		delete_option($group_option_name);
		delete_option($field_option_name);
		delete_option($version_option_name);

	}
?>