<?php
require __DIR__ . '/gd-text/autoload.php';

use GDText\Box;
use GDText\Color;

function generate_og( $bg_image, $review, $author, $slug ){

    $file = dirname( __FILE__ ) . '/assets/images/og/' . $slug . '.png';

    if (!file_exists($file)) {

        $im = imagecreatefromjpeg( $bg_image );

        $box = new Box($im);
        $box->setFontFace(dirname( __FILE__ ) . "/assets/fonts/Montserrat-Regular.ttf");
        $box->setFontColor(new Color(0, 0, 0));
        $box->setFontSize(30);
        $box->setBox(100, 240, 1000, 660);
        $box->setTextAlign('center', 'top');
        $box->draw($review);

        $box = new Box($im);
        $box->setFontFace(dirname( __FILE__ ) . "/assets/fonts/Montserrat-Bold.ttf");
        $box->setFontSize(30);
        $box->setFontColor(new Color(0, 0, 0));
        $box->setBox(0, 120, 1200, 300);
        $box->setTextAlign('center', 'bottom');
        $box->draw($author);

        imagepng($im, $file);

    }
    return plugins_url( "/assets/images/og/" . $slug . ".png", __FILE__ );
}

//add_filter( 'wpseo_opengraph_image', 'change_og_image_to_autogenerate' );

function change_og_image_to_autogenerate( $url ) {
        $slug = get_post_field( 'post_name', get_post() );
        $post_id = get_the_ID();
        $bg_image = plugins_url( "assets/images/og-bg.jpg", __FILE__ );
        $review = mb_strimwidth(wp_strip_all_tags( get_the_content() ), 0, 180, '...');
        $author = strtoupper( get_post_meta( $post_id, 'srp_author_name_meta', true ) );

        return generate_og($bg_image, $review, $author, $slug);
}