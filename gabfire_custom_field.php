<?php
/*
Plugin Name: Gabfire Custom Field Beta
plugin URI:
Description: Creates a User Interface to add custom fields to a post type.
version: 0.9
Author: Kyle Benk
Author URI: http://kylebenkapps.com
License: GPL2
*/

/* Plugin Name */

if (!defined('GABFIRE_CUSTOM_FIELDS_PLUGIN_NAME'))
    define('GABFIRE_CUSTOM_FIELDS_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

/* Plugin directory */

if (!defined('GABFIRE_CUSTOM_FIELDS_PLUGIN_DIR'))
    define('GABFIRE_CUSTOM_FIELDS_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . GABFIRE_CUSTOM_FIELDS_PLUGIN_NAME);

/* Plugin url */

if (!defined('GABFIRE_CUSTOM_FIELDS_PLUGIN_URL'))
    define('GABFIRE_CUSTOM_FIELDS_PLUGIN_URL', WP_PLUGIN_URL . '/' . GABFIRE_CUSTOM_FIELDS_PLUGIN_NAME);

/* Plugin verison */

if (!defined('GABFIRE_CUSTOM_FIELDS_VERSION_NUM'))
    define('GABFIRE_CUSTOM_FIELDS_VERSION_NUM', '0.9.0');

/**
 * Activatation / Deactivation
 */

register_activation_hook( __FILE__, array('Gabfire_Custom_Field', 'register_activation'));


/**
 * Hooks / Filter
 */

add_action('admin_menu', array('Gabfire_Custom_Field','gabfire_menu'));
add_action('admin_enqueue_scripts', array('Gabfire_Custom_Field', 'gabfire_include_admin_scripts'));
add_action('init', array('Gabfire_Custom_Field', 'gabfire_load_textdoamin'));

/* Hooks for adding and saving data to post type */
add_action('add_meta_boxes', array('Gabfire_Custom_Field', 'gabfire_add_custom_meta_box'));
add_action('save_post', array('Gabfire_Custom_Field', 'gabfire_save_custom_meta'));

add_shortcode('gabfire_custom_field', 'gabfire_custom_field_shortcode');

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", array('Gabfire_Custom_Field', 'gabfire_settings_link'));

/* GLOBAL */

/* Used to prefix the custom field id */

class Gabfire_Custom_Field {

	/**
	 * Varibles for JQuery and Datpickers
	 *
	 * @since 1.0.0
	 */

	/* JQuery UI CSS */

	static $gcf_jquery_ui_css = '/css/jquery-ui-1.10.3.min.css';

	private static $text_domain = 'gabfire-custom-fields';

	private static $group_dashboard_page = 'gabfire-custom-fields-group-dashboard';

	private static $group_add_new_page = 'gabfire-custom-fields-group-add-new';

	private static $field_dashboard_page = 'gabfire-custom-fields-field-dashboard';

	private static $field_add_new_page = 'gabfire-custom-fields-field-add-new';

	private static $usage_page = 'gabfire-custom-fields-usage';

	public static $prefix = 'gabfire_custom_fields_';

	public static $prefix_dash = 'gabfire-custom-fields-';

	private static $default = array();

	/**
	 * Performs tasks needed upon activation
	 *
	 * @since 1.0.0
	 */
	static function register_activation() {

		/* Check if multisite, if so then save as site option */

		if (is_multisite()) {
			add_site_option('gabfire_custom_fields_version', GABFIRE_CUSTOM_FIELDS_VERSION_NUM);
		} else {
			add_option('gabfire_custom_fields_version', GABFIRE_CUSTOM_FIELDS_VERSION_NUM);
		}
	}

	/**
	 * Hooks to 'plugin_action_links_' filter
	 *
	 * @since 1.0.0
	 */
	static function gabfire_settings_link($links) {
		$settings_link = '<a href="admin.php?page=' . self::$group_dashboard_page . '">Settings</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Load the text domain
	 *
	 * @since 1.0.0
	 */
	static function gabfire_load_textdoamin() {
		load_plugin_textdomain(self::$text_domain, false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Hook for the admin menu
	 *
	 * @since 1.0.0
	 */
	static function gabfire_menu() {

		// Group Dashboard

		add_menu_page(
			"Gabfire Custom Fields",
			"Gabfire Custom Fields",
			'manage_options',
			self::$group_dashboard_page,
			array('Gabfire_Custom_Field','gabfire_dashboard_list_group'));

		$gabfire_admin_page_list_group = add_submenu_page(
			self::$group_dashboard_page,
			"All Groups",
			"All Groups",
			'manage_options',
			self::$group_dashboard_page,
			array('Gabfire_Custom_Field','gabfire_dashboard_list_group'));
		add_action('load-' . $gabfire_admin_page_list_group, array('Gabfire_Custom_Field','gabfire_dashboard_help_list_group'));
		add_action("admin_print_scripts-$gabfire_admin_page_list_group", array('Gabfire_Custom_Field','inline_scripts_general_page'));

		// Group Add New Page

		$gabfire_admin_page_new_group = add_submenu_page(
			self::$group_dashboard_page,
			"Add New Group",
			"Add New Group",
			'manage_options',
			self::$group_add_new_page,
			array('Gabfire_Custom_Field','gabfire_dashboard_add_new_group'));
		add_action('load-' . $gabfire_admin_page_new_group, array('Gabfire_Custom_Field','gabfire_dashboard_help_new_group'));
		add_action("admin_print_scripts-$gabfire_admin_page_new_group", array('Gabfire_Custom_Field','inline_scripts_general_page'));

		// Field Dashboard Page

		$gabfire_admin_page_list_field = add_submenu_page(
			self::$group_dashboard_page,
			"All Fields",
			"All Fields",
			'manage_options',
			self::$field_dashboard_page,
			array('Gabfire_Custom_Field','gabfire_dashboard_list_field'));
		add_action('load-' . $gabfire_admin_page_list_field, array('Gabfire_Custom_Field','gabfire_dashboard_help_list_field'));
		add_action("admin_print_scripts-$gabfire_admin_page_list_field", array('Gabfire_Custom_Field','inline_scripts_general_page'));

		// Field Add New Page

		$gabfire_admin_page_new_field = add_submenu_page(
			self::$group_dashboard_page,
			"Add New Field",
			"Add New Field",
			'manage_options',
			self::$field_add_new_page,
			array('Gabfire_Custom_Field','gabfire_dashboard_add_new_field'));
		add_action('load-' . $gabfire_admin_page_new_field, array('Gabfire_Custom_Field','gabfire_dashboard_help_new_field'));
		add_action("admin_print_scripts-$gabfire_admin_page_new_field", array('Gabfire_Custom_Field','inline_scripts_field_add_new_page'));

		// Usage Page

		$gabfire_admin_usage_page = add_submenu_page(
			self::$group_dashboard_page,
			"Usage",
			"Usage",
			'manage_options',
			self::$usage_page,
			array('Gabfire_Custom_Field','gabfire_dashboard_usage'));
		add_action("admin_print_scripts-$gabfire_admin_usage_page", array('Gabfire_Custom_Field','inline_scripts_usage_page'));
	}

	/**
	 * Hook to the "admin_print_script-$page"
	 *
	 * @since 1.0.0
	 */
	static function inline_scripts_general_page() {

		wp_enqueue_script('jquery');

		/* Gabfire Custom Fields CSS */

		wp_enqueue_style('gabfire_custom_field_css', plugins_url('css/gabfire_custom_field.css', __FILE__));
	}

	/**
	 * Hook to the "admin_print_script-$page"
	 *
	 * @since 1.0.0
	 */
	static function inline_scripts_field_add_new_page() {

		/* Gabfire Custom Fields CSS */

		wp_enqueue_style('gabfire_custom_field_css', plugins_url('css/gabfire_custom_field.css', __FILE__));

		wp_enqueue_media();

		/* JQuery */

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');

		/* JQuery Datepicker CSS */

		wp_enqueue_style('gabfire_custom_field_jquery_ui_datepicker_css', GABFIRE_CUSTOM_FIELDS_PLUGIN_URL . self::$gcf_jquery_ui_css);

		/* JQuery UI Time Picker CSS */

		wp_enqueue_style('gabfire_custom_field_jquery_ui_timepicker_addon_css', plugins_url('css/jquery-ui-timepicker-addon.css', __FILE__));

		/* JQuery UI Time Picker JS */

		wp_enqueue_script('gabfire_custom_field_jquery_ui_timepicker_addon_js', plugins_url('js/jquery-ui-timepicker-addon.js', __FILE__), array('jquery','jquery-ui-datepicker'));

		/* Gabfire Custom Fields JS */

		wp_register_script('gabfire_custom_field_js_addon', plugins_url( '/js/gabfire_custom_field_addon.js', __FILE__ ), array('jquery','jquery-ui-datepicker', 'gabfire_custom_field_jquery_ui_timepicker_addon_js'));
		wp_enqueue_script('gabfire_custom_field_js_addon');

		$translation_array = array(
			'text'		=> __('Gabfire Custom Field', self::$text_domain),
			'button'	=> __('Use as Custom Field Image', self::$text_domain)
		);

		wp_localize_script('gabfire_custom_field_js_addon', 'translation_array', $translation_array);
	}

	/**
	 * Hook to the "admin_print_script-$page"
	 *
	 * @since 1.0.0
	 */
	static function inline_scripts_usage_page() {
		/* Gabfire Custom Fields CSS */

		wp_enqueue_style('gabfire_custom_field_css', plugins_url('css/gabfire_custom_field.css', __FILE__));
	}

	/**
	 * Displays on the All Groups Dashboard
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_list_group() {

		require('admin/group_dashboard.php');

		/* Gabfire Custom Fields JS */

		wp_register_script('gabfire_custom_field_js_admin', plugins_url( '/js/gabfire_custom_field_admin.js', __FILE__ ), array('jquery'));
		wp_enqueue_script('gabfire_custom_field_js_admin');

		wp_localize_script('gabfire_custom_field_js_admin', 'gab_cf_data', array('prefix' => self::$prefix_dash));
	}

	/**
	 * Displays on the Add New Group Dashboard
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_add_new_group() {

		/* Get all post types that are public */

		$post_types = get_post_types(array('public' => true));

		/* Check if option had been created if not then create it */

		$settings = self::get_group_settings();

		/* Deleting a Custom Field Group */

		if (isset($_GET['action']) && $_GET['action'] == "delete" && check_admin_referer('gabfire_custom_field_group_delete')) {

			/* Delete from group settings */

			if (isset($_GET['custom_field_group']))
				unset($settings[$_GET['custom_field_group']]);

			/* Delete from field settings */

			if (is_multisite()) {
				if (get_site_option('gabfire_custom_field_settings') === false) {
					add_site_option('gabfire_custom_field_settings',array() ,'' ,'yes');
				}
				$field_settings = get_site_option('gabfire_custom_field_settings');
			}else {
				if (get_option('gabfire_custom_field_settings') === false) {
					add_option('gabfire_custom_field_settings',array() ,'' ,'yes');
				}
				$field_settings = get_option('gabfire_custom_field_settings');
			}

			foreach ($field_settings as $field_key => $field_setting) {
				for ($i = 0; $i < count($field_setting['group']); $i++) {
					if ($field_setting['group'][$i] == $_GET['custom_field_group']) {

						// Delete Group

						unset($field_setting['group'][$i]);
					}
				}

				$field_settings[$field_key] = $field_setting;
			}

			/* Save settings */

			if (is_multisite()) {
				update_site_option('gabfire_custom_field_group_settings', $settings);
				update_site_option('gabfire_custom_field_settings', $field_settings);
			}else {
				update_option('gabfire_custom_field_group_settings', $settings);
				update_option('gabfire_custom_field_settings', $field_settings);
			}

			?>
			<script type="text/javascript">
				window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$group_dashboard_page; ?>";
			</script>
			<?php
		}

		/* Add new -Default- */

		$data = array(
			'id'            => '',
			'label'         => '',
			'context'		=> 'advanced',
			'priority'		=> 'default',
			'capabilities'	=> array(
				'super_admin'		=> true,
				'admin'				=> true,
				'editor'			=> true,
				'author'			=> true,
				'contributor'		=> true
			),
			'post_type'   	=> array('post')
		);

		/* Edit Existing */

		if (isset($_GET['action']) && $_GET['action'] == "edit"/*  && check_admin_referer('gabfire_custom_field_group_add') */) {
			if (isset($_GET['custom_field_group'])) {
				$data = $settings[$_GET['custom_field_group']];
			}
		}

		/* Save option */
		if (isset($_POST['submit'])/*  && check_admin_referer('gabfire_custom_field_group_add') */) {

			/* Determine Post Types */

			$post_types_array = array();

			foreach ($post_types as $post_type) {
				if (isset($_POST['gcfg_post_type_' . $post_type]) && $_POST['gcfg_post_type_' . $post_type])
					$post_types_array[] = $post_type;
			}

			/*
$group_id = stripcslashes(strtolower(sanitize_text_field($_POST['gcfg_custom_field_group_id'])));

			$group_id = preg_replace('~[^\p{L}\p{N}]++~u', '', $group_id);
*/

			$group_id = stripcslashes(strtolower(str_replace(' ','',sanitize_text_field($_POST['gcfg_custom_field_group_id']))));


			/* Check if requirements are met */

			if (isset($_POST['gcfg_custom_field_group_id']) && $_POST['gcfg_custom_field_group_id'] != '' && count($post_types_array) > 0) {
				if (isset($_GET['action']) && $_GET['action'] == "edit"){

					/* Determine settings */

					$capabilities = array(
						'super_admin'		=> isset($_POST['gcfg_capabilities_super_admin']) ? true : false,
						'admin'				=> isset($_POST['gcfg_capabilities_admin']) ? true : false,
						'editor'			=> isset($_POST['gcfg_capabilities_editor']) ? true : false,
						'author'			=> isset($_POST['gcfg_capabilities_author']) ? true : false,
						'contributor'		=> isset($_POST['gcfg_capabilities_contributor']) ? true : false
					);

					if (!empty($_POST['gcfg_custom_field_group_id'])) {
						$settings[$group_id] = array(
							'id'            => $group_id,
							'label'         => !empty($_POST['gcfg_label']) ? sanitize_text_field($_POST['gcfg_label']) : '',
							'context'		=> !empty($_POST['gcfg_context']) ? $_POST['gcfg_context'] : '',
							'priority'		=> !empty($_POST['gcfg_priority']) ? $_POST['gcfg_priority'] : '',
							'capabilities'	=> $capabilities,
							'post_type'	 	=> $post_types_array
						);
					}

					/* Save to Database */

					if (is_multisite()) {
						update_site_option('gabfire_custom_field_group_settings', $settings);
					}else {
						update_option('gabfire_custom_field_group_settings', $settings);
					}

					/* Go back to the main dashboard */
					?>
					<script type="text/javascript">
						window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$group_dashboard_page; ?>";
					</script>
					<?php
				}else {
					if (!array_key_exists($group_id, $settings)){

						/* Determine settings */

						$capabilities = array(
							'super_admin'		=> isset($_POST['gcfg_capabilities_super_admin']) && $_POST['gcfg_capabilities_super_admin'] ? true : false,
							'admin'				=> isset($_POST['gcfg_capabilities_admin']) && $_POST['gcfg_capabilities_admin'] ? true : false,
							'editor'			=> isset($_POST['gcfg_capabilities_editor']) && $_POST['gcfg_capabilities_editor'] ? true : false,
							'author'			=> isset($_POST['gcfg_capabilities_author']) && $_POST['gcfg_capabilities_author'] ? true : false,
							'contributor'		=> isset($_POST['gcfg_capabilities_contributor']) && $_POST['gcfg_capabilities_contributor'] ? true : false
						);

						if (!empty($_POST['gcfg_custom_field_group_id'])) {
							$settings[$group_id] = array(
								'id'            => $group_id,
								'label'         => !empty($_POST['gcfg_label']) ? sanitize_text_field($_POST['gcfg_label']) : '',
								'context'		=> !empty($_POST['gcfg_context']) ? $_POST['gcfg_context'] : '',
								'priority'		=> !empty($_POST['gcfg_priority']) ? $_POST['gcfg_priority'] : '',
								'capabilities'	=> $capabilities,
								'post_type'	 	=> $post_types_array
							);
						}

						/* Save to Database */

						if (is_multisite()) {
							update_site_option('gabfire_custom_field_group_settings', $settings);
						}else {
							update_option('gabfire_custom_field_group_settings', $settings);
						}

						/* Go back to the main dashboard */
						?>
						<script type="text/javascript">
							window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$group_dashboard_page; ?>";
						</script>
						<?php
					}else{
						/* Go back to the main dashboard */
						?>
						<script type="text/javascript">
							window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$group_dashboard_page; ?>&error=duplicate";
						</script>
						<?php
					}
				}
			}else {
				//error_log(__('Gabfire Custom Field:: Missing the required field group name field and/or the build into post type check boxes.',self::$text_domain));
			}
		}

		/* Gabfire Custom Fields JS */

		wp_register_script('gabfire_custom_field_js_admin', plugins_url( '/js/gabfire_custom_field_admin.js', __FILE__ ), array('jquery'));
		wp_enqueue_script('gabfire_custom_field_js_admin');

		require('admin/group_add_edit.php');
	}

	/**
	 * Displays on the All Fields Dashboard
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_list_field() {

		require('admin/field_dashboard.php');

		/* Gabfire Custom Fields JS */

		wp_register_script('gabfire_custom_field_js_admin', plugins_url( '/js/gabfire_custom_field_admin.js', __FILE__ ), array('jquery'));
		wp_enqueue_script('gabfire_custom_field_js_admin');

		wp_localize_script('gabfire_custom_field_js_admin', 'gab_cf_data', array('prefix' => self::$prefix_dash));
	}

	/**
	 * Displays on the Add New page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_add_new_field() {

		/* Get all post types that are public */

		$post_types = get_post_types(array('public' => true));

		/* Check if option had been created if not then create it */

		$settings = self::get_field_settings();

		/* Deleting a Custom Post Type */

		if (isset($_GET['action']) && $_GET['action'] == "delete" && check_admin_referer('gabfire_custom_field_delete')) {

			// Delete field

			if (isset($_GET['custom_field']))
				unset($settings[$_GET['custom_field']]);

			if (is_multisite()) {
				update_site_option('gabfire_custom_field_settings', $settings);
			}else {
				update_option('gabfire_custom_field_settings', $settings);
			}

			?>
			<script type="text/javascript">
				window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$field_dashboard_page; ?>";
			</script>
			<?php
		}

		/* Add new -Default- */

		$data = array(
			'id'            => '',
			'label'         => '',
			'type'   		=> '',
			'description'   => '',
			'group'   		=> '',
			'args'			=> array(
				'image_type'	=> 'url'
			)
		);

		/* Edit Existing */

		if (isset($_GET['action']) && $_GET['action'] == "edit"/*  && check_admin_referer('gabfire_custom_field_add') */) {
			if (isset($_GET['custom_field'])) {
				$data = $settings[$_GET['custom_field']];
			}

			/* Parse values array to csv */
			if (isset($data['args'])) {

				$values_str = '';
				if (isset($data['args']['values']) && is_array($data['args']['values'])) {
					foreach ($data['args']['values'] as $value) {
						$values_str .= $value . ',';
					}
				} else if (isset($data['args']['values']) && !is_array($data['args']['values'])) {
					$values_str = $data['args']['values'];
				}else {
					$values_str = '';
				}

				$data['args']['values'] = $values_str;
			}
		}

		/* Save option */

		if (isset($_POST['submit'])/*  && check_admin_referer('gabfire_custom_field_add') */) {

			/* Determine Post Types */

			$groups = array();

			if (is_multisite()) {
				$group_settings = get_site_option('gabfire_custom_field_group_settings');
			}else {
				$group_settings = get_option('gabfire_custom_field_group_settings');
			}

			/* Check if there are any groups */

			if ($group_settings === false) {

				/* Go back to the main dashboard */

				?>
				<script type="text/javascript">
					window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$field_dashboard_page; ?>&error=no_group";
				</script>
				<?php
			}

			if (isset($group_settings) && is_array($group_settings)) {
				foreach ($group_settings as $group_setting) {
					if (isset($_POST['gcf_group_' . $group_setting['id']]) && $_POST['gcf_group_' . $group_setting['id']])
						$groups[] = $group_setting['id'];
				}
			}

			/*
$field_id = stripcslashes(strtolower(sanitize_text_field($_POST['gcf_custom_field_id'])));

			$field_id = preg_replace('~[^\p{L}\p{N}]++~u', '', $field_id);
*/

			$field_id = stripcslashes(strtolower(str_replace(' ','',sanitize_text_field($_POST['gcf_custom_field_id']))));

			/* Check if requirements are met */

			if (isset($_POST['gcf_custom_field_id']) && $_POST['gcf_custom_field_id'] != '') {
				if (isset($_GET['action']) && $_GET['action'] == "edit"){

					/* Determine settings */

					if (!empty($_POST['gcf_custom_field_id'])) {
						$settings[$field_id] = array(
							'id'            => $field_id,
							'label'         => !empty($_POST['gcf_label']) ? sanitize_text_field($_POST['gcf_label']) : '',
							'type'          => !empty($_POST['gcf_type']) ? $_POST['gcf_type'] : '',
							'description'  	=> !empty($_POST['gcf_description']) ? sanitize_text_field($_POST['gcf_description']) : '',
							'group'	 		=> $groups
						);
					}

					/* Create the args array to store field data */

					$args = array();

					/* Text */
					if (isset($_POST['gcf_type']) && ($_POST['gcf_type'] == 'text' || $_POST['gcf_type'] == 'textarea')) {
						$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
					}

					/* Text Area */
					if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'textarea') {
						$args['default'] = !empty($_POST['gcf_default_textarea']) ? sanitize_text_field($_POST['gcf_default_textarea']) : '';
					}

					/* Checkbox */
					if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'checkbox') {
						$args['default'] = isset($_POST['gcf_default_checkbox']) && $_POST['gcf_default_checkbox'] ? true : false;
					}

					/* Select Box and Radio */
					if (isset($_POST['gcf_type']) && ($_POST['gcf_type'] == 'select' || $_POST['gcf_type'] == 'radio')) {
						$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
						$args['values'] = !empty($_POST['gcf_values']) ? str_getcsv(sanitize_text_field($_POST['gcf_values'])) : '';
					}

					/* Image */
					if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'image') {
						$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
						$args['image_type'] = !empty($_POST['gcf_image_type']) ? sanitize_text_field($_POST['gcf_image_type']) : '';
					}

					/* Date Picker */
					if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'datepicker') {
						$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
						$args['datepicker_type'] = !empty($_POST['gcf_datepicker_type']) ? sanitize_text_field($_POST['gcf_datepicker_type']) : '';
						$args['datepicker_format'] = !empty($_POST['gcf_datepicker_format']) ? sanitize_text_field($_POST['gcf_datepicker_format']) : '';
					}

					$settings[$field_id]['args'] = $args;

					/* Save to Database */

					if (is_multisite()) {
						update_site_option('gabfire_custom_field_settings', $settings);
					}else {
						update_option('gabfire_custom_field_settings', $settings);
					}

					/* Go back to the main dashboard */
					?>
					<script type="text/javascript">
						window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$field_dashboard_page; ?>";
					</script>
					<?php
				}else {
					if (!array_key_exists($field_id, $settings)){

						/* Determine settings */

						if (!empty($_POST['gcf_custom_field_id'])) {
							$settings[$field_id] = array(
								'id'            => $field_id,
								'label'         => !empty($_POST['gcf_label']) ? sanitize_text_field($_POST['gcf_label']) : '',
								'type'          => !empty($_POST['gcf_type']) ? $_POST['gcf_type'] : '',
								'description'  	=> !empty($_POST['gcf_description']) ? sanitize_text_field($_POST['gcf_description']) : '',
								'group'	 		=> $groups
							);
						}

						/* Create the args array to store field data */

						$args = array();

						/* Text */
						if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'text') {
							$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
						}

						/* Text Area */
						if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'textarea') {
							$args['default'] = !empty($_POST['gcf_default_textarea']) ? sanitize_text_field($_POST['gcf_default_textarea']) : '';
						}

						/* Checkbox */
						if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'checkbox') {
							$args['default'] = isset($_POST['gcf_default_checkbox']) && $_POST['gcf_default_checkbox'] ? true : false;
						}

						/* Select Box and Radio */
						if (isset($_POST['gcf_type']) && ($_POST['gcf_type'] == 'select' || $_POST['gcf_type'] == 'radio')) {
							$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
							$args['values'] = !empty($_POST['gcf_values']) ? str_getcsv(sanitize_text_field($_POST['gcf_values'])) : '';
						}

						/* Image */
						if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'image') {
							$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
							$args['image_type'] = !empty($_POST['gcf_image_type']) ? sanitize_text_field($_POST['gcf_image_type']) : '';
						}

						/* Date Picker */
						if (isset($_POST['gcf_type']) && $_POST['gcf_type'] == 'datepicker') {
							$args['default'] = !empty($_POST['gcf_default']) ? sanitize_text_field($_POST['gcf_default']) : '';
							$args['datepicker_type'] = !empty($_POST['gcf_datepicker_type']) ? sanitize_text_field($_POST['gcf_datepicker_type']) : '';
							$args['datepicker_format'] = !empty($_POST['gcf_datepicker_format']) ? sanitize_text_field($_POST['gcf_datepicker_format']) : '';
						}

						$settings[$field_id]['args'] = $args;

						/* Save to Database */

						if (is_multisite()) {
							update_site_option('gabfire_custom_field_settings', $settings);
						}else {
							update_option('gabfire_custom_field_settings', $settings);
						}

						/* Go back to the main dashboard */
						?>
						<script type="text/javascript">
							window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$field_dashboard_page; ?>";
						</script>
						<?php
					}else{
						/* Go back to the main dashboard */
						?>
						<script type="text/javascript">
							window.location = "<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo self::$field_dashboard_page; ?>&error=duplicate";
						</script>
						<?php
					}
				}
			}else {
				//error_log(__('Gabfire Custom Field:: Missing the required field name field and/or the build into post type check boxes.',self::$text_domain));
			}
		}


		/* Gabfire Custom Fields JS */

		wp_register_script('gabfire_custom_field_js_admin', plugins_url( '/js/gabfire_custom_field_admin.js', __FILE__ ), array('jquery'));
		wp_enqueue_script('gabfire_custom_field_js_admin');

		require('admin/field_add_edit.php');
	}

	/**
	 * Hook for displaying content on the usage page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_usage() {
		require('admin/usage.php');
	}

	/**
	 * Hook for adding the meta boxes to the post type
	 *
	 * @since 1.0.0
	 */
	static function gabfire_add_custom_meta_box($post_type) {

		global $post;
		$group_settings = array();
		$field_settings = array();

		$group_settings = self::get_group_settings();
		$field_settings = self::get_field_settings();

		if (isset($group_settings) && is_array($group_settings)) {
			foreach ($group_settings as $group_setting) {

				/* Get all fields for this group */

				$fields_array = array();
				foreach ($field_settings as $field_setting) {
					if (in_array($group_setting['id'], $field_setting['group'])) {
						$fields_array[] = $field_setting['id'];
					}
				}

				/* Check capabilites */

				$check_cap = false;

				if (isset($group_setting['capabilities']['author']) && $group_setting['capabilities']['author'] && array_key_exists('author', get_userdata(get_current_user_id())->wp_capabilities))
					$check_cap = true;
				if (isset($group_setting['capabilities']['editor']) && $group_setting['capabilities']['editor'] && array_key_exists('editor', get_userdata(get_current_user_id())->wp_capabilities))
					$check_cap = true;
				if (isset($group_setting['capabilities']['contributor']) && $group_setting['capabilities']['contributor'] && array_key_exists('contributor', get_userdata(get_current_user_id())->wp_capabilities))
					$check_cap = true;
				if (isset($group_setting['capabilities']['admin']) && $group_setting['capabilities']['admin'] && array_key_exists('administrator', get_userdata(get_current_user_id())->wp_capabilities))
					$check_cap = true;
				if (isset($group_setting['capabilities']['super_admin']) && $group_setting['capabilities']['super_admin'] && is_super_admin(get_current_user_id()))
					$check_cap = true;

				/* Only add meta box if the post type matches the given ones from the field type */

				if (in_array($post_type, $group_setting['post_type']) && $check_cap) {
					add_meta_box(
				        self::$prefix . $group_setting['id'],
				        sprintf(__('%s', self::$text_domain), $group_setting['label']),
				        array('Gabfire_Custom_Field', 'gabfire_show_custom_meta_box'),
						$post_type,
						$group_setting['context'],
						$group_setting['priority'],
						array(
							'group_id'	=> $group_setting['id'],
							'fields'	=> $fields_array
						)
				    );
				}
			}
		}
	}

	/**
	 * Callback function for outputting the html
	 *
	 * @since 1.0.0
	 */
	static function gabfire_show_custom_meta_box($post, $settings) {

		$field_settings = array();

		$group_id = $settings['args']['group_id'] . '_';
		$fields_array = $settings['args']['fields'];

		/* Retrieve Field Option, if option had been created if not then create it */

		$field_settings = self::get_field_settings();

		/* Remove all fields that will not be used for this meta box */

		foreach ($field_settings as $key => $item) {
			if (!in_array($key, $fields_array)) {
				unset($field_settings[$key]);
			}
		}

		$media_uploader_array = array();

		// Begin the field table and loop
		echo '<table class="form-table">';

		foreach ($field_settings as $setting) {

			/* Set meta field to default if there is no current value */

			if ($setting['type'] == 'checkbox') {

				$meta = get_post_meta($post->ID, $setting['id']);

				if (is_array($meta) && count($meta) > 0) {
					$meta = $meta[0];
				} else {
					$meta = $setting['args']['default'];
				}

			} else {

				/* get value of this field if it exists for this post */

				$meta = get_post_meta($post->ID, $setting['id'], true);

				if ($meta === false) {
					$meta = $setting['args']['default'];
				} else if ($meta == '') {
					$meta = $setting['args']['default'];
				}

			}


			echo '<tr>
				<th><label for="' . $setting['id'] .'">' .$setting['label'].'</label></th>
			<td>';

			switch($setting['type']) {

				/* Text */

				case 'text':
					echo '<input type="text" name="' . $setting['id'] . '" id="' . $setting['id'] . '" value="' . esc_attr($meta) . '"/><br /><span class="description">' . $setting['description'] . '</span>';
				break;

				/* Text Area */

				case 'textarea':
				    echo '<textarea name="' . $setting['id'] . '" id="' . $setting['id'] . '" cols="60" rows="4">' . esc_attr($meta) . '</textarea><br /><span class="description">' . $setting['description'] . '</span>';
				break;

				/* Checkbox */

				case 'checkbox':

					if ($meta) {
						echo '<input type="checkbox" name="' . $setting['id'] . '" id="' . $setting['id'] . '" checked="checked"/><label for="' . $setting['id'] . '">' . $setting['description'] . '</label>';
					}else {
						echo '<input type="checkbox" name="' . $setting['id'] . '" id="' . $setting['id'] . '"/><label for="' . $setting['id'] . '">' . $setting['description'] . '</label>';
					}

				break;

				/* Select Box */

				case 'select' :
					$code = '<select name="' . $setting['id'] . '" id="' . $setting['id'] . '">';
					foreach ($setting['args']['values'] as $value) {
						if ($value == $meta) {
							$code .= '<option value="' . $value . '" selected>' . $value . '</option>';
						}else {
							$code .= '<option value="' . $value . '">' . $value . '</option>';
						}
					}
					$code .= '</select>';

					$code .= '<br /><span class="description">' . $setting['description'] . '</span>';
					echo $code;
				break;

				/* Radio */

				case 'radio' :
					$code = '';
					foreach ($setting['args']['values'] as $value) {
						if ($value == $meta) {
							$code .= '<input type="radio" id="' . $setting['id'] . '" name="' . $setting['id'] . '" value="' . $value . '" checked/><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $value . '</label><br />';
						}else {
							$code .= '<input type="radio" id="' . $setting['id'] . '" name="' . $setting['id'] . '" value="' . $value . '"/><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $value . '</label><br />';
						}
					}

					$code .= '<br /><span class="description">' . $setting['description'] . '</span>';
					echo $code;
				break;

				/* Date Picker */

				case 'datepicker':
					if ($setting['args']['datepicker_type'] == 'date') {
						echo '<input type="text" class="gabfire_custom_field_datepicker" name="' . $setting['id'] . '" id="' . $setting['id'] . '" value="' . $meta . '"/><br /><span class="description">' . $setting['description'] . '</span>';
					}

					if ($setting['args']['datepicker_type'] == 'date_time') {
						echo '<input type="text" class="gabfire_custom_field_datepicker_time" name="' . $setting['id'] . '" id="' . $setting['id'] . '" value="' . $meta . '"/><br /><span class="description">' . $setting['description'] . '</span>';
					}
				break;

				/* Image */

				case 'image':

					/* URL */

					if ($setting['args']['image_type'] == 'url') {
						echo '<input type="url" id="' . $setting['id'] . '" name="' . $setting['id'] . '" value="' . esc_url($meta)  . '"><br /><span class="description">' . $setting['description'] . '</span>';
					}

					/* Media Uploader */

					else if ($setting['args']['image_type'] == 'uploader') {
						wp_enqueue_media();

						$image_src = wp_get_attachment_image_src($meta, array(100,100));

						if ($image_src === false || !is_array($image_src)) {
							$image_src = '';
						} else {
							$image_src = $image_src[0];
						}

						echo '<input class="gabfire_custom_field_media_uploader_post_type" id="gabfire_custom_field_media_uploader_' . $setting['id'] . '" type="button" value="' . __('Upload or Select Image', self::$text_domain) . '" style="cursor:pointer"><br/><br />';


						echo '<img id="gabfire_custom_field_media_image_' . $setting['id'] . '" src="' . $image_src . '" height="100" width="100" />';

						echo '<input type="text" id="' . $setting['id'] . '" name="' . $setting['id'] . '" value="' . $meta  . '" style="display:none;"><br /><span class="description">' . $setting['description'] . '</span>';
					}

				break;
			}
			echo '</td></tr>';
		}

		echo '</table>';

		/* JQuery */

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');

		/* JQuery UI Time Picker JS */

		wp_register_script('gabfire_custom_field_jquery_ui_timepicker_addon_js', plugins_url('js/jquery-ui-timepicker-addon.js', __FILE__), array('jquery', 'jquery-ui-datepicker'));
		wp_enqueue_script('gabfire_custom_field_jquery_ui_timepicker_addon_js');

		/* Gabfire Custom Fields JS */

		wp_register_script('gabfire_custom_field_js_addon', plugins_url( '/js/gabfire_custom_field_addon.js', __FILE__ ), array('jquery', 'jquery-ui-datepicker', 'gabfire_custom_field_jquery_ui_timepicker_addon_js'));
		wp_enqueue_script('gabfire_custom_field_js_addon');

		/* JQuery Datepicker CSS */

		wp_register_style('gabfire_custom_field_jquery_ui_datepicker_css', GABFIRE_CUSTOM_FIELDS_PLUGIN_URL . self::$gcf_jquery_ui_css);
		wp_enqueue_style('gabfire_custom_field_jquery_ui_datepicker_css');

		/* JQuery UI Time Picker CSS */

		wp_register_style('gabfire_custom_field_jquery_ui_timepicker_addon_css', plugins_url('css/jquery-ui-timepicker-addon.css', __FILE__));
		wp_enqueue_style('gabfire_custom_field_jquery_ui_timepicker_addon_css');

		$translation_array = array(
			'text'		=> __('Gabfire Custom Field', self::$text_domain),
			'button'	=> __('Use as Custom Field Image', self::$text_domain)
		);

		wp_localize_script('gabfire_custom_field_js_addon', 'translation_array', $translation_array);
	}

	/**
	 * Hook for saving the post type custom fields
	 *
	 * @since 1.0.0
	 */
	static function gabfire_save_custom_meta($post_id) {
		global $post;

		$group_settings = array();
		$field_settings = array();

		$group_settings = self::get_group_settings();
		$field_settings = self::get_field_settings();

		/* Detect which group has this post */

		if (isset($group_settings) && is_array($group_settings)) {
			foreach ($group_settings as $group_setting) {
				if (in_array(get_post_type($post), $group_setting['post_type'])) {

					/* Get all fields for this group */

					foreach ($field_settings as $field_setting) {
						if (in_array($group_setting['id'], $field_setting['group'])) {

							$old = get_post_meta($post_id, $field_setting['id'], true);
							$new = isset($_POST[$field_setting['id']]) ? sanitize_text_field($_POST[$field_setting['id']]) : false;

							if (isset($new) && $new != $old) {
								update_post_meta($post_id, $field_setting['id'], $new);
							} elseif ($new == '' && $old) {
								delete_post_meta($post_id, $field_setting['id'], $old);
							}

						}
					}
				}
			}
		}
	}

	/**
	 * Displays the help tab in the all groups page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_list_group() {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
		    'id'      	=> 'gabfire-custom-field-help-list-overview-group', // This should be unique for the screen.
		    'title'   	=> 'Overview',
		    'content'	=> '',
		    'callback'	=> array('Gabfire_Custom_Field', 'gabfire_dashboard_help_list_overview_group')
		));
	}

	/**
	 * Displays the help tab in the add new group page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_new_group() {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
		    'id'      	=> 'gabfire-custom-field-help-new-parameters-group', // This should be unique for the screen.
		    'title'   	=> 'Parameters',
		    'content'	=> '',
		    'callback'	=> array('Gabfire_Custom_Field', 'gabfire_dashboard_help_new_parameters_group')
		));
	}

	/**
	 * Displays the overview tab in the help tab in the all groups page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_list_overview_group() {
		?>
		<span><?php _e('This is an overview.',self::$text_domain); ?></span>
		<?php
	}

	/**
	 * Displays the parameter tab in the help tab in the add new group page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_new_parameters_group() {
		?>
		<span><?php _e('This is a parameter overview.',self::$text_domain); ?></span>
		<?php
	}

	/**
	 * Displays the help tab in the all fields page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_list_field() {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
		    'id'      	=> 'gabfire-custom-field-help-list-overview-field', // This should be unique for the screen.
		    'title'   	=> 'Overview',
		    'content'	=> '',
		    'callback'	=> array('Gabfire_Custom_Field', 'gabfire_dashboard_help_list_overview_field')
		));
	}

	/**
	 * Displays the help tab in the add new field page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_new_field() {
		$screen = get_current_screen();
		$screen->add_help_tab( array(
		    'id'      	=> 'gabfire-custom-field-help-new-parameters-field', // This should be unique for the screen.
		    'title'   	=> 'Parameters',
		    'content'	=> '',
		    'callback'	=> array('Gabfire_Custom_Field', 'gabfire_dashboard_help_new_parameters_field')
		));
	}

	/**
	 * Displays the overview tab in the help tab in the all fields page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_list_overview_field() {
		?>
		<span><?php _e('This is an overview.',self::$text_domain); ?></span>
		<?php
	}

	/**
	 * Displays the parameter tab in the help tab in the add new field page
	 *
	 * @since 1.0.0
	 */
	static function gabfire_dashboard_help_new_parameters_field() {
		?>
		<span><?php _e('This is a parameter overview.',self::$text_domain); ?></span>
		<?php
	}

	/**
	 * Register scripts for admin page
	 *
	 * @since 1.0.0
	 *
	 * @param 	N/A
	 * @return 	N/A
	 */
	static function gabfire_include_admin_scripts($hook) {

		/* Add scripts if on post type of page */

		if (isset($_GET['post'])) {

			/* JQuery */

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-datepicker');

			/* Gabfire Custom Fields CSS */

			wp_register_style('gabfire_custom_field_css', plugins_url('css/gabfire_custom_field.css', __FILE__));
			wp_enqueue_style('gabfire_custom_field_css');

			/* JQuery UI Time Picker JS */

			wp_register_script('gabfire_custom_field_jquery_ui_timepicker_addon_js', plugins_url('js/jquery-ui-timepicker-addon.js', __FILE__), array('jquery', 'jquery-ui-datepicker'));
			wp_enqueue_script('gabfire_custom_field_jquery_ui_timepicker_addon_js');
		}
	}

	/**
	 * Retrieve all fields options
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function get_group_settings() {

		if (function_exists('is_multisite') && is_multisite()) {
			$group_settings = get_site_option('gabfire_custom_field_group_settings');
		} else {
			$group_settings = get_option('gabfire_custom_field_group_settings');
		}

		if ($group_settings === false) {
			$group_settings = self::$default;
		}

		return $group_settings;
	}

	/**
	 * Retrieve all fields options
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	static function get_field_settings() {

		if (function_exists('is_multisite') && is_multisite()) {
			$field_settings = get_site_option('gabfire_custom_field_settings');
		} else {
			$field_settings = get_option('gabfire_custom_field_settings');
		}

		if ($field_settings === false) {
			$field_settings = self::$default;
		}

		return $field_settings;
	}
}

/**
 * Shortcode function
 *
 * @since 1.0.0
 *
 * @param	array	atts (int field, int group, string image_size, string date_format)
 * @return 	string	html code
 */
