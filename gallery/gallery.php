<?php
/**
 * Created by PhpStorm.
 * User: phuongth
 * Date: 2/26/15
 * Time: 2:58 PM
 */

if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Gallery')){
    class Cupid_Gallery {
        function __construct() {
            add_action('wp_enqueue_scripts',array($this,'cupid_gallery_ajax_url'),1);
            add_action('wp_enqueue_scripts',array($this,'front_scripts'),11);
            add_action( 'init', array($this, 'register_post_types' ), 6 );
            add_shortcode('cupid_gallery', array($this, 'cupid_gallery_shortcode' ));
            add_filter( 'rwmb_meta_boxes', array($this,'cupid_register_meta_boxes' ));
            add_action( 'after_setup_theme', array($this,'register_image_size'));
        }

        function register_post_types() {
            if ( post_type_exists('cupid_gallery') ) {
                return;
            }
            register_post_type('cupid_gallery',
                array(
                    'label' => __('Cupid Gallery','cupid'),
                    'description' => __( 'Gallery Description', 'cupid' ),
                    'labels' => array(
                        'name'					=>'Gallery',
                        'singular_name' 		=> 'Gallery',
                        'menu_name'    			=> __( 'Gallery', 'cupid' ),
                        'parent_item_colon'  	=> __( 'Parent Item:', 'cupid' ),
                        'all_items'          	=> __( 'All Gallery', 'cupid' ),
                        'view_item'          	=> __( 'View Item', 'cupid' ),
                        'add_new_item'       	=> __( 'Add New Gallery', 'cupid' ),
                        'add_new'            	=> __( 'Add New', 'cupid' ),
                        'edit_item'          	=> __( 'Edit Item', 'cupid' ),
                        'update_item'        	=> __( 'Update Item', 'cupid' ),
                        'search_items'       	=> __( 'Search Item', 'cupid' ),
                        'not_found'          	=> __( 'Not found', 'cupid' ),
                        'not_found_in_trash' 	=> __( 'Not found in Trash', 'cupid' ),
                    ),
                    'supports'    => array( 'title', 'excerpt', 'thumbnail'),
                    'public'      => true,
                    'has_archive' => true
                )
            );
        }

        function cupid_gallery_shortcode($atts){
            $g5plus_animation = $current_page = $load_more = $load_more_style = $column = $duration = $delay = $item = $css_animation = $el_class = '';
            extract( shortcode_atts( array(
                'load_more'   => '0',
                'load_more_style' => 'load-more-button',
                'column'  => '3',
                'item' => '9',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => '',
                'el_class'      => '',
                'current_page' => '1'
            ), $atts ) );

            $post_per_page = -1;
            if($item!='' || !isset($item))
               $post_per_page = $item;

            $offset = ($current_page-1) * $item;

            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);


            $plugin_path =  untrailingslashit( plugin_dir_path( __FILE__ ) );
            $template_path = $plugin_path . '/templates/gallery_template.php';
            ob_start();
            include($template_path);
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        function cupid_register_meta_boxes($meta_boxes){

            $args = array(
                'nopaging' => true,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => 'cupid_classes',
                'post_status'      => 'publish');
            $query  = new WP_Query( $args );
            $posts = $query->get_posts();
            $list_class = array('' => 'None', );
            foreach($posts as $post) {
                if($post->post_name!='' && $post->post_title!='')
                    $list_class[$post->post_name] = $post->post_title;
            }
            wp_reset_postdata();


            $meta_boxes[] = array(
                'title'  => __( 'Gallery Extra', 'cupid' ),
                'id'     => 'cupid-meta-box-format-gallery',
                'pages'  => array( 'cupid_gallery' ),
                'fields' => array(
                    array(
                        'name' => __( 'Class', 'cupid' ),
                        'id'   => 'gallery-class',
                        'type' => 'select',
                        'options' => $list_class,
                        'multiple'    => false,
                        'std'         => 'none'
                    ),
                    array(
                        'name' => __( 'Gallery', 'cupid' ),
                        'id'   => 'cupid-format-gallery',
                        'type' => 'image_advanced',
                    )
                )
            );
            return $meta_boxes;
        }

        function register_image_size(){
            add_image_size( 'thumbnail-480x320', 480, 320, true );
            add_image_size( 'thumbnail-639x380', 639, 380, true );
        }

        function front_scripts(){
            $min_suffix = defined( 'CUPID_SCRIPT_DEBUG' ) && CUPID_SCRIPT_DEBUG ? '' : '.min';
            wp_enqueue_style( 'cupid-pretty-css', plugins_url() . '/cupid-shortcode/gallery/assets/plugins/jquery.prettyPhoto/css/prettyPhoto.css', array() );
            wp_enqueue_script('cupid-pretty-js',plugins_url() . '/cupid-shortcode/gallery/assets/plugins/jquery.prettyPhoto/js/jquery.prettyPhoto.js',array(), false, true);
            wp_enqueue_script('cupid-gallery-js',plugins_url() . '/cupid-shortcode/gallery/assets/js/gallery'.$min_suffix.'.js',array(), false, true);
        }
        function cupid_gallery_ajax_url(){
            echo '<script type="text/javascript">
				    var cupid_gallery_ajax_url="'. trailingslashit( get_template_directory_uri() ) .'";
		        </script>';
        }
    }
    new Cupid_Gallery();
}