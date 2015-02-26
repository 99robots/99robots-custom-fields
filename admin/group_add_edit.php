<div class="wrap gabfire-plugin-settings">

	<?php require_once('header.php'); ?>

	<div class="metabox-holder has-right-sidebar">

		<?php require_once('sidebar.php'); ?>

		<div id="post-body">
			<div id="post-body-content">

				<form id="gabfire_custom_field_group_form" method="post">

					<div class="postbox">

						<h3 style="color:red"><span><?php _e('Required', self::$text_domain); ?></span></h3>

						<div class="inside">

							<table>
						<tbody>

							<!-- Name -->
							<tr>
								<th class="gabfire_custom_field_admin_table_th">
									<label><?php _e('Name',self::$text_domain); ?></label>
									<td class="gabfire_custom_field_admin_table_td">
										<input size="40" type="text" id="gcfg_custom_field_group_id" name="gcfg_custom_field_group_id" placeholder="e.g person" value="<?php echo ((isset($_GET['action']) && $_GET['action'] == "edit" && (isset($_GET['custom_field_group']) && $_GET['custom_field_group'] != '')) ? $_GET['custom_field_group'] : ''); ?>"  <?php echo ((isset($_GET['action']) && $_GET['action'] == "edit") ? 'readonly' : ''); ?> /><br/>
										<em><label><?php _e('Will be used as id so use all lowercase letters, and must be unique.',self::$text_domain); ?></label></em>
									</td>
								</th>
							</tr>

							<!-- Label -->
							<tr>
								<th class="gabfire_custom_field_admin_table_th">
									<label><?php _e('Label',self::$text_domain); ?></label>
									<td class="gabfire_custom_field_admin_table_td">
										<input size="40" type="text" id="gcfg_label" name="gcfg_label" placeholder="e.g Person" value="<?php echo ((isset($data['label']) && $data['label'] != '') ? esc_attr($data['label']) : ''); ?>"/><br/>
										<em><label><?php _e('This is the name the user will see.',self::$text_domain); ?></label></em>
									</td>
								</th>
							</tr>

							<!-- Build into certain post types -->
							<tr>
								<th class="gabfire_custom_field_admin_table_th">
									<label><?php _e('Build into Post Type', self::$text_domain); ?></label>
									<td class="gabfire_custom_field_admin_table_td">
										<?php
										foreach ($post_types as $post_type) {
										?>
											<input type="checkbox" class="gcfg_post_type" id="gcfg_post_type_<?php echo $post_type; ?>" name="gcfg_post_type_<?php echo $post_type; ?>" <?php echo (isset($data['post_type']) && in_array($post_type, $data['post_type']) ? 'checked="checked"' : '');?>/><label><?php printf(__('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s', self::$text_domain), $post_type); ?></label><br />
										<?php
										} ?>
									</td>
								</th>
							</tr>

						</tbody>
					</table>

						</div>

					</div>

					<div class="postbox">

						<h3><span><?php _e('Advanced Options', self::$text_domain); ?></span></h3>

						<div class="inside">

							<table>
						<tbody>

						<!-- Context -->
						<tr>
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Context',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<select id="gcfg_context" name="gcfg_context">
										<option value="normal" <?php echo ((isset($data['context']) && $data['context'] == 'normal') ? 'selected' : ''); ?>><?php _e('Normal',self::$text_domain); ?></option>
										<option value="advanced" <?php echo ((isset($data['context']) && $data['context'] == 'advanced') ? 'selected' : ''); ?>><?php _e('Advanced',self::$text_domain); ?></option>
										<option value="side" <?php echo ((isset($data['context']) && $data['context'] == 'side') ? 'selected' : ''); ?>><?php _e('Side',self::$text_domain); ?></option>
									</select>
								</td>
							</th>
						</tr>

						<!-- Priority -->
						<tr>
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Priority',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<select id="gcfg_priority" name="gcfg_priority">
										<option value="high" <?php echo ((isset($data['priority']) && $data['priority'] == 'high') ? 'selected' : ''); ?>><?php _e('High',self::$text_domain); ?></option>
										<option value="core" <?php echo ((isset($data['priority']) && $data['priority'] == 'core') ? 'selected' : ''); ?>><?php _e('Core',self::$text_domain); ?></option>
										<option value="default" <?php echo ((isset($data['priority']) && $data['priority'] == 'default') ? 'selected' : ''); ?>><?php _e('Default',self::$text_domain); ?></option>
										<option value="low" <?php echo ((isset($data['priority']) && $data['priority'] == 'low') ? 'selected' : ''); ?>><?php _e('Low',self::$text_domain); ?></option>
									</select>
								</td>
							</th>
						</tr>

						<!-- Capabilities -->
						<tr>
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Capabilities', self::$text_domain); ?></label>
								<td>
									<!-- Super Admin -->
									<input type="checkbox" id="gcfg_capabilities_super_admin" name="gcfg_capabilities_super_admin" <?php echo (isset($data['capabilities']['super_admin']) && $data['capabilities']['super_admin'] ? 'checked="checked"' : '');?>/><label><?php _e('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Super Admin', self::$text_domain); ?></label><br />

									<!-- Administrator -->
									<input type="checkbox" id="gcfg_capabilities_admin" name="gcfg_capabilities_admin" <?php echo (isset($data['capabilities']['admin']) && $data['capabilities']['admin'] ? 'checked="checked"' : '');?>/><label><?php _e('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Administrator', self::$text_domain); ?></label><br />

									<!-- Editor -->
									<input type="checkbox" id="gcfg_capabilities_editor" name="gcfg_capabilities_editor" <?php echo (isset($data['capabilities']['editor']) && $data['capabilities']['editor'] ? 'checked="checked"' : '');?>/><label><?php _e('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Editor', self::$text_domain); ?></label><br />

									<!-- Author -->
									<input type="checkbox" id="gcfg_capabilities_author" name="gcfg_capabilities_author" <?php echo (isset($data['capabilities']['author']) && $data['capabilities']['author'] ? 'checked="checked"' : '');?>/><label><?php _e('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Author', self::$text_domain); ?></label><br />

									<!-- Contributor -->
									<input type="checkbox" id="gcfg_capabilities_contributor" name="gcfg_capabilities_contributor" <?php echo (isset($data['capabilities']['contributor']) && $data['capabilities']['contributor'] ? 'checked="checked"' : '');?>/><label><?php _e('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contributor', self::$text_domain); ?></label><br />
								</td>
							</th>
						</tr>

						</tbody>
					</table>

						</div>

					</div>

					<?php submit_button(); ?>
				</form>

<?php require_once('footer.php'); ?>