function gabfire_custom_field_shortcode($atts) {
	global $post;

	extract(shortcode_atts(array(
		'field' 		=> null,
		'group'			=> null,
		'image_size'	=> 100,
		'date_format'	=> '1'
	), $atts));

	/* Check for image_size input */

	if (!isset($image_size)) $image_size = 100;
	if (!is_numeric($image_size)) $image_size = (int) $image_size;

	/* Check for date_format input */

	if (!isset($date_format)) $date_format = '1';

	/* Return if no id is set */

	if (!isset($field) && !isset($group))
		return null;

	$field_data = get_post_meta($post->ID, $field, true);

	/* Retrieve Field Option, if option had been created if not then create it */

	$field_settings = Gabfire_Custom_Field::get_field_settings();

	/* Return if there is no post meta data */

	$code = '';

	if ($field_data !== false && is_singular()) {

		/* Image */

		if ($field_settings[$field]['type'] == 'image') {

			$image_src = wp_get_attachment_image_src($meta, array($image_size, $image_size));

			if ($image_src === false || !is_array($image_src)) {
				$image_src = '';
			} else {
				$image_src = $image_src[0];
			}

			if ($field_settings[$field]['args']['image_type'] == 'url') {
				$code .= '<img src="' . $field_data . '" height="' . $image_size . '" width="' . $image_size . '" />';
			}else {
				$code .= '<img src="' . $image_src . '" height="' . $image_size .'" width="' . $image_size . '" />';
			}
		}

		/* Datepicker */

		else if ($field_settings[$field]['type'] == 'datepicker') {

			/* March 10, 2001 */

			if ($date_format == '1')
				$date_format = date('F j, Y', strtotime($field_data));

			/* 2001-03-10 17:16:18 */

			if ($date_format == '2')
				$date_format = date('Y-m-d H:i:s', strtotime($field_data));

			/* 03/10/01 */

			if ($date_format == '3')
				$date_format = date('m/d/y', strtotime($field_data));

			/* Sat Mar 10 */

			if ($date_format == '4')
				$date_format = date('D M j', strtotime($field_data));

			/* March 10, 2001 5:16 */

			if ($date_format == '5')
				$date_format = date('F j, Y h:i', strtotime($field_data));

			/* 10/03/01 */

			if ($date_format == '6')
				$date_format = date('d/m/y', strtotime($field_data));

			$code .= '<span>' . $date_format .'</span>';
		}

		/* Checkbox */

		else if ($field_settings[$field]['type'] == 'checkbox') {
			if ($field_data) {
				$code .= '<span>yes</span>';
			} else {
				$code .= '<span>no</span>';
			}
		}

		/* Everything else */

		else {
			$code .= '<span>' . $field_data .'</span>';
		}
	} else {
		return null;
	}

	return $code;
}

