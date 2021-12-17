<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying all single posts
 *
 * Do not overload this file directly. Instead have a look at templates/single.php file in us-core plugin folder:
 * you should find all the needed hooks there.
 */

get_header();
?>

<main id="page-content" class="l-review">
	<?php echo do_shortcode ('[show_review_posts full_review="1"]') ?>
</main>
<?php
get_footer();