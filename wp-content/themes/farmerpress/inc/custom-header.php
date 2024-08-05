<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Theme Palace
 * @subpackage Farmerpress
 * @since Farmerpress 1.0.0
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses farmerpress_header_style()
 */
function farmerpress_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'farmerpress_custom_header_args', array(
		'default-image'          => '%s/assets/uploads/header-image.jpg',
		'default-text-color'     => '181818',
		'width'                  => 1200,
		'height'                 => 528,
		'flex-height'            => true,
		'wp-head-callback'       => 'farmerpress_header_style',
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/uploads/header-image.jpg',
			'thumbnail_url' => '%s/assets/uploads/header-image.jpg',
			'description'   => esc_html__( 'Default Header Image', 'farmerpress' ),
		),
	) );
}
add_action( 'after_setup_theme', 'farmerpress_custom_header_setup' );

if ( ! function_exists( 'farmerpress_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see farmerpress_custom_header_setup().
	 */
	function farmerpress_header_style() {
		$options = farmerpress_get_theme_options();
		$css = '';

		$header_title_color = $options['header_title_color'];
		$header_tagline_color = $options['header_tagline_color'];


		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: HEADER_TEXTCOLOR.
		 */
		if ( $header_title_color && $header_tagline_color ) {

			// If we get this far, we have custom styles. Let's do this.
			// Has the text been hidden?
			if ( ! display_header_text() ) :
			$css .='
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}';

			// If the user has set a custom color for the text use that.
			else :
			$css .='
			.site-title a {
				color: '.esc_attr( $header_title_color ).';
			}
			.site-description {
				color: '.esc_attr( $header_tagline_color ).';
			}';
			endif;
		}

		$pagination_type = isset( $options['pagination_type'] ) ? $options['pagination_type'] : 'default';
		if ( $pagination_type == 'infinite' ) {
			$css .= '
			.site-main nav.pagination.navigation {
				display:none;
			}';
		}

		$css .= '.trail-items li:not(:last-child):after {
			    content: "' . html_entity_decode( esc_attr( $options['breadcrumb_separator'] ) ) . '";
			    padding: 0 5px;
			}';
		
		wp_add_inline_style( 'farmerpress-style', $css );
	}
endif;
add_action( 'wp_enqueue_scripts', 'farmerpress_header_style', 10 );