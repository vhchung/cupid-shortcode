<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 3/4/15
 * Time: 2:41 PM
 */
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if (!class_exists('Cupid_Latest_Post')):
    class Cupid_Latest_Post {
        function __construct(){
            add_shortcode('cupid_latest_post', array($this, 'cupid_latest_post'));
        }

        function cupid_latest_post($atts) {
            $layout_style =  $item_amount = $is_slider =  $html = $el_class = $g5plus_animation = $css_animation  =$duration = $delay = $styles_animation = '';
            extract( shortcode_atts( array(
                'layout_style'  => 'style1',
                'item_amount'   => '10',
                'is_slider' => false ,
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );

            global $cupid_archive_loop;

            $column = 3;
            if ($layout_style == 'style2') {
                $column = 2;
                $cupid_archive_loop['image-size'] = 'cupid_square_thumbnail';
            }

            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );


            $r = new WP_Query( apply_filters( 'shortcode_related_args', array(
                'posts_per_page'      => $item_amount,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'post',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'post_format',
                        'field' => 'slug',
                        'terms' => array('post-format-quote','post-format-link','post-format-audio'),
                        'operator' => 'NOT IN'
                    )
                )
            )));
            ob_start();
            if ($r->have_posts()) :
            ?>
                <div class="latest-post-wrapper">
                    <div class="container">
                        <div class="row">
                                <?php if  ($is_slider) : ?>
                                <div  class="latest-post-<?php echo esc_attr($layout_style) ?> latest-post-slider <?php echo esc_attr($g5plus_animation) ?>" <?php echo g5plus_getStyleAnimation($duration,$delay); ?>>
                                    <div data-plugin-options='{"items" : <?php echo esc_attr($column) ?>,"pagination":false }' class="owl-carousel">
                                 <?php else: ?>
                                        <div  class="latest-post-col-<?php echo esc_attr($column); ?> latest-post-no-slider latest-post-<?php echo esc_attr($layout_style) ?> <?php echo esc_attr($g5plus_animation) ?>" <?php echo g5plus_getStyleAnimation($duration,$delay); ?>>
                                <?php endif; ?>
                                    <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                                        <?php get_template_part('content'); ?>
                                    <?php endwhile; ?>

                                <?php if  ($is_slider) : ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </div>
                    </div>
                </div>
            <?php
            endif;
            wp_reset_postdata();
            cupid_archive_loop_reset();
            $content = ob_get_clean();
            return $content;
        }
    }
    new Cupid_Latest_Post;
endif;