/**
 * php template tags function to display post data
 *
 * @since 	1.0.0
 *
 * @param 	$field		int
 * @param 	$group		int
 * @param 	$post_id	int
 * @param 	$args		array (string image_size, string date_format)
 * @return 	$code		string (html code)
 */
function gabfire_custom_field_generate_code($field, $group, $post_id, $args = array('image_size' => '100', 'date_format' => '1')) {

	/* Check for image_size input */

	if (!isset($args['image_size'])) $args['image_size'] = 100;
	if (!is_numeric($args['image_size'])) $args['image_size'] = (int) $args['image_size'];

	/* Check for date_format input */

	if (!isset($args['date_format'])) $args['date_format'] = '1';

	$image_size = $args['image_size'];
	$date_format = $args['date_format'];

	/* Return if no id is set */
	if (!isset($field) || !isset($post_id) || !isset($group))
		return null;

	$field_data = get_post_meta($post->ID, $field, true);

	/* Retrieve Field Option, if option had been created if not then create it */

	$field_settings = Gabfire_Custom_Field::get_field_settings();

	/* Return if there is no post meta data */

	$code = '';

	if ($field_data !== false && is_singular()) {
		/* Image */

		if ($field_settings[$field]['type'] == 'image') {

			$image_src = wp_get_attachment_image_src($meta, array($image_size, $image_size));

			if ($image_src === false || !is_array($image_src)) {
				$image_src = '';
			} else {
				$image_src = $image_src[0];
			}

			if ($field_settings[$field]['args']['image_type'] == 'url') {
				$code .= '<img src="' . $field_data . '" height="' . $image_size . '" width="' . $image_size . '" />';
			}else {
				$code .= '<img src="' . $image_src . '" height="' . $image_size .'" width="' . $image_size . '" />';
			}
		}

		/* Datepicker */

		else if ($field_settings[$field]['type'] == 'datepicker') {

			/* March 10, 2001 */

			if ($date_format == '1')
				$date_format = date('F j, Y', strtotime($field_data));

			/* 2001-03-10 17:16:18 */

			if ($date_format == '2')
				$date_format = date('Y-m-d H:i:s', strtotime($field_data));

			/* 03/10/01 */

			if ($date_format == '3')
				$date_format = date('m/d/y', strtotime($field_data));

			/* Sat Mar 10 */

			if ($date_format == '4')
				$date_format = date('D M j', strtotime($field_data));

			/* March 10, 2001 5:16 */

			if ($date_format == '5')
				$date_format = date('F j, Y h:i', strtotime($field_data));

			/* 10/03/01 */

			if ($date_format == '6')
				$date_format = date('d/m/y', strtotime($field_data));

			$code .= '<span>' . $date_format .'</span>';
		}

		/* Checkbox */

		else if ($field_settings[$field]['type'] == 'checkbox') {
			if ($field_data) {
				$code .= '<span>yes</span>';
			} else {
				$code .= '<span>no</span>';
			}
		}

		/* Everything else */

		else {
			$code .= '<span>' . $field_data .'</span>';
		}
	} else {
		return null;
	}

	return $code;
}