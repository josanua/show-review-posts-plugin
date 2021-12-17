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
		'in_category'    => 0,
		'location_name'	 => '',
		'full_review' => 0,
		'google_location' => ''
	], $atts );


	// get setup values
	$show_on_home_state = intval( trim( $atts['show_on_home'] ) );
	$post_type_slug     = 'srp_review_posts';
	$category_id        = trim( $atts['category_id'] );
	$location_name		= trim( $atts['location_name'] );
	$posts_per_page     = $atts['posts_per_page']; // it must be a string type!
	$full_review     = $atts['full_review'];
	$google_location_url =  $atts['google_location'];

	if ($full_review == 0) { // If review list


	if ($show_on_home_state == 1 && $options_posts_num_home_page != '') {
		$posts_per_page = $options_posts_num_home_page;
	}


	// custom query args setup
	$currentPage = get_query_var( 'paged' );

	
	if ( $category_id ) {
		$args = array(
			'tax_query' => array(
				array(
					'taxonomy' => 'srp_review_tax_cat',
					'field' => 'tax_ID',
					'terms' => $category_id
				)
			),
			'post_type'      => $post_type_slug,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => $posts_per_page,
			'paged'          => $currentPage
		);
	} else {
		$args = array(
			'post_type'      => $post_type_slug,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => $posts_per_page,
			'paged'          => $currentPage
		);
	}
	
	$custom_query = new WP_Query( $args );


	// calc number of words in content pos
	if (!function_exists('srp_words_num')) {
	  function srp_words_num( $text = '' ) {
		  $text = wp_strip_all_tags( $text );

		  return str_word_count( $text, 0 );
	  }
  }

	ob_start();
	?>

	<div class="show-review-posts-row">

		<div class="logos-img">
			<a href="<?php echo $options_main_link['srp_main_logo_link_field']; ?>" target="_blank">
				<img src="<?php echo plugins_url( "assets/images/{$hapigood_main_logo}", __FILE__ ) ?>" class="hapigood-logo <?php echo $hapigood_main_logo_class ?>"
				     alt="hapigood logo">
			</a>

			<?php
				global $rev_link;
				if ( $atts['in_category'] == 1 && $category_id ) :
					$term = get_term( $category_id );
					$rev_link = $options_review_link['srp_review_logo_link_field'] . '?location=' . $category_id . '&location_name=' . $term->name;// . '&google_location=' . $google_location_url;
				else :
				 	$rev_link = $options_review_link['srp_review_logo_link_field'];// . '?google_location=' . $google_location_url;
				endif;
			?>
			<a href="<?php echo $rev_link ?>" target="_blank"
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
				$post_url = get_permalink();
				?>

				<article class="review-posts-article">

					<header class="review-posts-entry-header">
						<div class="review-posts-left">
								<span class="review-author-name">
									<b>by
										<strong>
									<?php

										$review_author_name = get_post_meta( get_the_ID(), 'srp_author_name_meta', true );
										
										if ( ! empty( $review_author_name ) ) {
											echo $review_author_name;
										} else {
											echo get_the_author();
										}

									?>
								</strong>
									</b>
								</span>

								<?php
									// get link address

										// Get the custom post class.
										$author_description_text = get_post_meta(  get_the_ID(), 'srp_author_description_meta', true );

										// If a post class was input, sanitize it and add it to the post class array.
										if ( ! empty( $author_description_text ) ) { ?>
											<span class="review-author-description">
														<?php echo $author_description_text ?>
													</span>
										<?php } ?>

							<?php
							// get link address

								// Get the custom post class.
								$review_link      = get_post_meta(  get_the_ID(), 'srp_review_link_meta', true );
								$review_link_text = get_post_meta(  get_the_ID(), 'srp_review_link_text_meta', true );

								// If a post class was input, sanitize it and add it to the post class array.
								if ( ! empty( $review_link ) && ! empty( $review_link_text ) ) { ?>
									<a href="<?php echo $review_link ?>" class="review-posts-link-to-source" target="_blank">
										<?php echo $review_link_text ?>
									</a>
								<?php }
							?>
						</div>

						<div class="review-posts-right">
							<span class="review-posts-date">
								<?php the_time( 'm / d / y' ); ?>
							</span>
						</div>
					</header><!-- .review-posts-entry-header -->

					<div class="review-posts-entry-content <?php if ( empty( $text_content ) ) echo 'hide' ;  ?>">
						<p>
							<?php //echo wp_trim_words( get_the_content(), NUMBER_OF_WORDS, __( ' ...' ) ); 
							echo get_the_content();
							?>
						</p>
					</div><!-- .review-posts-entry-content -->

					<div class="review-posts-full-content">
						<?php the_content(); ?>

					</div><!-- .eview-posts-full-content -->

					<?php $srp_words_num = srp_words_num($text_content); ?>
					<footer class="review-posts-entry-footer <?php //if ($srp_words_num <= NUMBER_OF_WORDS) echo 'hide' ; ?>">

								<span class="link-full-review">
									<a href="<?php the_permalink() ?>">
										<?php _e( 'Read more', 'show_review_posts' ); ?>
									</a>
								</span>
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
	<!-- . show-review-posts-row -->

	<?php
// Child pages list
	return ob_get_clean();
	} else { // If review single ?>
<div class="show-review-posts-row">
		<div class="logos-img">
			<a href="<?php echo $options_main_link['srp_main_logo_link_field']; ?>" target="_blank">
				<img src="<?php echo plugins_url( "assets/images/{$hapigood_main_logo}", __FILE__ ) ?>" class="hapigood-logo <?php echo $hapigood_main_logo_class ?>"
				     alt="hapigood logo">
			</a>

			<?php
					global $rev_link;
				if ( $atts['in_category'] == 1 && $location_name ) :
					$rev_link = $options_review_link['srp_review_logo_link_field'] . '?location=' . $category_id . '&location_name=' . $location_name;
				else :
					$rev_link = $options_review_link['srp_review_logo_link_field'];
				endif;
			?>
			<a href="<?php echo $rev_link ?>" target="_blank"
			   class="write-review-btn-link">
				<img src="<?php echo plugins_url( 'assets/images/write-review-button.png', __FILE__ ) ?>"
				     class="write-review-btn"
				     alt="write review btn">
			</a>
		</div>
		
				<article class="review-posts-article">

					<header class="review-posts-entry-header">
						<div class="review-posts-left">
								<span class="review-author-name">
									<b>by
										<strong>
									<?php

										$review_author_name = get_post_meta( get_the_ID(), 'srp_author_name_meta', true );
										
										if ( ! empty( $review_author_name ) ) {
											echo $review_author_name;
										} else {
											echo get_the_author();
										}

									?>
								</strong>
									</b>
								</span>

								<?php
									// get link address

										// Get the custom post class.
										$author_description_text = get_post_meta(  get_the_ID(), 'srp_author_description_meta', true );

										// If a post class was input, sanitize it and add it to the post class array.
										if ( ! empty( $author_description_text ) ) { ?>
											<span class="review-author-description">
														<?php echo $author_description_text ?>
													</span>
										<?php } ?>

							<?php
							// get link address

								// Get the custom post class.
								$review_link      = get_post_meta(  get_the_ID(), 'srp_review_link_meta', true );
								$review_link_text = get_post_meta(  get_the_ID(), 'srp_review_link_text_meta', true );

								// If a post class was input, sanitize it and add it to the post class array.
								if ( ! empty( $review_link ) && ! empty( $review_link_text ) ) { ?>
									<a href="<?php echo $review_link ?>" class="review-posts-link-to-source" target="_blank">
										<?php echo $review_link_text ?>
									</a>
								<?php }
							?>
						</div>

						<div class="review-posts-right">
							<span class="review-posts-date">
								<?php the_time( 'm / d / y' ); ?>
							</span>
							<?php if(is_plugin_active( 'add-to-any/add-to-any.php' )){ ?>
							<div class="review-share-link" id="share">
								<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									 viewBox="0 0 227.216 227.216" xml:space="preserve" >
								<path d="M175.897,141.476c-13.249,0-25.11,6.044-32.98,15.518l-51.194-29.066c1.592-4.48,2.467-9.297,2.467-14.317
									c0-5.019-0.875-9.836-2.467-14.316l51.19-29.073c7.869,9.477,19.732,15.523,32.982,15.523c23.634,0,42.862-19.235,42.862-42.879
									C218.759,19.229,199.531,0,175.897,0C152.26,0,133.03,19.229,133.03,42.865c0,5.02,0.874,9.838,2.467,14.319L84.304,86.258
									c-7.869-9.472-19.729-15.514-32.975-15.514c-23.64,0-42.873,19.229-42.873,42.866c0,23.636,19.233,42.865,42.873,42.865
									c13.246,0,25.105-6.042,32.974-15.513l51.194,29.067c-1.593,4.481-2.468,9.3-2.468,14.321c0,23.636,19.23,42.865,42.867,42.865
									c23.634,0,42.862-19.23,42.862-42.865C218.759,160.71,199.531,141.476,175.897,141.476z M175.897,15
									c15.363,0,27.862,12.5,27.862,27.865c0,15.373-12.499,27.879-27.862,27.879c-15.366,0-27.867-12.506-27.867-27.879
									C148.03,27.5,160.531,15,175.897,15z M51.33,141.476c-15.369,0-27.873-12.501-27.873-27.865c0-15.366,12.504-27.866,27.873-27.866
									c15.363,0,27.861,12.5,27.861,27.866C79.191,128.975,66.692,141.476,51.33,141.476z M175.897,212.216
									c-15.366,0-27.867-12.501-27.867-27.865c0-15.37,12.501-27.875,27.867-27.875c15.363,0,27.862,12.505,27.862,27.875
									C203.759,199.715,191.26,212.216,175.897,212.216z"/>
								<g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
								</svg>
							</div>
						<?php } ?>
						</div>
					</header><!-- .review-posts-entry-header -->

					<div class="review-posts-entry-content">
						<p>
							<?php the_content(); ?>
						</p>
					</div><!-- .review-posts-entry-content -->
					<?php if(is_plugin_active( 'add-to-any/add-to-any.php' )){ ?>
		             <div class="share" style="display:none;">
		                  <?php echo do_shortcode('[addtoany]') ?>                
		             </div>
		         	<?php } ?>
				</article><!-- .article -->
<?php
		echo "<section class='l-section'><div class='l-section-h i-cf'><h3 class='see-more'>See more reviews </h2><div class='post_nav'>";
			the_post_navigation(
				array(
					'prev_text' => '<svg width="54" height="29" viewBox="0 0 54 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M53.1118 14.7433H1.42279M1.42279 14.7433L15.1295 1.03666M1.42279 14.7433L14.7288 27.9505" stroke="#06539F" stroke-width="1.5"/></svg> <span class="nav-title">%title</span>',
					'next_text' => ' <span class="nav-title">%title</span><svg width="54" height="29" viewBox="0 0 54 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.506348 14.7433H52.1954M52.1954 14.7433L38.4886 1.03666M52.1954 14.7433L38.8894 27.9505" stroke="#06539F" stroke-width="1.5"/></svg>',
				)
			);
		echo "</div></div></section>";
?>
<?php
	}
}

add_shortcode( 'show_review_posts', 'srp_generate_review_posts' );


function wpb_list_child_pages($rev_link) { 

global $post; 
global $rev_link;

if ( is_page() && $post->post_parent ) {
 
    $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );
} else {
    $childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );
}
if ( $childpages ) {

$string = '<div class="location-sidebar"><div class="wpb_wrapper"><div class="st-custom-heading-wraper st-custom-heading-layout1 text-default"><h2 class="st-heading-title""><span class="heading-text">Choose location</span></h2><hr class="heading-line"></div></div><div class="location-categories"><ul class="wpb_page_list" style="margin-top:25px">' . $childpages . '</ul></div></div>';
}

return $string;
 
}

add_shortcode('wpb_childpages', 'wpb_list_child_pages');