<div class="wrap gabfire-plugin-settings">

	<?php require_once('header.php'); ?>

	<div class="metabox-holder has-right-sidebar">

		<?php require_once('sidebar.php'); ?>

		<div id="post-body">
			<div id="post-body-content">

				<form id="gabfire_custom_field_form" method="post">

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
										<input size="40" type="text" id="gcf_custom_field_id" name="gcf_custom_field_id" placeholder="e.g person" value="<?php echo ((isset($_GET['action']) && $_GET['action'] == "edit" && (isset($_GET['custom_field']) && $_GET['custom_field'] != '')) ? $_GET['custom_field'] : ''); ?>"  <?php echo ((isset($_GET['action']) && $_GET['action'] == "edit") ? 'readonly' : ''); ?> required/><br/>
										<em><label><?php _e('Will be used as id so use all lowercase letters, and must be unique.',self::$text_domain); ?></label></em>
									</td>
								</th>
							</tr>

							<!-- Label -->
							<tr>
								<th class="gabfire_custom_field_admin_table_th">
									<label><?php _e('Label',self::$text_domain); ?></label>
									<td class="gabfire_custom_field_admin_table_td">
										<input size="40" type="text" id="gcf_label" name="gcf_label" placeholder="e.g Person" value="<?php echo ((isset($data['label']) && $data['label'] != '') ? esc_attr($data['label']) : ''); ?>"/><br/>
										<em><label><?php _e('This is the name the user will see.',self::$text_domain); ?></label></em>
									</td>
								</th>
							</tr>

							<!-- Select Box and Radio Values -->

							<tr class="gabfire_custom_fields_admin_add_new_values">
								<th class="gabfire_custom_field_admin_table_th">
									<label class="gabfire_custom_fields_admin_add_new_values_select"><?php _e('Select Box Values',self::$text_domain); ?></label>
									<td class="gabfire_custom_field_admin_table_td">
										<input size="40" type="text" id="gcf_values" name="gcf_values" placeholder="e.g beginner,intermiedate,advanced" value="<?php echo ((isset($data['args']['values']) && $data['args']['values'] != '') ? trim(esc_attr($data['args']['values']), ",") : ''); ?>"/>
										<br/><em><?php _e('Use a comma separated list.',self::$text_domain); ?></em>
									</td>
								</th>
							</tr>

							<!-- Build into certain field groups -->

							<tr>
								<th class="gabfire_custom_field_admin_table_th">
									<label><?php _e('Build into Field Group', self::$text_domain); ?></label>
									<td class="gabfire_custom_field_admin_table_td">
										<?php if (is_multisite()) {
											$group_settings = get_site_option('gabfire_custom_field_group_settings');
										}else {
											$group_settings = get_option('gabfire_custom_field_group_settings');
										}

										if (isset($group_settings) && is_array($group_settings)) {
											foreach ($group_settings as $group_setting) { ?>
											<input type="checkbox" class="gcf_builtin_group" id="gcf_group_<?php echo $group_setting['id']; ?>" name="gcf_group_<?php echo $group_setting['id']; ?>" <?php echo (isset($data['group']) && is_array($data['group']) && in_array($group_setting['id'], $data['group']) ? 'checked="checked"' : '');?>/><label><?php printf(__('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%s', self::$text_domain), $group_setting['id']); ?></label><br />

										<?php }
										}?>
									</td>
								</th>
							</tr>

						</tbody>
					</table>
						</div>
					</div>

					<div class="postbox">

						<h3><span><?php _e('General', self::$text_domain); ?></span></h3>

						<div class="inside">

							<table>
						<tbody>

						<!-- Type -->
						<tr>
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Type',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<select id="gcf_type" name="gcf_type">
										<option value="text" <?php echo ((isset($data['type']) && $data['type'] == 'text') ? 'selected' : ''); ?>><?php _e('Text',self::$text_domain); ?></option>
										<option value="textarea" <?php echo ((isset($data['type']) && $data['type'] == 'textarea') ? 'selected' : ''); ?>><?php _e('Text Area',self::$text_domain); ?></option>
										<option value="checkbox" <?php echo ((isset($data['type']) && $data['type'] == 'checkbox') ? 'selected' : ''); ?>><?php _e('Checkbox',self::$text_domain); ?></option>
										<option value="radio" <?php echo ((isset($data['type']) && $data['type'] == 'radio') ? 'selected' : ''); ?>><?php _e('Radio',self::$text_domain); ?></option>
										<option value="select" <?php echo ((isset($data['type']) && $data['type'] == 'select') ? 'selected' : ''); ?>><?php _e('Select',self::$text_domain); ?></option>
										<option value="image" <?php echo ((isset($data['type']) && $data['type'] == 'image') ? 'selected' : ''); ?>><?php _e('Image',self::$text_domain); ?></option>
										<option value="datepicker" <?php echo ((isset($data['type']) && $data['type'] == 'datepicker') ? 'selected' : ''); ?>><?php _e('Date Picker',self::$text_domain); ?></option>

									</select>
								</td>
							</th>
						</tr>

						<!-- Date Picker Type -->

						<tr class="gabfire_custom_fields_admin_add_new_datepicker_type">
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Date Picker Type',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<select id="gcf_datepicker_type" name="gcf_datepicker_type">
										<option value="date" <?php echo ((isset($data['args']['datepicker_type']) && $data['args']['datepicker_type'] == 'date') ? 'selected' : ''); ?>><?php _e('Date',self::$text_domain); ?></option>
										<option value="date_time" <?php echo ((isset($data['args']['datepicker_type']) && $data['args']['datepicker_type'] == 'date_time') ? 'selected' : ''); ?>><?php _e('Date and Time',self::$text_domain); ?></option>
									</select>
								</td>
							</th>
						</tr>

						<!-- Image Type -->

						<tr class="gabfire_custom_fields_admin_add_new_image_type">
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Image Type',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<select id="gcf_image_type" name="gcf_image_type">
										<option value="url" <?php echo ((isset($data['args']['image_type']) && $data['args']['image_type'] == 'url') ? 'selected' : ''); ?>><?php _e('URL',self::$text_domain); ?></option>
										<option value="uploader" <?php echo ((isset($data['args']['image_type']) && $data['args']['image_type'] == 'uploader') ? 'selected' : ''); ?>><?php _e('Media Uploader',self::$text_domain); ?></option>
									</select>
								</td>
							</th>
						</tr>

						<!-- Default -->

						<tr class="gabfire_custom_field_admin_add_new_default">
							<th class="gabfire_custom_field_admin_table_th">
								<label class="gabfire_custom_field_admin_add_new_default_label"><?php _e('Default Value',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<input size="40" type="text" class="gcf_default" id="gcf_default" name="gcf_default" placeholder="e.g default" value="<?php echo ((isset($data['args']['default']) && $data['args']['default'] != '') ? esc_attr($data['args']['default']) : ''); ?>"/><br class="gabfire_custom_field_admin_add_new_default_image"/><em class="gabfire_custom_field_admin_add_new_default_image"><?php _e('i.e. http://example.com/image.png',self::$text_domain); ?></em>
								</td>
							</th>
						</tr>

						<!-- Default (textarea) -->

						<tr class="gabfire_custom_field_admin_add_new_default_textarea">
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Default Textarea Value',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">

									<textarea cols="50" rows="10" class="gcf_default_textarea" id="gcf_default_textarea" name="gcf_default_textarea" placeholder="e.g default"><?php echo ((isset($data['args']['default']) && $data['args']['default'] != '') ? esc_attr($data['args']['default']) : ''); ?></textarea>
								</td>
							</th>
						</tr>

						<!-- Default (checkbox) -->

						<tr class="gabfire_custom_field_admin_add_new_default_checkbox">
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Default Value',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<input type="checkbox" id="gcf_default_checkbox" name="gcf_default_checkbox" <?php echo ((isset($data['args']['default']) && $data['args']['default'] != '') ? 'checked="checked"' : ''); ?>/>
								</td>
							</th>
						</tr>

						<!-- Default (Image From Media Uploader) -->

						<tr class="gabfire_custom_fields_admin_add_new_image">
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Image',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<input class="gabfire_custom_field_media_uploader" id="gabfire_custom_field_media_uploader" type="button" value="<?php _e('Upload or Select Image',self::$text_domain); ?>" style="cursor:pointer"><br/><br />
									<?php /*
									if (isset($data['args']['default']) && $data['args']['default'] != '') { ?>
										<img id="gabfire_custom_field_media_image" src="<?php echo $data['args']['default']; ?>" height="100" width="100" />
									<?php } else
									*/ if (isset($data['args']['default']) && $data['args']['default'] != '') {

										$image_src = wp_get_attachment_image_src($data['args']['default'], array(100,100));

										if ($image_src === false || !is_array($image_src)) {
											$image_src = '';
										} else {
											$image_src = $image_src[0];
										}

									?>
										<img id="gabfire_custom_field_media_image" src="<?php echo $image_src; ?>" height="100" width="100" />
									<?php } else { ?>
										<img id="gabfire_custom_field_media_image" src="" height="100" width="100" />
									<?php } ?>

									<!-- <input type="text" class="gcf_default" id="gcf_default_media_image" name="gcf_default_media_image" value="<?php echo ((isset($data['args']['default']) && $data['args']['default'] != '') ? esc_attr($data['args']['default']) : ''); ?>" style="display:none"> -->
								</td>
							</th>
						</tr>

						<!-- Required -->
						<!--
		<tr>
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Required',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<input type="checkbox" id="gcf_required" name="gcf_required" <?php echo (isset($data['args']['public']) && $data['args']['public'] ? 'checked="checked"' : ''); ?> />
								</td>
							</th>
						</tr>
		-->

						<!-- Date Format -->

						<!--
		<tr class="gabfire_custom_fields_admin_add_new_datepicker_format">
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Date Format',self::$text_domain); ?></label>
								<td class="gabfire_custom_field_admin_table_td">
									<select id="gcf_datepicker_format" name="gcf_datepicker_format">
										<option value="date" <?php echo ((isset($data['args']['datepicker_format']) && $data['args']['datepicker_format'] == 'date') ? 'selected' : ''); ?>><?php _e('Date',self::$text_domain); ?></option>
										<option value="date_time" <?php echo ((isset($data['args']['datepicker_format']) && $data['args']['datepicker_format'] == 'date_time') ? 'selected' : ''); ?>><?php _e('Date and Time',self::$text_domain); ?></option>
									</select>
								</td>
							</th>
						</tr>
		-->

						<!-- Description -->
						<tr>
							<th class="gabfire_custom_field_admin_table_th">
								<label><?php _e('Description',self::$text_domain); ?></label>
								<td size="40" class="gabfire_custom_field_admin_table_td">
									<input type="text" id="gcf_description" name="gcf_description" placeholder="e.g This is a description" value="<?php echo ((isset($data['description']) && $data['description'] != '') ? esc_attr($data['description']) : ''); ?>"/>
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