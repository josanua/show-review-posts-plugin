<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link              simpals.com
 * @since             1.0.0
 * @package           show_review_posts
 * @package           show_review_posts
 * @subpackage        show_review_posts_admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


/**
 * Enqueue admin scripts.
 */
function sr_plugin_enqueue_admin_styles() {

	// include CSS files
	wp_enqueue_style( 'show-reviews-plugin-admin', plugins_url( 'assets/admin.css', __FILE__ ) );

	// Include JS
	// wp_enqueue_script( 'custom-js-script', get_stylesheet_directory_uri() . '/custom-js.js', array(), '1.0.0', true); // Print in footer
}

add_action( 'admin_enqueue_scripts', 'sr_plugin_enqueue_admin_styles' );


/**
 * Custom option and settings:
 *  - callback functions
 */

/**
 * Shortcode presentation
 */
function srp_admin_html() {
	$pageTitle = __( 'Shortcode settings', 'show_review_posts' );
	$reviewsRSSLink =  get_bloginfo('url') . '/feed/?post_type=srp_review_posts';
	?>
	<div class="shortcode-info">
		<h1><?php echo $pageTitle ?></h1>

		<div class="documentation-text">
			<b>Shortcode default text:</b> [show_review_posts]<br/>
			<b>Shortcode with options (with default values):</b>[show_review_posts show_on_home="" posts_per_page=""
			category_id=""]<br/>
			<ul>
				Options list:
				<li><b>show_on_home - </b>it's destinated for 'Home Page', mean with "More Reviews button", 0 - no (it's
					default), 1 - for Home page.
				</li>
				<li><b>posts_per_page - </b>default 5 posts.</li>
				<li><b>category_id - </b>parent category ID, default is "" mean all.</li>
				<!--				<li><b>more_reviews_link - </b>change review link page.</li>-->
				<!--				<li><b>post_type - </b>future option, for now it's disabled.</li>-->
			</ul>

			<h2>Reviews link</h2>
			<p>
				<a href="<?php echo $reviewsRSSLink ?>" target="_blank">
					<?php echo $reviewsRSSLink; ?>
				</a>
			</p>
		</div>
	</div>

	<?php
}


/**
 * Admin options and settings
 */
function srp_admin_settings_init() {

	// Register a new setting
	register_setting( 'srp_admin_options', 'srp_options' );
	register_setting( 'srp_admin_options', 'srp_review_link_option' );
	register_setting( 'srp_admin_options', 'srp_more_reviews_link_option' );
	register_setting( 'srp_admin_options', 'srp_posts_num_home_page_option' );
	register_setting( 'srp_admin_options', 'srp_select_main_logo_img' );

	// for synchronization settings
	register_setting( 'srp_admin_options', 'srp_sync_site_address' );
	register_setting( 'srp_admin_options', 'srp_sync_security_code' );

	// Og image generator
	register_setting( 'srp_admin_options', 'srp_og_dynamic' );


	// Register a new section in the "srp-settings" page.
	add_settings_section(
		'srp_admin_section',
		'Hapigood Review Post Options',
		'srp_admin_section_html',
		'srp-settings'
	);

	// Register a new field in the "srp_admin_section" section, inside the "srp-settings" page.
	add_settings_field(
		'srp_main_logo_link_field',
		'Main Logo Link',
		'srp_logo_link_field_html',
		'srp-settings',
		'srp_admin_section',
		array(
			'label_for'       => 'srp_main_logo_link_field',
			'class'           => 'srp_main_logo_link_row',
			'srp_custom_data' => 'custom'
		)
	);

	add_settings_field(
		'srp_review_logo_link_field',
		'Write Review Logo Link',
		'srp_review_link_field_html',
		'srp-settings',
		'srp_admin_section',
		array(
			'label_for'       => 'srp_review_logo_link_field',
			'class'           => 'srp_review_link_field_row',
			'srp_custom_data' => 'custom_review_link_field_row'
		)
	);

	add_settings_field(
		'srp_more_reviews_link_field',
		'More Reviews Button Link',
		'srp_more_reviews_link_field_html',
		'srp-settings',
		'srp_admin_section',
		array(
			'label_for'       => 'srp_more_reviews_link_field',
			'class'           => 'srp_more_reviews_field_row',
			'srp_custom_data' => 'custom_more_reviews_link_field_row'
		)
	);

	add_settings_field(
		'srp_posts_num_home_page_option',
		'Reviews numbers on Home page',
		'srp_select_posts_num_home_html',
		'srp-settings',
		'srp_admin_section',
		array(
			'label_for'       => 'srp_posts_num_home_page_option',
			'class'           => 'srp_posts_num_home_page_option',
			'srp_custom_data' => 'custom_posts_num_home_page_row'
		)
	);

	add_settings_field(
		'srp_select_main_logo_img',
		'Select main logo img',
		'srp_select_main_logo_img_html',
		'srp-settings',
		'srp_admin_section',
		array(
			'label_for'       => 'srp_select_main_logo_img',
			'class'           => 'srp_select_main_logo_img',
			'srp_custom_data' => 'custom_select_main_logo_img_row'
		)
	);

	add_settings_field(
		'srp_sync_site_address',
		'Synchronization Site URL',
		'srp_sync_site_address_html',
		'srp-settings',
		'srp_admin_section',
		array(
			'label_for'       => 'srp_sync_site_address',
			'class'           => 'srp_sync_site_address',
			'srp_custom_data' => 'custom_srp_sync_site_address_row'
		)
	);

	add_settings_field(
			'srp_sync_security_code',
			'Synchronization security code',
			'srp_sync_security_code_html',
			'srp-settings',
			'srp_admin_section',
			array(
					'label_for'       => 'srp_sync_security_code',
					'class'           => 'srp_sync_security_code',
					'srp_custom_data' => 'custom_srp_sync_security_code_row'
			)
	);

	add_settings_field(
			'srp_og_dynamic',
			'Enable dynamic og image ?',
			'srp_og_dynamic_html',
			'srp-settings',
			'srp_admin_section',
			array(
					'label_for'       => 'srp_og_dynamic',
					'class'           => 'srp_og_dynamic',
					'srp_custom_data' => 'custom_srp_og_dynamic_row'
			)
	);
}

