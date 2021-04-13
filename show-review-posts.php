<?php

/**
 * @link              simpals.com
 * @since             1.0.0
 * @package           show_review_posts
 *
 * @wordpress-plugin
 * Plugin Name:       Hapigood reviews plugin
 * Plugin URI:        simpals.com
 * Description:       This is a custom Hapigood plugin for reviews showing
 * Version:           1.4.2
 * Author:            Simpals Dev
 * Author URI:        simpals.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       show_review_posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 */


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SHOW_REVIEW_POSTS_VERSION', '1.4.0' );
define( 'PHP_REQUIRES_VERSION', '7.2' );
define( 'PLUGIN_SLUG', 'srp' );


// plugin setup
define('NUMBER_OF_WORDS', 30);
define('MAIN_LOGO_NAME', 'hapigood-logo.png');
define('MAIN_LOGO_NAME_WITHOUT_TEXT', 'hapigood-logo-without-text.jpg');


/**
 * Include files
 */
require_once dirname( __FILE__ ) . '/admin-panel.php';
require_once dirname( __FILE__ ) . '/shortcode.php';


/**
 * Required Hooks
 */
register_activation_hook( __FILE__, 'srp_flush_rewrites' );
register_deactivation_hook( __FILE__, 'srp_flush_rewrite_rules' );


/**
 * Flush rewrite rules on activation
 */
function srp_flush_rewrites() {
	// call your CPT registration function here (it should also be hooked into 'init')
	srp_create_custom_post_type();
	flush_rewrite_rules();
}


/**
 * Flush rewrite rules on deactivation
 */
function srp_flush_rewrite_rules() {
	flush_rewrite_rules();
}


/**
 * Enqueue scripts.
 */
function srp_plugin_enqueue_styles() {

	// include CSS files
	wp_enqueue_style( 'show-reviews-plugin', plugins_url( 'assets/style.css', __FILE__ ) );

	// Include JS
	wp_enqueue_script( 'show-reviews-plugin-js', plugins_url( 'assets/script.js', __FILE__ ), array(), '1.0.0', true ); // Print in footer
}

add_action( 'wp_enqueue_scripts', 'srp_plugin_enqueue_styles' );


/**
 * Admin Enqueue scripts.
 */
function srp_plugin_admin_enqueue_styles() {

	// include CSS files
	wp_enqueue_style( 'show-reviews-plugin', plugins_url( 'assets/admin.css', __FILE__ ) );
}

add_action( 'admin_enqueue_scripts', 'srp_plugin_admin_enqueue_styles' );



/**
 * Create post type 'srp_review_posts'
 */
