<?php
/**
 * Team section
 *
 * This is the template for the content of team section
 *
 * @package Theme Palace
 * @subpackage Farmerpress
 * @since Farmerpress 1.0.0
 */
if ( ! function_exists( 'farmerpress_add_team_section' ) ) :
    /**
    * Add team section
    *
    *@since farmerpress_section_status Pro 1.0.0
    */
    function farmerpress_add_team_section() {
    	$options = farmerpress_get_theme_options();
        // Check if team is enabled on frontpage
        $team_enable = apply_filters( 'farmerpress_section_status', true, 'team_section_enable' );

        if ( true !== $team_enable ) {
            return false;
        }
        // Get team section details
        $section_details = array();
        $section_details = apply_filters( 'farmerpress_filter_team_section_details', $section_details );

        if ( empty( $section_details ) ) {
            return;
        }

        // Render team section now.
        farmerpress_render_team_section( $section_details );
    }
endif;

if ( ! function_exists( 'farmerpress_get_team_section_details' ) ) :
    /**
    * team section details.
    *
    * @since farmerpress_section_status Pro 1.0.0
    * @param array $input team section details.
    */
    function farmerpress_get_team_section_details( $input ) {
        $options = farmerpress_get_theme_options();

        
        $content = array();
       
        $page_ids = array();

        for ( $i = 1; $i <= 4; $i++ ) {
            if ( ! empty( $options['team_content_page_' . $i] ) )
                $page_ids[] = $options['team_content_page_' . $i];
        }
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => absint( 4 ),
            'orderby'           => 'post__in',
            );                    
            
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['url']     = get_the_permalink( );
                $page_post['img']   = get_the_post_thumbnail_url( null, 'medium' );

                // Push to the main array.
                array_push( $content, $page_post );
            endwhile;
        endif;
        wp_reset_postdata();
  
            
        if ( ! empty( $content ) ) {
            $input = $content;
        }
        return $input;
    }
endif;
// team section content details.
add_filter( 'farmerpress_filter_team_section_details', 'farmerpress_get_team_section_details' );


if ( ! function_exists( 'farmerpress_render_team_section' ) ) :
  /**
   * Start team section
   *
   * @return string team content
   * @since farmerpress_section_status Pro 1.0.0
   *
   */
   function farmerpress_render_team_section( $content_details = array() ) {
        $options = farmerpress_get_theme_options();
        if ( empty( $content_details ) ) {
            return;
        } 

        ?>
        <div id="our-team" class="relative page-section same-background">
            <div class="wrapper">
                <div class="section-header">
                    <?php if ( ! empty( $options['team_title'] ) ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $options['team_title'] ); ?></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $options['team_subtitle'] ) ) : ?>
                            <h3 class="section-subtitle"><?php echo esc_html( $options['team_subtitle'] ); ?></h3>
                        <?php endif; ?>
                </div><!-- .section-header -->
                <div class="section-content clear col-4">
                    <?php 
                    $i = 1;
                    foreach ( $content_details as $content ) : ?>
                        <article>
                            <?php if (!empty($content['img'])): ?>
                                <div class="featured-image">
                                    <img src="<?php echo esc_url( $content['img'] ); ?>" alt="<?php echo esc_attr( $content['title'] ) ?>">
                                </div><!-- .featured-image -->
                            <?php endif ?>

                            <div class="entry-container">
                                <header class="entry-header">
                                    <?php if ( ! empty( $content['title'] ) ) : ?>
                                        <h2 class="entry-title"><a href="<?php echo esc_url( $content['url'] ) ; ?>"><?php echo esc_html( $content['title'] ) ; ?></a></h2> 
                                    <?php endif; ?>  
                                </header>
                                <?php if ( ! empty( $options['team_content_custom_position_' . $i ] ) ) : ?>
                                        <div class="entry-content">
                                            <p><?php echo esc_html( $options['team_content_custom_position_' . $i ] ); ?></p>
                                        </div>
                                    <?php endif; ?>

                                <div class="social-icons">
                                    <ul>
                                        <?php 
                                        $team_socials = ! empty( $options['team_social_' . $i ] ) ? explode( '|', $options['team_social_' . $i ] ) : array(); 

                                        foreach ( $team_socials as $team_social ) : ?>
                                            <li>
                                                <a href="<?php echo esc_url( $team_social ); ?>"><?php echo farmerpress_return_social_icon( $team_social ); ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </article>
                    <?php 
                    $i++;
                    endforeach; ?>
                </div><!-- .section-content -->
            </div><!-- .wrapper -->
        </div><!-- #featured-team -->
            
    <?php }
endif;