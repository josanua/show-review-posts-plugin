<?php
/**
 * Shortcode
 * [show_review_posts  show_on_home="" posts_per_page=""  category_id=""]
 *
 */
function srp_generate_review_posts( $atts ) {

	// default values
	// define( 'DEFAULT_REVIEWS_LINK', get_home_url() . '/reviews/' );

	// get plugin options
	$options_main_link         		= get_option( 'srp_options' );
	$options_review_link       		= get_option( 'srp_review_link_option' );
	$options_more_reviews_link 		= get_option( 'srp_more_reviews_link_option' );

	// home page show posts number
	$options_posts_num_home_page 	= get_option( 'srp_posts_num_home_page_option' );
	$options_posts_num_home_page 	= $options_posts_num_home_page['srp_posts_num_home_page_option'];

	// Detect main logo value
	$hapigood_main_logo 			= get_option( 'srp_select_main_logo_img' );

	if($hapigood_main_logo == '') {
		$hapigood_main_logo = MAIN_LOGO_NAME;
	} else {
		$hapigood_main_logo = $hapigood_main_logo['srp_select_main_logo_img'];
	}

	// define main logo css style
	switch ($hapigood_main_logo) {
		case MAIN_LOGO_NAME:
			$hapigood_main_logo_class = '';
			break;
		case MAIN_LOGO_NAME_WITHOUT_TEXT:
			$hapigood_main_logo_class = 'without-text';
			break;
//		case 'hapigood-logo-without-text.jpg':
//			$hapigood_main_logo_class = '';
//			break;
	}


	// default attributes
	$atts = shortcode_atts( [
		'show_on_home'   => 0,
		'posts_per_page' => 5,
		'category_id'    => '',
	], $atts );


	// get setup values
	$show_on_home_state = intval( trim( $atts['show_on_home'] ) );
	$post_type_slug     = 'srp_review_posts';
	$category_id        = trim( $atts['category_id'] );
	$posts_per_page     = $atts['posts_per_page']; // it must be a string type!

	if ($show_on_home_state == 1 && $options_posts_num_home_page != '') {
		$posts_per_page = $options_posts_num_home_page;
	}


	// custom query args setup
	$currentPage = get_query_var( 'paged' );
	$args        = array(
		'post_type'      => $post_type_slug,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'cat'            => $category_id,
		'posts_per_page' => $posts_per_page,
		'paged'          => $currentPage
	);

	$custom_query = new WP_Query( $args );


	// calc number of words in content pos
	function srp_words_num($text = '') {
		$text = wp_strip_all_tags( $text );

		return str_word_count($text, 0);
	}

	ob_start();
	?>

	<div class="show-review-posts-row">

		<div class="logos-img">
			<a href="<?php echo $options_main_link['srp_main_logo_link_field']; ?>" target="_blank">
				<img src="<?php echo plugins_url( "assets/images/{$hapigood_main_logo}", __FILE__ ) ?>" class="hapigood-logo <?php echo $hapigood_main_logo_class ?>"
				     alt="hapigood logo">
			</a>

			<a href="<?php echo $options_review_link['srp_review_logo_link_field']; ?>" target="_blank"
			   class="write-review-btn-link">
				<img src="<?php echo plugins_url( 'assets/images/write-review-button.png', __FILE__ ) ?>"
				     class="write-review-btn"
				     alt="write review btn">
			</a>
		</div>

		<?php
		if ( $custom_query->have_posts() ) :

			while ( $custom_query->have_posts() ) : $custom_query->the_post();
				$post_id = get_the_ID();
				$text_content = get_the_content();
				?>

				<article class="review-posts-article">

					<header class="review-posts-entry-header">

								<span class="review-author-name">
									<b>by</b>
									<?php

									// get author name
									if ( ! empty( $post_id ) ) {

										// Get the custom post class.
										$review_author_name = get_post_meta( $post_id, 'srp_author_name_meta', true );

										// If a post class was input, sanitize it and add it to the post class array.
										if ( ! empty( $review_author_name ) ) {
											echo $review_author_name;
										} else {
											echo get_the_author();
										}
									}

									?>
								</span>


						<?php
						// get link address
						if ( ! empty( $post_id ) ) {

							// Get the custom post class.
							$author_description_text = get_post_meta( $post_id, 'srp_author_description_meta', true );

							// If a post class was input, sanitize it and add it to the post class array.
							if ( ! empty( $author_description_text ) ) { ?>
								<span class="review-author-description">
											<?php echo $author_description_text ?>
										</span>
							<?php }
						}
						?>

						<span class="review-posts-date">
									<?php the_time( 'd / m / y' ); ?>
								</span>


						<?php
						// get link address
						if ( ! empty( $post_id ) ) {

							// Get the custom post class.
							$review_link      = get_post_meta( $post_id, 'srp_review_link_meta', true );
							$review_link_text = get_post_meta( $post_id, 'srp_review_link_text_meta', true );

							// If a post class was input, sanitize it and add it to the post class array.
							if ( ! empty( $review_link ) && ! empty( $review_link_text ) ) { ?>
								<a href="<?php echo $review_link ?>" class="review-posts-link-to-source" target="_blank">
									<?php echo $review_link_text ?>
								</a>
							<?php }
						}
						?>
					</header><!-- .review-posts-entry-header -->

					<div class="review-posts-entry-content <?php if ( empty( $text_content ) ) echo 'hide' ;  ?>">
						<p>
							<?php echo wp_trim_words( get_the_excerpt(), NUMBER_OF_WORDS, __( ' ...' ) ); ?>
						</p>
					</div><!-- .review-posts-entry-content -->

					<div class="review-posts-full-content">
						<?php the_content(); ?>

					</div><!-- .eview-posts-full-content -->

					<?php $srp_words_num = srp_words_num($text_content); ?>
					<footer class="review-posts-entry-footer <?php if ($srp_words_num <= NUMBER_OF_WORDS) echo 'hide' ; ?>">

								<span class="link-full-review">
									<?php _e( 'Read more', 'show_review_posts' ); ?>
								</span>
						<span class="close-link-full-review">
									<?php _e( 'Close', 'show_review_posts' ); ?>
								</span>
						<!--								<a href="--><?php //the_permalink() ?><!--" class="link-full-review" >-->
						<!--					--><?php //_e( 'Full review', 'show_review_posts' ); ?>
						<!--								</a>-->
					</footer><!-- .review-posts-entry-footer -->
				</article><!-- .article -->


			<?php

			endwhile;

			if ( $show_on_home_state == 0 ) :
				echo paginate_links( array( 'total' => $custom_query->max_num_pages ) );
			endif;

			wp_reset_postdata();
		endif;

		if ( $show_on_home_state != 0 ) :

			?>
			<a href="<?php echo $options_more_reviews_link['srp_more_reviews_link_field'] ?>" class="more-button">
				<?php _e( 'More', 'show_review_posts' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<?php

	return ob_get_clean();
}

add_shortcode( 'show_review_posts', 'srp_generate_review_posts' );