if ( ! function_exists( 'srp_create_custom_post_type' ) ) {

// Register Custom Post Type
	function srp_create_custom_post_type() {

		$labels = array(
			'name'                  => _x( 'Reviews', 'Post Type General Name', 'show_review_posts' ),
			'singular_name'         => _x( 'Review', 'Post Type Singular Name', 'show_review_posts' ),
			'menu_name'             => __( 'Reviews', 'show_review_posts' ),
			'name_admin_bar'        => __( 'Reviews', 'show_review_posts' ),
			'archives'              => __( 'Item Archives', 'show_review_posts' ),
			'attributes'            => __( 'Item Attributes', 'show_review_posts' ),
			'parent_item_colon'     => __( 'Parent Item:', 'show_review_posts' ),
			'all_items'             => __( 'All Items', 'show_review_posts' ),
			'add_new_item'          => __( 'Add New Item', 'show_review_posts' ),
			'add_new'               => __( 'Add New', 'show_review_posts' ),
			'new_item'              => __( 'New Item', 'show_review_posts' ),
			'edit_item'             => __( 'Edit Item', 'show_review_posts' ),
			'update_item'           => __( 'Update Item', 'show_review_posts' ),
			'view_item'             => __( 'View Item', 'show_review_posts' ),
			'view_items'            => __( 'View Items', 'show_review_posts' ),
			'search_items'          => __( 'Search Item', 'show_review_posts' ),
			'not_found'             => __( 'Not found', 'show_review_posts' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'show_review_posts' ),
			'featured_image'        => __( 'Featured Image', 'show_review_posts' ),
			'set_featured_image'    => __( 'Set featured image', 'show_review_posts' ),
			'remove_featured_image' => __( 'Remove featured image', 'show_review_posts' ),
			'use_featured_image'    => __( 'Use as featured image', 'show_review_posts' ),
			'insert_into_item'      => __( 'Insert into item', 'show_review_posts' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'show_review_posts' ),
			'items_list'            => __( 'Items list', 'show_review_posts' ),
			'items_list_navigation' => __( 'Items list navigation', 'show_review_posts' ),
			'filter_items_list'     => __( 'Filter items list', 'show_review_posts' ),
		);
		$args   = array(
			'label'               => __( 'Review', 'show_review_posts' ),
			'description'         => __( 'Hapigood Reviews', 'show_review_posts' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'taxonomies'          => array( 'srp_review_tax_cat' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-testimonial',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);
		register_post_type( 'srp_review_posts', $args );

	}

	add_action( 'init', 'srp_create_custom_post_type', 0 );

}


/**
 * Create taxonomies
 */
if ( ! function_exists( 'srp_create_reviews_taxonomy' ) ) {

// Register Custom Taxonomy
	function srp_create_reviews_taxonomy() {

		$labels = array(
			'name'                       => _x( 'Categories', 'Taxonomy General Name', 'show_review_posts' ),
			'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'show_review_posts' ),
			'menu_name'                  => __( 'Category', 'show_review_posts' ),
			'all_items'                  => __( 'All Items', 'show_review_posts' ),
			'parent_item'                => __( 'Parent Item', 'show_review_posts' ),
			'parent_item_colon'          => __( 'Parent Item:', 'show_review_posts' ),
			'new_item_name'              => __( 'New Item Name', 'show_review_posts' ),
			'add_new_item'               => __( 'Add New Item', 'show_review_posts' ),
			'edit_item'                  => __( 'Edit Item', 'show_review_posts' ),
			'update_item'                => __( 'Update Item', 'show_review_posts' ),
			'view_item'                  => __( 'View Item', 'show_review_posts' ),
			'separate_items_with_commas' => __( 'Separate items with commas', 'show_review_posts' ),
			'add_or_remove_items'        => __( 'Add or remove items', 'show_review_posts' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'show_review_posts' ),
			'popular_items'              => __( 'Popular Items', 'show_review_posts' ),
			'search_items'               => __( 'Search Items', 'show_review_posts' ),
			'not_found'                  => __( 'Not Found', 'show_review_posts' ),
			'no_terms'                   => __( 'No items', 'show_review_posts' ),
			'items_list'                 => __( 'Items list', 'show_review_posts' ),
			'items_list_navigation'      => __( 'Items list navigation', 'show_review_posts' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'srp_review_tax_cat', array( 'srp_review_posts' ), $args );

	}

	add_action( 'init', 'srp_create_reviews_taxonomy', 0 );

}


/**
 * Metaboxes
 */
add_action( 'load-post.php', 'srp_meta_boxes_setup' );
add_action( 'load-post-new.php', 'srp_meta_boxes_setup' );

// Meta box setup function.
function srp_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'srp_add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'srp_save_review_meta', 10, 2 );
}


// add_meta_boxes action hook
function srp_add_post_meta_boxes() {

	add_meta_box(
		'srp-review-author-name',                                          // Unique ID
		esc_html__( 'Review author', 'show_review_posts' ),       // Title
		'srp_reviews_meta_box_html',                            // Callback function
		'srp_review_posts',                                            // Admin page (or post type)
		'side',                                                        // Context
		'default'                                                      // Priority
	);
}

// Display the author post meta box.
//TODO: de imbunatatit forma, eliminare p-uri, eroare consola,
//TODO: nonce de vazut ce-i
function srp_reviews_meta_box_html( $post ) {

	$srp_author_name_meta        = esc_attr( get_post_meta( $post->ID, 'srp_author_name_meta', true ) );
	$srp_author_description_meta = esc_attr( get_post_meta( $post->ID, 'srp_author_description_meta', true ) );
	$srp_review_link_meta        = esc_attr( get_post_meta( $post->ID, 'srp_review_link_meta', true ) );
	$srp_review_link_text_meta   = esc_attr( get_post_meta( $post->ID, 'srp_review_link_text_meta', true ) );

	?>

	<?php //wp_nonce_field( basename( __FILE__ ), 'srp_author_name_meta_nonce' ); ?>

	<p>
		<label for="srp-review-author-name">
		<?php _e( "Add the name of the author of the review.", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-author-name" id="srp-review-author-name"
					 value="<?php echo $srp_author_name_meta; ?>"/>
	</p>

	<p>
		<label for="srp-review-author-description">
		<?php _e( "Add the description of the author of the review.", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-author-description" id="srp-review-author-description"
					 value="<?php echo $srp_author_description_meta ?>"/>
	</p>

	<p>
		<label for="srp-review-link">
		<?php _e( "Review Link:", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-link" id="srp-review-link"
					 value="<?php echo $srp_review_link_meta ?>"/>
	</p>

	<p>
		<label for="srp-review-link-text">
		<?php _e( "Review Link Text:", 'show_review_posts' ); ?>
		</label>
		<br/>
		<input class="widefat" type="text" name="srp-review-link-text" id="srp-review-link-text"
					 value="<?php echo $srp_review_link_text_meta ?>"/>
	</p>
<?php }


// Save the Author post metadata.
function srp_save_review_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
//		TODO: de vazut aici ce-i
//	if ( ! isset( $_POST['srp_author_name_meta_nonce'] ) || ! wp_verify_nonce( $_POST['srp_author_name_meta_nonce'], basename( __FILE__ ) ) ) {
//		return $post_id;
//	}// Check if user has permissions to save data.

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	// Check if not an autosave.
	if ( wp_is_post_autosave( $post_id ) ) {
		return $post_id;
	}

	// Check if not a revision.
	if ( wp_is_post_revision( $post_id ) ) {
		return $post_id;
	}

	/* Check if the current user has permission to edit the post. */
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Do not save the data if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value_author_name        = isset( $_POST['srp-review-author-name'] ) ? sanitize_text_field( $_POST['srp-review-author-name'] ) : '';
	$new_meta_value_author_description = isset( $_POST['srp-review-author-description'] ) ? sanitize_text_field( $_POST['srp-review-author-description'] ) : '';
	$new_meta_value_review_link        = isset( $_POST['srp-review-link'] ) ? sanitize_text_field( $_POST['srp-review-link'] ) : '';
	$new_meta_value_review_link_text   = isset( $_POST['srp-review-link-text'] ) ? sanitize_text_field( $_POST['srp-review-link-text'] ) : '';

	/* Get the meta key. */
	$meta_key_author_name        = 'srp_author_name_meta';
	$meta_key_author_description = 'srp_author_description_meta';
	$meta_key_review_link        = 'srp_review_link_meta';
	$meta_key_review_link_text   = 'srp_review_link_text_meta';

	/* Get the meta value */
	$meta_value_author_name        = get_post_meta( $post_id, $meta_key_author_name, true );
	$meta_value_author_description = get_post_meta( $post_id, $meta_key_author_description, true );
	$meta_value_review_link        = get_post_meta( $post_id, $meta_key_review_link, true );
	$meta_value_review_link_text   = get_post_meta( $post_id, $meta_key_review_link_text, true );


	/* If a new meta value was added and does not match the old value, add it. */
	if ( isset( $new_meta_value_author_name ) && $new_meta_value_author_name != $meta_value_author_name ) {
		update_post_meta( $post_id, $meta_key_author_name, $new_meta_value_author_name );
	}

	/* If a new meta value was added and does not match the old value, add it. */
	if ( isset( $new_meta_value_author_description ) && $new_meta_value_author_description != $meta_value_author_description ) {
		update_post_meta( $post_id, $meta_key_author_description, $new_meta_value_author_description );
	}

	/* If a new meta value was added and does not match the old value, add it. */
	if ( isset( $new_meta_value_review_link ) && $new_meta_value_review_link != $meta_value_review_link ) {
		update_post_meta( $post_id, $meta_key_review_link, $new_meta_value_review_link );
	}

	/* If a new meta value was added and does not match the old value, add it. */
	if ( isset( $new_meta_value_review_link_text ) && $new_meta_value_review_link_text != $meta_value_review_link_text ) {
		update_post_meta( $post_id, $meta_key_review_link_text, $new_meta_value_review_link_text );
	}


// old code
	/* If a new meta value was added and there was no previous value, add it. */
//	if ( $new_meta_value_author_description && ’ == $meta_value_author_description ) {
//		add_post_meta( $post_id, $meta_key_author_description, $new_meta_value_author_description, true );
//	} /* If the new meta value does not match the old value, update it. */
//		elseif ( $new_meta_value_author_description && $new_meta_value_author_description != $meta_value_author_description ) {
//		update_post_meta( $post_id, $meta_key_author_description, $new_meta_value_author_description );
//	} /* If there is no new meta value but an old value exists, delete it. */
//		elseif ( ’ == $new_meta_value_author_description && $meta_value_author_description ) {
//		delete_post_meta( $post_id, $meta_key_author_description, $meta_value_author_description );
//	}
}


/**
 * Plugin update functions
 */
require dirname( __FILE__ ) . '/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/josanua/show-review-posts-plugin',
	__FILE__,
	'show_review_posts'
);

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('');

//Set the branch that contains the stable release. 
$myUpdateChecker->setBranch( 'main' );

