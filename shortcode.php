<?php
/**
 * Plugin Name: Cupid Shortcode
 * Plugin URI: https://github.com/vhchung/cupid-shortcode
 * Description: This is plugin that create shortcode of theme (http://themes.g5plus.net/cupid/)
 * Version: 1.1
 * Author: chungvh
 * Author URI: https://chungvh.com
 * License: GPLv2 or later
 */
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(! function_exists('g5plus_getCSSAnimation')){
    function g5plus_getCSSAnimation( $css_animation ) {
        $output = '';
        if ( $css_animation != '' ) {
            wp_enqueue_script( 'waypoints' );
            $output = ' wpb_animate_when_almost_visible g5plus-css-animation wpb_' . $css_animation;
        }
        return $output;
    }
}
if(! function_exists('g5plus_getStyleAnimation')){
    function g5plus_getStyleAnimation( $duration,$delay ) {
        $duration=esc_attr($duration);
        $delay=esc_attr($delay);
        $styles = array();
        if ( $duration != '0' && ! empty( $duration ) ) {
            $duration = (float)trim( $duration, "\n\ts" );
            $styles[] = "-webkit-animation-duration: {$duration}s";
            $styles[] = "-moz-animation-duration: {$duration}s";
            $styles[] = "-ms-animation-duration: {$duration}s";
            $styles[] = "-o-animation-duration: {$duration}s";
            $styles[] = "animation-duration: {$duration}s";
        }
        if ( $delay != '0' && ! empty( $delay ) ) {
            $delay = (float)trim( $delay, "\n\ts" );
            $styles[] = "opacity: 0";
            $styles[] = "-webkit-animation-delay: {$delay}s";
            $styles[] = "-moz-animation-delay: {$delay}s";
            $styles[] = "-ms-animation-delay: {$delay}s";
            $styles[] = "-o-animation-delay: {$delay}s";
            $styles[] = "animation-delay: {$delay}s";
        }
        if (count($styles) > 1) {
            return 'style="'. implode( ';', $styles ).'"';
        }
        return implode( ';', $styles );
    }
}

