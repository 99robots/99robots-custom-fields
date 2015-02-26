<div class="wrap gabfire-plugin-settings">

	<?php require_once('header.php'); ?>

	<div class="metabox-holder has-right-sidebar">

		<?php require_once('sidebar.php'); ?>

		<div id="post-body">
			<div id="post-body-content">
				<h1><?php _e('Usages',self::$text_domain); ?></h1>

				<h3><?php _e('Shortcode',self::$text_domain); ?></h3>

				<div class="postbox">
					<h3><span><?php _e('Description',self::$text_domain); ?></span></h3>

					<div class="inside">
						<?php _e('Insert this ',self::$text_domain); ?>
						<em class="gabfire_custom_field_shorcode_code"><strong><?php _e('[gabfire_custom_field field="price" group="listing"]',self::$text_domain); ?></strong></em>
						<label><?php _e(' into any post that has a custom field.',self::$text_domain); ?></label>
					</div>

				</div>

				<div class="postbox">
					<h3><span><?php _e('Parameters',self::$text_domain); ?></span></h3>

					<div class="inside">
						<strong style="color:red"><?php _e('Required',self::$text_domain); ?></strong>
						<ol>
							<li>
								<label><?php _e('field = The id of the custom post type. You can find the ids ',self::$text_domain); ?></label>
								<a href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . self:: $field_dashboard_page; ?>">
									<label><?php _e('here.',self::$text_domain); ?></label>
								</a>
							</li>
							<li>
								<label><?php _e('group = The id of the group in which has the custom field.',self::$text_domain); ?></label>
							</li>
						</ol>
					</div>

					<div class="inside">
						<strong><?php _e('Optional',self::$text_domain); ?></strong>
						<ol>
							<em class="gabfire_custom_field_shorcode_code"><strong><?php _e('[gabfire_custom_field field="price" group="listing" image_size="200"]',self::$text_domain); ?></strong></em>
							<li>
								<label><?php _e('image_size = Size of the square image you want to display.  If "50" is entered in, then the image will be of size 50 x 50.',self::$text_domain); ?></label>
							</li>

							<em class="gabfire_custom_field_shorcode_code"><strong><?php _e('[gabfire_custom_field field="price" group="listing" date_format="1"]',self::$text_domain); ?></strong></em>
							<li>
								<label><?php _e('date_format = Number 1-6 of the date format you want. Assuming the date is March 10th, 2001, 5:16:18 pm',self::$text_domain); ?></label>
								<ol>
									<li> 	<label><?php _e('March 10, 2001',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('2001-03-10 17:16:18',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('03/10/01',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('Sat Mar 10',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('March 10, 2001 5:16',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('10/03/01',self::$text_domain); ?></label> </li>

								</ol>
							</li>
						</ol>
					</div>

				</div>

				<h3><?php _e('Php Template Tags',self::$text_domain); ?></h3>

				<div class="postbox">
					<h3><span><?php _e('Description',self::$text_domain); ?></span></h3>

					<div class="inside">
						<?php _e('Insert this ',self::$text_domain); ?>
						<em class="gabfire_custom_field_shorcode_code"><strong><?php _e('&lt;php gabfire_custom_field("price", "listing", "1") ?&gt;',self::$text_domain); ?></strong></em>
						<label><?php _e(' into any post that has a custom field.',self::$text_domain); ?></label>
					</div>

				</div>

				<div class="postbox">

					<h3><span><?php _e('Parameters',self::$text_domain); ?></span></h3>

					<div class="inside">

						<strong style="color:red"><?php _e('Required',self::$text_domain); ?></strong>
						<ol>
							<li>
								<label><?php _e('$field = The id of the custom post type. You can find the ids ',self::$text_domain); ?></label>
								<a href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . self::$field_dashboard_page; ?>">
									<label><?php _e('here.',self::$text_domain); ?></label>
								</a>
							</li>
							<li>
								<label><?php _e('$group = The id of the group in which has the custom field.',self::$text_domain); ?></label>
							</li>
							<li>
								<label><?php _e('$post_id = The id of the post in which has the custom field group.',self::$text_domain); ?></label>
							</li>
						</ol>

						<strong><?php _e('Optional',self::$text_domain); ?></strong>
						<ol>
							<em class="gabfire_custom_field_shorcode_code"><strong><?php _e('&lt;php gabfire_custom_field("price", "listing", "1", array("image_size" => "100")) ?&gt;',self::$text_domain); ?></strong></em>
							<li>
								<label><?php _e('image_size = Size of the square image you want to display.  If "50" is entered in, then the image will be of size 50 x 50.',self::$text_domain); ?></label>
							</li>

							<em class="gabfire_custom_field_shorcode_code"><strong><?php _e('&lt;php gabfire_custom_field("price", "listing", "1", array("date_format" => "1")) ?&gt;',self::$text_domain); ?></strong></em>
							<li>
								<label><?php _e('date_format = Number 1-6 of the date format you want. Assuming the date is March 10th, 2001, 5:16:18 pm',self::$text_domain); ?></label>
								<ol>
									<li> 	<label><?php _e('March 10, 2001',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('2001-03-10 17:16:18',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('03/10/01',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('Sat Mar 10',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('March 10, 2001 5:16',self::$text_domain); ?></label> </li>
									<li>	<label><?php _e('10/03/01',self::$text_domain); ?></label> </li>

								</ol>
							</li>
						</ol>

					</div>

				</div>

<?php require_once('footer.php'); ?>