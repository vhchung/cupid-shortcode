<?php
/**
 * Plugin Name: Cupid Advisory Board
 * Plugin URI: http://g5plus.net
 * Description: This is plugin that create our team post type
 * Version: 1.0
 * Author: g5plus
 * Author URI: http://g5plus.net
 * License: GPLv2 or later
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Advisory_Board')){
    class Cupid_Advisory_Board{
        function __construct(){
            add_action('init', array($this,'register_post_types'), 5 );
            add_shortcode('cupid_advisory_board', array($this, 'cupid_advisory_board_shortcode'));
            add_filter( 'rwmb_meta_boxes', array($this,'cupid_register_meta_boxes' ));
            add_action('admin_menu', array($this, 'addMenuChangeSlug'));
        }

        function cupid_register_meta_boxes( $meta_boxes )
        {
            $meta_boxes[] = array(
                'title'  => __( 'Advisory Board Information', 'cupid' ),
                'pages'  => array( 'advisory-board' ),
                'fields' => array(
                    array(
                        'name' => __( 'Job', 'cupid' ),
                        'id'   => 'job',
                        'type' => 'text',
                    ),
                    array(
                        'name' => __( 'Facebook URL', 'cupid' ),
                        'id'   => 'face_url',
                        'type' => 'url',
                    ),
                    array(
                        'name' => __( 'Twitter URL', 'cupid' ),
                        'id'   => 'twitter_url',
                        'type' => 'url',
                    ),
                    array(
                        'name' => __( 'Google URL', 'cupid' ),
                        'id'   => 'google_url',
                        'type' => 'url',
                    ),
                    array(
                        'name' => __( 'Linked In URL', 'cupid' ),
                        'id'   => 'linkedin_url',
                        'type' => 'url',
                    ),
                    array(
                        'name' => __( 'Phone', 'cupid' ),
                        'id'   => 'phone',
                        'type' => 'text',
                    ),
                    array(
                        'name' => __( 'Email', 'cupid' ),
                        'id'   => 'email',
                        'type' => 'text',
                    ),
                )
            );
            return $meta_boxes;
        }
        function register_post_types() {
            $post_tyte = 'advisory-board';
            if (post_type_exists($post_tyte)) {
                return;
            }
            $post_type_slug =  get_option('cupid-'.$post_tyte.'-config');
            if(!isset($post_type_slug) || !is_array($post_type_slug)){
                $slug = 'advisory-board';
                $name = $singular_name = 'Advisory Board';
            }else{
                $slug = $post_type_slug['slug'];
                $name = $post_type_slug['name'];
                $singular_name = $post_type_slug['singular_name'];
            }

            register_post_type($post_tyte,
                array(
                    'label' => esc_attr__('Advisory Board', 'cupid'),
                    'description' => esc_attr__('Advisory Board Description', 'cupid'),
                    'labels' => array(
                        'name' => $name,
                        'singular_name' => $singular_name,
                        'menu_name' => sprintf(esc_attr__('%s','cupid'),$name),
                        'parent_item_colon' => esc_attr__('Parent Item:', 'cupid'),
                        'all_items' => sprintf(esc_attr__('All %s','cupid'),$name ),
                        'view_item' => esc_attr__('View Item', 'cupid'),
                        'add_new_item' => sprintf(esc_attr__('Add New %s','cupid'),$name),
                        'add_new' => esc_attr__('Add New', 'cupid'),
                        'edit_item' => esc_attr__('Edit Item', 'cupid'),
                        'update_item' => esc_attr__('Update Item', 'cupid'),
                        'search_items' => esc_attr__('Search Item', 'cupid'),
                        'not_found' => esc_attr__('Not found', 'cupid'),
                        'not_found_in_trash' => esc_attr__('Not found in Trash', 'cupid'),
                    ),
                    'rewrite' => array('slug' => $slug, 'with_front' => true),
                    'supports'    => array( 'title','editor', 'thumbnail'),
                    'public' => true,
                    'has_archive' => true,
                    'query_var' => true
                )
            );
            flush_rewrite_rules();
        }
        function addMenuChangeSlug()
        {
            add_submenu_page('edit.php?post_type=advisory-board', 'Setting', 'Settings', 'edit_posts', wp_basename(__FILE__), array($this, 'initPageSettings'));
        }

        function initPageSettings()
        {
            $template_path = ABSPATH  . 'wp-content/plugins/cupid-shortcode/posttype-settings/settings.php';
            if(file_exists($template_path))
                require_once $template_path;
        }
        function cupid_advisory_board_shortcode($atts){
            $item_amount = $column = $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'column'        => '3',
                'item_amount'   => '10',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );

            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $args    = array(
                'posts_per_page'   	=> $item_amount,
                'post_type'      => 'advisory-board',
                'orderby'   => 'date',
                'order'     => 'ASC',
                'post_status'      	=> 'publish'
            );
            $data = new WP_Query( $args );
            if ( $data->have_posts() ) {
	            $html .= '<div class="cupid-our-staffs ' . $g5plus_animation . '" '.$styles_animation.'>
                           <div class="row">
                            <div class="owl-carousel" data-plugin-options=\'{"items" : '.$column.',"itemsDesktop" : [1199,3],"pagination": false, "autoPlay": true}\'>';
	            while ( $data->have_posts() ): $data->the_post();
		            $job   = get_post_meta(get_the_ID(), 'job', true);
		            $image_id  = get_post_thumbnail_id();
		            $image_url = wp_get_attachment_image( $image_id, 'full  img-circle', false, array( 'alt' => get_the_title(), 'title' => get_the_title()));
		            $html .= '<div class="our-staffs-item">
                                <div class="our-staffs-image"><a href="' . get_permalink() . '" title="' . get_the_title() . '" >'.wp_kses_post($image_url).'</a></div>
                                <a class="our-staffs-name" href="' . get_permalink() . '" title="' . get_the_title() . '" >' . get_the_title() . '</a>
                                <p>'.esc_html($job).'</p>';
		            $html .= '</div>';
	            endwhile;
	            $html .= '</div>
                        </div>
                     </div>';
            }
            wp_reset_postdata();
            return $html;
        }
    }
    new Cupid_Advisory_Board;
}