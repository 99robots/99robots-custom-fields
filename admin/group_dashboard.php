<div class="wrap gabfire-plugin-settings">

	<?php require_once('header.php'); ?>

	<div class="metabox-holder has-right-sidebar">

		<?php require_once('sidebar.php'); ?>

		<div id="post-body">
			<div id="post-body-content">

				<div class="wrap">
					<div id="icon-edit" class="icon32 icon32-posts-post"><br/></div>
					<h2><?php _e('Gabfire Custom Field Groups',self::$text_domain); ?><a class="add-new-h2" href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$group_add_new_page, 'gabfire_custom_field_group_add'); ?>"><?php _e('Add New', self::$text_domain); ?></a></h2>

				<br />

				<!-- Detect errors -->
				<?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate') { ?>
					<h3 style="color:red"><?php _e('Error: Cannot add duplicate custom field', self::$text_domain); ?></h3>
				<?php } ?>

				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th><?php _e('ID', self::$text_domain); ?></th>
							<th><?php _e('Fields', self::$text_domain); ?></th>
							<th><?php _e('Label', self::$text_domain); ?></th>
							<th><?php _e('Context', self::$text_domain); ?></th>
							<th><?php _e('Priority', self::$text_domain); ?></th>
							<th><?php _e('Builtin Post Types', self::$text_domain); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e('ID', self::$text_domain); ?></th>
							<th><?php _e('Fields', self::$text_domain); ?></th>
							<th><?php _e('Label', self::$text_domain); ?></th>
							<th><?php _e('Context', self::$text_domain); ?></th>
							<th><?php _e('Priority', self::$text_domain); ?></th>
							<th><?php _e('Builtin Post Types', self::$text_domain); ?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php
						$settings = array();
						$gabfire_ahref_array = array();

						/* Check if option had been created if not then create it */
						if (is_multisite()) {
							if (get_site_option('gabfire_custom_field_group_settings') === false) {
								add_site_option('gabfire_custom_field_group_settings',array() ,'' ,'yes');
							}
							$settings = get_site_option('gabfire_custom_field_group_settings');
						}else {
							if (get_option('gabfire_custom_field_group_settings') === false) {
								add_option('gabfire_custom_field_group_settings',array() ,'' ,'yes');
							}
							$settings = get_option('gabfire_custom_field_group_settings');
						}

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



						if (is_array($settings)) {

							/* Loop through all custom post types */

							foreach ($settings as $custom_field_group => $item) {
								$gabfire_ahref_array[] = $custom_field_group;

								/* Get all bultin post types */

								$post_type_str = '';
								if (isset($item['post_type']) && is_array($item['post_type'])) {
									foreach ($item['post_type'] as $item2) {
										$post_type_str .= $item2 . ',';
									}
								} else if (isset($item['post_type']) && !is_array($item['post_type'])) {
									$post_type_str = $item['post_type'];
								}else {
									$post_type_str = 'N/A';
								}

								/* Get all fields for this group */

								$fields_array = array();
								foreach ($field_settings as $field_setting) {
									if (in_array($custom_field_group, $field_setting['group'])) {
										$fields_array[] = $field_setting['id'];
									}
								}

								?>
								<tr>

									<!-- Custom Field Group -->
									<td>
										<a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$group_add_new_page . '&custom_field_group=' . $custom_field_group . '&action=edit', 'gabfire_custom_field_group_add'); ?>"><strong><?php _e($custom_field_group,self::$text_domain); ?></strong></a>
									</td>

									<!-- Fields -->
									<td>
									<?php
									foreach ($fields_array as $field) {
										?> <a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$field_add_new_page . '&custom_field=' . $field . '&action=edit', 'gabfire_custom_field_add'); ?>"><?php _e($field,self::$text_domain); ?> </a><br /> <?php
									}
									?>
									</td>

									<!-- Label -->
									<td>
										<label><?php echo (isset($item['label']) && $item['label'] != '' ? __($item['label'],self::$text_domain) : ''); ?></label>

										<div class="row-actions">

											<span class="edit">
												<a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$group_add_new_page . '&custom_field_group=' . $custom_field_group . '&action=edit', 'gabfire_custom_field_group_add'); ?>"><?php _e("Edit",self::$text_domain); ?></a> |
											</span>

											<span class="trash">

												<a href="#" id="<?php echo self::$prefix_dash . 'delete-' . $custom_field_group; ?>" class="<?php echo self::$prefix_dash; ?>delete"><?php _e('Delete', self::$text_domain); ?></a>
												<label id="gabfire_custom_fields_delete_url_<?php echo $custom_field_group; ?>" style="display:none;"><?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$group_add_new_page . '&custom_field_group=' . $custom_field_group . '&action=delete', 'gabfire_custom_field_group_delete'); ?></label>
											</span>

										</div>
									</td>

									<!-- Context -->
									<td> <?php echo (isset($item['context']) && $item['context'] != '' ? __($item['context'],self::$text_domain) : ''); ?> </td>

									<!-- Priority -->
									<td> <?php echo (isset($item['priority']) && $item['priority'] != '' ? __($item['priority'],self::$text_domain) : ''); ?> </td>

									<!-- Bulitin Posts -->
									<td> <?php echo __(trim($post_type_str, ","), self::$text_domain); ?> </td>
								</tr>
								<?php
							}
						}
					?>
					</tbody>
				</table>
				</div>

				<!-- <label style="color:red"><?php _e('**Please note that if plugin is deleted then all Gabfire Custom Field Groups will be deleted.  Also, if this plugin is deactivated, then all Gabfire Custom Field Groups will be deactivated as well.', self::$text_domain); ?></label> -->

<?php require_once('footer.php'); ?>