if(!class_exists('Cupid_Shortcode')){
    class Cupid_Shortcode{
        function __construct(){
            add_action('init', array($this, 'includes'),0);
            add_action('init', array($this,'register_vc_map'),10);
        }
        function includes() {
            $dir = plugin_dir_path( __FILE__ );
            include_once($dir.'vc-extend/vc-extend.php');
            include_once($dir.'heading/heading.php');
            include_once($dir.'call-action/call-action.php');
            include_once($dir.'button/button.php');
            include_once($dir.'counter/counter.php');
            include_once($dir.'quote/quote.php');
            include_once($dir.'services/services.php');
            include_once($dir.'contact-info/contact-info.php');
            include_once($dir.'introduction/introduction.php');
            include_once($dir.'icon-box/icon-box.php');
            include_once($dir.'our-staffs/our-staffs.php');
            include_once($dir.'advisory-board/advisory-board.php');
            include_once($dir.'gallery/gallery.php');
            include_once($dir.'classes/classes.php');
            include_once($dir.'count-down/countdown.php');
            include_once($dir.'latest-post/latest-post.php');
        }
        function register_vc_map()
        {
            if ( function_exists( 'vc_map' ) ) {
                $add_css_animation = array(
                    'type' => 'dropdown',
                    'heading' => __( 'CSS Animation', 'cupid' ),
                    'param_name' => 'css_animation',
                    'admin_label' => true,
                    'value' => array(
                        __( 'No', 'cupid' ) => '',
                        __( 'Top to bottom', 'cupid' ) => 'top-to-bottom',
                        __( 'Bottom to top', 'cupid' ) => 'bottom-to-top',
                        __( 'Left to right', 'cupid' ) => 'left-to-right',
                        __( 'Right to left', 'cupid' ) => 'right-to-left',
                        __( 'Appear from center', 'cupid' ) => 'appear',
                        __( 'FadeIn', 'cupid' ) => 'fadein'
                    ),
                    'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
                );
                $add_duration_animation= array(
                    'type' => 'textfield',
                    'heading' => __( 'Animation Duration', 'cupid' ),
                    'param_name' => 'duration',
                    'value' => '',
                    'description' => __( 'Duration in seconds. You can use decimal points in the value. Use this field to specify the amount of time the animation plays. <em>The default value depends on the animation, leave blank to use the default.</em>', 'cupid' ),
                    'dependency'  => Array( 'element' => 'css_animation', 'value' => array( 'top-to-bottom','bottom-to-top','left-to-right','right-to-left','appear','fadein') ),
                );
                $add_delay_animation=array(
                    'type' => 'textfield',
                    'heading' => __( 'Animation Delay', 'cupid' ),
                    'param_name' => 'delay',
                    'value' => '',
                    'description' => __( 'Delay in seconds. You can use decimal points in the value. Use this field to delay the animation for a few seconds, this is helpful if you want to chain different effects one after another above the fold.', 'cupid' ),
                    'dependency'  => Array( 'element' => 'css_animation', 'value' => array( 'top-to-bottom','bottom-to-top','left-to-right','right-to-left','appear','fadein') ),
                );
                $add_el_class = array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Extra class name', 'cupid' ),
                    'param_name'  => 'el_class',
                    'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'cupid' ),
                );
                vc_map( array(
                    'name'     => __( 'Headings', 'cupid' ),
                    'base'     => 'cupid_heading',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Title', 'cupid' ),
                            'param_name' => 'title',
                            'value'      => '',
                            'admin_label' => true,
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Description', 'cupid' ),
                            'param_name'  => 'description',
                            'value'       => '',
                            'description' => __( 'Provide the description for this heading', 'cupid' )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Call To Action', 'cupid' ),
                    'base'     => 'cupid_call_action',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Text', 'cupid' ),
                            'param_name' => 'text',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Button Label', 'cupid' ),
                            'param_name' => 'button_label',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Link (url)', 'cupid' ),
                            'param_name' => 'link',
                            'value'      => '',
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __( 'Open link in a new window/tab', 'cupid' ),
                            'param_name' => 'link_target',
                            'value' => array( __( 'Yes, please', 'cupid' ) => 'yes' )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Button', 'cupid' ),
                    'base'     => 'cupid_button',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Button Label', 'cupid' ),
                            'param_name' => 'button_label',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Link (url)', 'cupid' ),
                            'param_name' => 'link',
                            'value'      => '',
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __( 'Open link in a new window/tab', 'cupid' ),
                            'param_name' => 'link_target',
                            'value' => array( __( 'Yes, please', 'cupid' ) => 'yes' )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Counter', 'cupid' ),
                    'base'     => 'cupid_counter',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Value', 'cupid' ),
                            'param_name' => 'value',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Title', 'cupid' ),
                            'param_name' => 'title',
                            'value'      => '',
                        ),
                        $add_el_class
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Gallery', 'cupid' ),
                    'base'     => 'cupid_gallery',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            "type"        => "dropdown",
                            "heading"     => __( "Number of column", "cupid" ),
                            "param_name"  => "column",
                            "value"       => array( '3' => '3', '4' => '4')
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => __( "Number of item per page. Display all if number of item empty", "cupid" ),
                            "param_name"  => "item",
                            "value"       => ''
                        ),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => __( 'Load more', 'cupid' ),
                            'param_name' => 'load_more',
                            'value'      => array( 'No' => '0','Yes' => '1')
                        ),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => __( 'Load more style', 'cupid' ),
                            'param_name' => 'load_more_style',
                            'value' => array( 'Scroll' => 'scroll','Load more button' => 'load-more-button'),
                            'dependency'  => Array( 'element' => 'load_more', 'value' => array( '1') )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Classes', 'cupid' ),
                    'base'     => 'cupid_classes',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            "type"        => "dropdown",
                            "heading"     => __( "Number of column", "cupid" ),
                            "param_name"  => "column",
                            "value"       => array( '3' => '3', '4' => '4')
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => __( "Number of item (or number of item per page if choose show paging)", "cupid" ),
                            "param_name"  => "item",
                            "value"       => ''
                        ),
                        array(
                            'type'       => 'dropdown',
                            'heading'    => __( 'Show paging', 'cupid' ),
                            'param_name' => 'show_paging',
                            'value'      => array( 'No' => '0','Yes' => '1'),
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    "name"     => __( "Countdown", "cupid" ),
                    "base"     => "cupid_countdown",
                    "class"    => "",
                    "icon"     => "icon-wpb-title",
                    "category" => __( 'Cupid Shortcodes', 'cupid' ),
                    "params"   => array(
                        array(
                            "type"        => "dropdown",
                            "heading"     => __( "Countdown Type", "cupid" ),
                            "param_name"  => "type",
                            "admin_label" => true,
                            "value"       => array( __('Coming Soon','cupid') => 'comming-soon', __('Under Construction','cupid') => 'under-construction')
                        ),
                        array(
                            "type"        => "textfield",
                            "heading"     => __( "Extra class name", "cupid" ),
                            "param_name"  => "css",
                            "value"       => '',
                            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "cupid" )
                        ),
                    )
                ));
                vc_map( array(
                    'name'     => __( 'Quote', 'cupid' ),
                    'base'     => 'cupid_quote',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Text', 'cupid' ),
                            'param_name' => 'text',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Sub Text', 'cupid' ),
                            'param_name' => 'subtext',
                            'value'      => '',
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Services', 'cupid' ),
                    'base'     => 'cupid_services',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Title', 'cupid' ),
                            'param_name' => 'title',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Description', 'cupid' ),
                            'param_name' => 'description',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Link (url)', 'cupid' ),
                            'param_name' => 'link',
                            'value'      => '',
                        ),
                        array(
                            'type' => 'checkbox',
                            'heading' => __( 'Open link in a new window/tab', 'cupid' ),
                            'param_name' => 'link_target',
                            'value' => array( __( 'Yes, please', 'cupid' ) => 'yes' )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Contact Info', 'cupid' ),
                    'base'     => 'cupid_contact_info',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'        => 'dropdown',
                            'heading'     => __( 'Type', 'cupid' ),
                            'param_name'  => 'type',
                            'admin_label' => true,
                            'value'       => array( __( 'Address', 'cupid' ) => 'address', __( 'Phone', 'cupid' ) => 'phone', __( 'Email', 'cupid' ) => 'email'),
                            'description' => __( 'Select type contact.', 'cupid' )
                        ),
                        array(
                            'type'        => 'icon_text',
                            'heading'     => __( 'Select Icon:', 'cupid' ),
                            'param_name'  => 'icon',
                            'value'       => '',
                            'description' => __( 'Select the icon from the list.', 'cupid' ),
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Title', 'cupid' ),
                            'param_name' => 'title',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Address', 'cupid' ),
                            'param_name' => 'address',
                            'value'      => '',
                            'dependency'  => Array( 'element' => 'type', 'value' => array( 'address' ) )
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Phone', 'cupid' ),
                            'param_name' => 'phone',
                            'value'      => '',
                            'dependency'  => Array( 'element' => 'type', 'value' => array( 'phone' ) )
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Mobile', 'cupid' ),
                            'param_name' => 'mobile',
                            'value'      => '',
                            'dependency'  => Array( 'element' => 'type', 'value' => array( 'phone' ) )
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Email', 'cupid' ),
                            'param_name' => 'email',
                            'value'      => '',
                            'dependency'  => Array( 'element' => 'type', 'value' => array( 'email' ) )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Introduction', 'cupid' ),
                    'base'     => 'cupid_introduction',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Text', 'cupid' ),
                            'param_name' => 'text',
                            'value'      => '',
                        ),
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Description', 'cupid' ),
                            'param_name' => 'description',
                            'value'      => '',
                        ),
                        array(
                            'type'        => 'dropdown',
                            'heading'     => __( 'Text Align', 'cupid' ),
                            'param_name'  => 'text_align',
                            'admin_label' => true,
                            'value'       => array( __( 'Left', 'cupid' ) => 'left', __( 'Right', 'cupid' ) => 'right', __( 'Center', 'cupid' ) => 'center'),
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
                vc_map( array(
                    'name'     => __( 'Our Staffs', 'cupid' ),
                    'base'     => 'cupid_our_staffs',
                    'class'    => '',
                    'icon'     => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'params'   => array(
                        array(
                            'type'        => 'dropdown',
                            'heading'     => __( 'Number Column', 'cupid' ),
                            'param_name'  => 'column',
                            'admin_label' => true,
                            'value'       => array('1' => 1,'2' => 2,'3' => 3,'4' => 4,'5' => 5),
                            'std'   => '3',
                        ),
                        array(
                            'type'       => 'textfield',
                            'heading'    => __( 'Item Amount', 'cupid' ),
                            'param_name' => 'item_amount',
                            'value'      => '10',
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ) );
	            vc_map( array(
		            'name'     => __( 'Advisory Board', 'cupid' ),
		            'base'     => 'cupid_advisory_board',
		            'class'    => '',
		            'icon'     => 'icon-wpb-title',
		            'category' => __( 'Cupid Shortcodes', 'cupid' ),
		            'params'   => array(
			            array(
				            'type'        => 'dropdown',
				            'heading'     => __( 'Number Column', 'cupid' ),
				            'param_name'  => 'column',
				            'admin_label' => true,
				            'value'       => array('1' => 1,'2' => 2,'3' => 3,'4' => 4,'5' => 5),
				            'std'   => '3',
			            ),
			            array(
				            'type'       => 'textfield',
				            'heading'    => __( 'Item Amount', 'cupid' ),
				            'param_name' => 'item_amount',
				            'value'      => '10',
			            ),
			            $add_el_class,
			            $add_css_animation,
			            $add_duration_animation,
			            $add_delay_animation
		            )
	            ) );
                vc_map( array(
                    'name' => __( 'Latest Posts', 'cupid' ),
                    'base' => 'cupid_latest_post',
                    'icon' => 'icon-wpb-title',
                    'category' => __( 'Cupid Shortcodes', 'cupid' ),
                    'description' => __( 'Latest Posts', 'cupid' ),
                    'params' => array(
                        array(
                            "type"        => "dropdown",
                            "heading"     => __( "Layout Style", 'cupid' ),
                            "param_name"  => "layout_style",
                            "admin_label" => true,
                            "value"       => array( __( "style 1", 'cupid' ) => "style1", __( "style 2", 'cupid' ) => "style2"),
                            "description" => __( "Select Layout Style.", 'cupid' )
                        ),
                        array(
                            "type"       => "textfield",
                            "heading"    => __( "Item Amount", 'cupid' ),
                            "param_name" => "item_amount",
                            "value"      => "10"
                        ),
                        array(
                            "type"        => "checkbox",
                            "heading"     => __( "Slider Style", 'cupid' ),
                            "param_name"  => "is_slider",
                            "admin_label" => false,
                            "value" => array( __( "Yes, please", 'cupid' ) => "yes" )
                        ),
                        $add_el_class,
                        $add_css_animation,
                        $add_duration_animation,
                        $add_delay_animation
                    )
                ));
                vc_map(
                    array(
                        'name'                    => __( 'Icon Box', 'cupid' ),
                        'base'                    => 'cupid_icon_box',
                        'icon'                    => 'icon-wpb-title',
                        'category' => __( 'Cupid Shortcodes', 'cupid' ),
                        'description'             => 'Adds icon box with font icons',
                        'params'                  => array(
                            array(
                                'type'        => 'dropdown',
                                'heading'     => __( 'Layout Style', 'cupid' ),
                                'param_name'  => 'layout_style',
                                'admin_label' => true,
                                'value'       => array( __( 'style 1', 'cupid' ) => 'style1', __( 'style 2', 'cupid' ) => 'style2'),
                                'description' => __( 'Select Layout Style.', 'cupid' )
                            ),
                            array(
                                'type'        => 'icon_text',
                                'heading'     => __( 'Select Icon:', 'cupid' ),
                                'param_name'  => 'icon',
                                'value'       => '',
                                'description' => __( 'Select the icon from the list.', 'cupid' ),
                            ),
                            array(
                                'type'        => 'colorpicker',
                                'heading'     => __( 'Icon color', 'cupid' ),
                                'param_name'  => 'icon_color',
                                'value'       => '',
                            ),
                            array(
                                'type'        => 'dropdown',
                                'heading'     => __( 'Icon position', 'cupid' ),
                                'param_name'  => 'icon_position',
                                'admin_label' => true,
                                'value'       => array( __( 'left', 'cupid' ) => 'left', __( 'right', 'cupid' ) => 'right'),
                                'dependency'  => Array( 'element' => 'layout_style', 'value' => array( 'style2' ) )
                            ),
                            array(
                                'type'       => 'textfield',
                                'heading'    => __( 'Link (url)', 'cupid' ),
                                'param_name' => 'link',
                                'value'      => '#',
                            ),
                            array(
                                'type'        => 'textfield',
                                'heading'     => __( 'Title', 'cupid' ),
                                'param_name'  => 'title',
                                'value'       => __( 'This is an icon box.', 'cupid' ),
                                'description' => __( 'Provide the title for this icon box.', 'cupid' ),
                            ),
                            array(
                                'type'        => 'textarea',
                                'heading'     => __( 'Description', 'cupid' ),
                                'param_name'  => 'description',
                                'value'       => __( 'Write a short description, that will describe the title or something informational and useful.', 'cupid' ),
                                'description' => __( 'Provide the description for this icon box.', 'cupid' ),
                                'dependency'  => Array( 'element' => 'layout_style', 'value' => array( 'style1' ) )
                            ),
                            $add_el_class,
                            $add_css_animation,
                            $add_duration_animation,
                            $add_delay_animation
                        )
                    )
                ); // end vc_map                               
            }
        }
    }
    new Cupid_Shortcode;
}