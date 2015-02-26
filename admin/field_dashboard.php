<div class="wrap gabfire-plugin-settings">

	<?php require_once('header.php'); ?>

	<div class="metabox-holder has-right-sidebar">

		<?php require_once('sidebar.php'); ?>

		<div id="post-body">
			<div id="post-body-content">

				<div class="wrap">
					<div id="icon-edit" class="icon32 icon32-posts-post"><br/></div>
					<h2><?php _e('Gabfire Custom Fields',self::$text_domain); ?><a class="add-new-h2" href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$field_add_new_page, 'gabfire_custom_field_add'); ?>"><?php _e('Add New', self::$text_domain); ?></a></h2>

				<br />

				<!-- Detect errors -->
				<?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate') { ?>
					<h3 style="color:red"><?php _e('Error: Cannot add duplicate custom field', self::$text_domain); ?></h3>
				<?php } ?>

				<?php if (isset($_GET['error']) && $_GET['error'] == 'no_group') { ?>
					<h3 style="color:red"><?php _e('Error: Cannot custom field if there are no groups', self::$text_domain); ?></h3>
				<?php } ?>

				<table class="wp-list-table widefat fixed posts">
					<thead>
						<tr>
							<th><?php _e('ID', self::$text_domain); ?></th>
							<th><?php _e('Label', self::$text_domain); ?></th>
							<th><?php _e('Type', self::$text_domain); ?></th>
							<th><?php _e('Description', self::$text_domain); ?></th>
							<th><?php _e('Builtin Groups', self::$text_domain); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e('ID', self::$text_domain); ?></th>
							<th><?php _e('Label', self::$text_domain); ?></th>
							<th><?php _e('Type', self::$text_domain); ?></th>
							<th><?php _e('Description', self::$text_domain); ?></th>
							<th><?php _e('Builtin Groups', self::$text_domain); ?></th>
						</tr>
					</tfoot>
					<tbody>
					<?php
						$settings = array();
						$gabfire_ahref_array = array();

						/* Check if option had been created if not then create it */

						if (is_multisite()) {
							if (get_site_option('gabfire_custom_field_settings') === false) {
								add_site_option('gabfire_custom_field_settings',array() ,'' ,'yes');
							}
							$settings = get_site_option('gabfire_custom_field_settings');
						}else {
							if (get_option('gabfire_custom_field_settings') === false) {
								add_option('gabfire_custom_field_settings',array() ,'' ,'yes');
							}
							$settings = get_option('gabfire_custom_field_settings');
						}

						if (is_array($settings)) {
							/* Loop through all custom post types */

							foreach ($settings as $custom_field => $item) {
								$gabfire_ahref_array[] = $custom_field;

								$groups_str = '';
								if (isset($item['group']) && is_array($item['group'])) {
									foreach ($item['group'] as $item2) {
										$groups_str .= $item2 . ',';
									}
								} else if (isset($item['group']) && !is_array($item['group'])) {
									$groups_str = $item['group'];
								}else {
									$groups_str = 'N/A';
								}

								$groups_str = str_getcsv(trim($groups_str, ","));

								?>
								<tr>

									<!-- Custom Field -->
									<td>
										<a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$field_add_new_page . '&custom_field=' . $custom_field . '&action=edit', 'gabfire_custom_field_add'); ?>"><strong><?php _e($custom_field,self::$text_domain); ?></strong></a>
									</td>

									<!-- Label -->
									<td>
										<label><?php echo (isset($item['label']) && $item['label'] != '' ? __($item['label'],self::$text_domain) : ''); ?></label>

										<div class="row-actions">

											<span class="edit">
												<a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$field_add_new_page . '&custom_field=' . $custom_field . '&action=edit', 'gabfire_custom_field_add'); ?>"><?php _e('Edit' ,self::$text_domain); ?></a> |
											</span>

											<span class="trash">

												<a href="#" id="<?php echo self::$prefix_dash . 'delete-' . $custom_field; ?>" class="<?php echo self::$prefix_dash; ?>delete"><?php _e('Delete', self::$text_domain); ?></a>
												<label id="gabfire_custom_fields_delete_url_<?php echo $custom_field; ?>" style="display:none;"><?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$field_add_new_page . '&custom_field=' . $custom_field . '&action=delete', 'gabfire_custom_field_delete'); ?></label>
											</span>
										</div>
									</td>

									<!-- Type -->
									<td> <?php echo (isset($item['type']) && $item['type'] != '' ? __($item['type'],self::$text_domain) : ''); ?> </td>

									<!-- Description -->
									<td> <?php echo (isset($item['description']) && $item['description'] != '' ? __($item['description'],self::$text_domain) : ''); ?> </td>

									<!-- Bulitin Groups -->
									<td>
										<?php foreach ($groups_str as $custom_field_group) {
											?>
											<a href="<?php echo wp_nonce_url($_SERVER['PHP_SELF'] . '?page=' . self::$group_add_new_page . '&custom_field_group=' . $custom_field_group . '&action=edit', 'gabfire_custom_field_group_add'); ?>"><?php _e($custom_field_group, self::$text_domain); ?></a><br/> <?php
										} ?>
									</td>
								</tr>
								<?php
							}
						}
					?>
					</tbody>
				</table>
				</div>
				<!-- <label style="color:red"><?php _e('**Please note that if plugin is deleted then all Gabfire Custom Fields will be deleted.  Also, if this plugin is deactivated, then all Gabfire Custom Fields will be deactivated as well.', self::$text_domain); ?></label> -->

<?php require_once('footer.php'); ?>