add_action( 'admin_init', 'srp_admin_settings_init' );


// Add the top level menu page.
function srp_options_page() {
	add_menu_page(
		'Hapigood review posts',
		'Hapigood Reviews Options',
		'manage_options',
		'srp-settings',
		'srp_options_page_html'
	);
}

add_action( 'admin_menu', 'srp_options_page' );


/**
 * Custom option and settings:
 *  - callback functions
 */

/**
 * SRP admin section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function srp_admin_section_html( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>">
	  <?php esc_html_e( 'Setup your settings.', 'show_review_posts' ); ?>
	</p>
	<?php
}

// show placeholder value helper
function showValuePlaceholder( $showValue = '' ) {

	if ( $showValue != '' ) {
		$returnValue = $showValue;
	} else {
		$returnValue = 'https://';
	}

	return $returnValue;
}


// function for check values existance
function showRequiredAttribute($value = '') {

	// detect existing value
	$hapigood_main_logo = get_option( 'srp_select_main_logo_img' );

	if($hapigood_main_logo == '') {
		$hapigood_main_logo = MAIN_LOGO_NAME;
	} else {
		$hapigood_main_logo = $hapigood_main_logo['srp_select_main_logo_img'];
	}

	// check input value
	if ($hapigood_main_logo == $value) {
	  $state = 'checked';
	}	else {
	  $state = '';
	}

	return $state;
}

/**
 * add_settings_field - callback function
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function srp_logo_link_field_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_options' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
				 name="srp_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
				 value="<?php echo showValuePlaceholder( $options['srp_main_logo_link_field'] ); ?>">

	<p class="description">
	  <?php esc_html_e( 'Enter the link of the main logo.', 'show_review_posts' ); ?><br/>
	</p>

	<?php
}

// Write Review Logo Link
function srp_review_link_field_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_review_link_option' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
				 name="srp_review_link_option[<?php echo esc_attr( $args['label_for'] ); ?>]"
				 value="<?php echo showValuePlaceholder( $options['srp_review_logo_link_field'] ); ?>">

	<p class="description">
	  <?php esc_html_e( 'Enter the link of the review page.', 'show_review_posts' ); ?><br/>
	</p>

	<?php
}

// Write More Reviews Logo Link
function srp_more_reviews_link_field_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_more_reviews_link_option' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
				 name="srp_more_reviews_link_option[<?php echo esc_attr( $args['label_for'] ); ?>]"
				 value="<?php echo showValuePlaceholder( $options['srp_more_reviews_link_field'] ); ?>">

	<p class="description">
	  <?php esc_html_e( 'Enter the link of the more reviews page.', 'show_review_posts' ); ?><br/>
	</p>

	<?php
}

// Select show posts numbers html
function srp_select_posts_num_home_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_posts_num_home_page_option' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="number" id="<?php echo esc_attr( $args['label_for'] ); ?>"
				 name="srp_posts_num_home_page_option[<?php echo esc_attr( $args['label_for'] ); ?>]"
				 value="<?php echo showValuePlaceholder( $options['srp_posts_num_home_page_option'] ); ?>">

	<p class="description">
	  <?php esc_html_e( 'Enter the desired number of reviews to display on the Home page.', 'show_review_posts' ); ?><br/>
	</p>

	<?php
}

// Main logo select html
function srp_select_main_logo_img_html( $args ) {

//	$options = get_option( 'srp_select_main_logo_img' );

	?>

	<div class="select-main-logos">
		<div class="logo-item">
			<img src="<?php echo plugins_url( "assets/images/" . MAIN_LOGO_NAME, __FILE__ ) ?>" class="hapigood-logo one"
					 alt="hapigood logo">
			<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
			<input type="radio" id="<?php echo esc_attr( $args['label_for'] ); ?>"
						 name="srp_select_main_logo_img[<?php echo esc_attr( $args['label_for'] ); ?>]"
						 value="<?php echo MAIN_LOGO_NAME ?>" <?php echo showRequiredAttribute(MAIN_LOGO_NAME ); ?>>
		</div>

		<div class="logo-item">
			<img src="<?php echo plugins_url( "assets/images/" . MAIN_LOGO_NAME_WITHOUT_TEXT, __FILE__ ) ?>"
					 class="hapigood-logo two"
					 alt="hapigood logo">
			<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
			<input type="radio" id="<?php echo esc_attr( $args['label_for'] ); ?>"
						 name="srp_select_main_logo_img[<?php echo esc_attr( $args['label_for'] ); ?>]"
						 value="<?php echo MAIN_LOGO_NAME_WITHOUT_TEXT ?>" <?php echo showRequiredAttribute(MAIN_LOGO_NAME_WITHOUT_TEXT ); ?>>
		</div>

<!--		<div class="logo-item">-->
<!--			<img src="--><?php //echo plugins_url( "assets/images/hapigood-logo.png", __FILE__ ) ?><!--" class="hapigood-logo three"-->
<!--					 alt="hapigood logo">-->
<!--		</div>-->

	  <?php

	//	  var_dump($options);

	  ?>
	</div>
<?php }


// Sync site address html
function srp_sync_site_address_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_sync_site_address' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
				 name="srp_sync_site_address[<?php echo esc_attr( $args['label_for'] ); ?>]"
				 value="<?php echo showValuePlaceholder( $options['srp_sync_site_address'] ); ?>">

	<p class="description">
	  <?php esc_html_e( 'Pls enter site url of Hapigood Reviews.', 'show_review_posts' ); ?><br/>
	</p>

	<?php
}

// Sync site security code html
function srp_sync_security_code_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_sync_security_code' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
				 name="srp_sync_security_code[<?php echo esc_attr( $args['label_for'] ); ?>]"
				 value="<?php echo $options['srp_sync_security_code'] ; ?>">

	<div id="btn-generate-security-code" class="button button-primary">Generate code</div>

	<p class="description">
		<?php esc_html_e( 'Pls. generate and save synchronization security code.', 'show_review_posts' ); ?><br/>
	</p>

	<?php
}

// Main logo select html
function srp_og_dynamic_html( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'srp_og_dynamic' );
	?>

	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"></label>
	<input type="checkbox" id="srp_og_dynamic" name="srp_og_dynamic[<?php echo esc_attr( $args['label_for'] ); ?>]" value="1" <?php if ( is_array($options) && 1 == $options['srp_og_dynamic'] ) echo 'checked="checked"'; ?> />

	<?php
}

/**
 * Top level menu callback function
 */
function srp_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error(
			'srp_messages',
			'srp_message',
			__( 'Settings Saved', 'show_review_posts' ),
			'updated'
		);
	}

	// show error/update messages
	settings_errors( 'srp_messages' );
	?>

	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
		<?php
			// output security fields for the registered setting "wporg"
			settings_fields( 'srp_admin_options' );
			// output setting sections and their fields
			// (sections are registered for "wporg", each field is registered to a specific section)
			do_settings_sections( 'srp-settings' );
			// output save settings button
			submit_button( 'Save Settings' );
		?>
		</form>
	  <?php srp_admin_html(); ?>
	</div>
	<?php
}