<?php
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Countdown')){
    class Cupid_Countdown {
        function __construct() {
            add_action( 'init', array($this, 'register_post_types' ), 6 );
            add_shortcode('cupid_countdown', array($this, 'cupid_countdown_shortcode' ));
            add_filter( 'rwmb_meta_boxes', array($this,'cupid_register_meta_boxes' ));
        }

        function register_post_types() {
            if ( post_type_exists('cupid_countdown') ) {
                return;
            }
            register_post_type('cupid_countdown',
                array(
                    'label' => __('Countdown','cupid'),
                    'description' => __( 'Countdown Description', 'cupid' ),
                    'labels' => array(
                        'name'					=>'Countdown',
                        'singular_name' 		=> 'Countdown',
                        'menu_name'    			=> __( 'Countdown', 'cupid' ),
                        'parent_item_colon'  	=> __( 'Parent Item:', 'cupid' ),
                        'all_items'          	=> __( 'All Countdown', 'cupid' ),
                        'view_item'          	=> __( 'View Item', 'cupid' ),
                        'add_new_item'       	=> __( 'Add New Countdown', 'cupid' ),
                        'add_new'            	=> __( 'Add New', 'cupid' ),
                        'edit_item'          	=> __( 'Edit Item', 'cupid' ),
                        'update_item'        	=> __( 'Update Item', 'cupid' ),
                        'search_items'       	=> __( 'Search Item', 'cupid' ),
                        'not_found'          	=> __( 'Not found', 'cupid' ),
                        'not_found_in_trash' 	=> __( 'Not found in Trash', 'cupid' ),
                    ),
                    'menu_icon' => 'dashicons-backup',
                    'supports'    => array( 'title', 'editor', 'comments', 'thumbnail'),
                    'public'      => true,
                    'has_archive' => true
                )
            );
        }

        function cupid_countdown_shortcode($atts){
            $type = $css = '';
            extract( shortcode_atts( array(
                'css'      => '',
                'type' => ''
            ), $atts ) );

            $plugin_path =  untrailingslashit( plugin_dir_path( __FILE__ ) );
            $template_path = $plugin_path . '/templates/under-construction.php';

            ob_start();
            include($template_path);
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        function cupid_register_meta_boxes($meta_boxes){
            $meta_boxes[] = array(
                'title'  => __( 'Countdown Option', 'cupid' ),
                'id'     => 'cupid-meta-box-countdown-opening',
                'pages'  => array( 'cupid_countdown' ),
                'fields' => array(
                    array(
                        'name' => __( 'Type', 'cupid' ),
                        'id'   => 'countdown-type',
                        'type' => 'select',
                        'options'  => array(
                            'comming-soon' => __('Coming Soon','cupid'),
                            'under-construction' => __('Under Construction','cupid')
                        )
                    ),
                    array(
                        'name' => __( 'Opening hours', 'cupid' ),
                        'id'   => 'countdown-opening',
                        'type' => 'datetime',
                    ),
                    array(
                        'name' => __( 'Url redirect (after countdown completed)', 'cupid' ),
                        'id'   => 'countdown-url',
                        'type' => 'textarea',
                    )
                )
            );
            return $meta_boxes;
        }

    }
    new Cupid_Countdown();
}