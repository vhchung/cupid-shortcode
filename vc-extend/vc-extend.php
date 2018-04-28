<?php
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(! function_exists('g5plus_hex_to_rgba')){
    function g5plus_hex_to_rgba($hex,$opacity=1) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        }
        else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgba = 'rgba('.$r.','.$g.','.$b.','.$opacity.')';
        return $rgba;
    }
}
if(! function_exists('g5plus_vc_getExtraClass')){
    function g5plus_vc_getExtraClass( $el_class ) {
        $output = '';
        if ( $el_class != '' ) {
            $output = " " . str_replace( ".", "", $el_class );
        }
        return $output;
    }
}
if(! function_exists('g5plus_vc_buildStyle')){
    function g5plus_vc_buildStyle( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $margin_bottom = '' ) {
        $has_image = false;
        $style = '';
        if ( (int)$bg_image > 0 && ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) !== false ) {
            $has_image = true;
            $style .= "background-image: url(" . $image_url . ");";
        }
        if ( ! empty( $bg_color ) ) {
            $style .= vc_get_css_color( 'background-color', $bg_color );
        }
        if ( ! empty( $bg_image_repeat ) && $has_image ) {
            if ( $bg_image_repeat === 'cover' ) {
                $style .= "background-repeat:no-repeat;background-size: cover;";
            } elseif ( $bg_image_repeat === 'contain' ) {
                $style .= "background-repeat:no-repeat;background-size: contain;";
            } elseif ( $bg_image_repeat === 'no-repeat' ) {
                $style .= 'background-repeat: no-repeat;';
            }
        }
        if ( ! empty( $font_color ) ) {
            $style .= vc_get_css_color( 'color', $font_color ); // 'color: '.$font_color.';';
        }
        if ( $padding != '' ) {
            $style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
        }
        if ( $margin_bottom != '' ) {
            $style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
        }
        return empty( $style ) ? $style : ' style="' . $style . '"';
    }
}
if(!class_exists('Cupid_VC_Extend')){
    class Cupid_VC_Extend{
        function __construct(){
            add_action('init', array($this, 'init' ),1);
            add_action('wp_enqueue_scripts',array($this,'front_scripts'),1);
            add_action('vc_before_init',array($this,'add_vc_param'));
        }
        function init()
        {
            if ( function_exists('add_shortcode_param'))
            {
                add_shortcode_param('number' , array(&$this, 'number_settings_field' ) );
                add_shortcode_param('icon_text' , array(&$this, 'icon_text_settings_field' ) );
            }
        }
        function number_settings_field($settings, $value)
        {
            $dependency = vc_generate_dependencies_attributes($settings);
            $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
            $type = isset($settings['type']) ? $settings['type'] : '';
            $min = isset($settings['min']) ? $settings['min'] : '';
            $max = isset($settings['max']) ? $settings['max'] : '';
            $suffix = isset($settings['suffix']) ? $settings['suffix'] : '';
            $class = isset($settings['class']) ? $settings['class'] : '';
            $output = '<input type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" class="wpb_vc_param_value ' . esc_attr($param_name) . ' ' . esc_attr($type) . ' ' . esc_attr($class) . '" name="' . esc_attr($param_name) . '" value="'.esc_attr($value).'" style="max-width:100px; margin-right: 10px;" />'.esc_attr($suffix);
            return $output;
        }
        function icon_text_settings_field($settings, $value) {
            $dependency = vc_generate_dependencies_attributes( $settings );
            return '<div class="vc-text-icon">'
            .'<input  name="'.$settings['param_name'] .'" style="width:80%;" class="wpb_vc_param_value wpb-textinput widefat input-icon ' .esc_attr($settings['param_name']).' '.esc_attr($settings['type']).'_field" type="text" value="' .esc_attr($value).'" ' . $dependency . '/>'
            .'<input title="'.__('Click to browse icon','cupid').'" style="width:20%; height:34px;" class="browse-icon button-secondary" type="button" value="'. __('Browse Icon','cupid') .'" >'
            .'<span class="icon-preview"><i class="fa '. esc_attr($value).'"></i></span>'
            .'</div>';
        }
        function front_scripts(){
            $min_suffix = defined( 'CUPID_SCRIPT_DEBUG' ) && CUPID_SCRIPT_DEBUG ? '' : '.min';
            if(!function_exists('vc_map_get_attributes')) {
                wp_enqueue_script('cupid_parallax_overlay_js', plugins_url('cupid-shortcode/vc-extend/main' . $min_suffix . '.js'), array(), false, true);
            }
            wp_enqueue_style('cupid_css-animation',plugins_url('cupid-shortcode/vc-extend/animation'.$min_suffix.'.css'));
        }


        function add_vc_param(){
            if(!function_exists('vc_map_get_attributes')) {
                function cupid_vc_row_shortcode($atts, $content = null) {
                    $video_link=$css_animation = $duration = $delay=$output = $style_css = $layout = $parallax_style = $parallax_scroll_effect = $parallax_speed = $overlay_set = $overlay_color = $overlay_image = $overlay_opacity = $el_id = $el_class = $bg_image = $bg_color = $bg_image_repeat = $pos = $font_color = $padding = $margin_bottom = $css = '';
                    extract( shortcode_atts( array(
                        'el_class'        => '',
                        'el_id'           => '',
                        'bg_image'        => '',
                        'bg_color'        => '',
                        'bg_image_repeat' => '',
                        'font_color'      => '',
                        'padding'         => '',
                        'margin_bottom'   => '',
                        'css'             => '',
                        'layout'          => '',
                        'parallax_style'  => 'none',
                        'video_link'   => '',
                        'parallax_scroll_effect'  => '',
                        'parallax_speed'  => '',
                        'overlay_set'     => 'hide_overlay',
                        'overlay_color'   => '',
                        'overlay_image'   => '',
                        'overlay_opacity' => '',
                        'css_animation'   => '',
                        'duration'        => '',
                        'delay'           => ''
                    ), $atts ) );

                    //wp_enqueue_style( 'js_composer_front' );
                    wp_enqueue_script( 'wpb_composer_front_js' );
                    //wp_enqueue_style( 'js_composer_custom_css' );

                    $el_class = g5plus_vc_getExtraClass($el_class );
                    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row ' . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $atts );
#
                    $style = g5plus_vc_buildStyle( $bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);
                    /*************edit**************/

                    $str_el_id='';
                    $css_overlay_video='';
                    if($el_id!='')
                    {
                        $str_el_id='id="'.esc_attr($el_id).'"';
                    }
                    if ( $layout == 'boxed' ) {
                        $style_css='container';
                    } elseif ( $layout == 'container-fluid' ) {
                        $style_css='container-fluid';
                    }else
                    {
                        $style_css='fullwidth';
                    }
                    $output .= '<div '.$str_el_id.' class="'.$style_css . g5plus_getCSSAnimation($css_animation) .'" '.g5plus_getStyleAnimation($duration,$delay).'>';
                    if ($parallax_style != 'none' && $parallax_style != 'video-background') {
                        if($overlay_set!='hide_overlay'){
                            $css_overlay_video=' overlay-wapper';
                        }
                        $output .= '<div data-parallax_speed="'.(esc_attr($parallax_speed)/100) .'" data-scroll_effect="'.esc_attr($parallax_scroll_effect).'" class="' . esc_attr($css_class) .  ' '.esc_attr($parallax_style).$css_overlay_video.'"' . $style .'>';
                    }
                    else
                    {
                        if($overlay_set!='hide_overlay'){
                            $css_overlay_video=' overlay-wapper';
                        }
                        if ($parallax_style == 'video-background') {
                            $css_overlay_video.=' video-background-wapper';
                        }
                        $output .= '<div class="' . esc_attr($css_class) . $css_overlay_video.'"' . $style .'>';
                    }
                    if ($parallax_style == 'video-background') {
                        $output .= '<video data-top-default="0" muted="muted" loop="loop" autoplay="true" preload="auto">
                                <source src="' . esc_url($video_link) . '">
                            </video>';
                    }
                    if($overlay_set!='hide_overlay'){
                        $overlay_id='overlay-'.uniqid();
                        if($overlay_set=='show_overlay_color'){
                            $overlay_color = g5plus_hex_to_rgba(esc_attr($overlay_color),(esc_attr($overlay_opacity)/100));
                            $style_css=' data-overlay_color= '.esc_attr($overlay_color);
                        }
                        else if($overlay_set=='show_overlay_image'){
                            $image_attributes = wp_get_attachment_image_src( $overlay_image,'full' );
                            $style_css=' data-overlay_image= '.$image_attributes[0].' data-overlay_opacity='.(esc_attr($overlay_opacity)/100);
                        }
                        $output .= '<div id="'.$overlay_id.'" class="overlay" '.$style_css.'></div>';
                    }
                    $output .= wpb_js_remove_wpautop( $content );
                    $output .= '</div></div>';
                    return $output;
                }

                function cupid_vc_row_inner_shortcode($atts, $content = null) {
                    $video_link=$css_animation = $duration = $delay=$output = $style_css = $layout = $parallax_style = $parallax_scroll_effect = $parallax_speed = $overlay_set = $overlay_color = $overlay_image = $overlay_opacity = $el_id = $el_class = $bg_image = $bg_color = $bg_image_repeat = $pos = $font_color = $padding = $margin_bottom = $css = '';
                    extract( shortcode_atts( array(
                        'el_class'        => '',
                        'el_id'           => '',
                        'bg_image'        => '',
                        'bg_color'        => '',
                        'bg_image_repeat' => '',
                        'font_color'      => '',
                        'padding'         => '',
                        'margin_bottom'   => '',
                        'css'             => '',
                        'layout'          => '',
                        'parallax_style'  => 'none',
                        'video_link'   => '',
                        'parallax_scroll_effect'  => '',
                        'parallax_speed'  => '',
                        'overlay_set'     => 'hide_overlay',
                        'overlay_color'   => '',
                        'overlay_image'   => '',
                        'overlay_opacity' => '',
                        'css_animation'   => '',
                        'duration'        => '',
                        'delay'           => ''
                    ), $atts ) );

                    //wp_enqueue_style( 'js_composer_front' );
                    wp_enqueue_script( 'wpb_composer_front_js' );
                    //wp_enqueue_style( 'js_composer_custom_css' );
                    $el_class = g5plus_vc_getExtraClass( $el_class );
                    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row vc_inner ' . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $atts );
#
                    $style = g5plus_vc_buildStyle( $bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);
                    /*************edit**************/
                    $str_el_id='';
                    $css_overlay_video='';
                    if($el_id!='')
                    {
                        $str_el_id='id="'.esc_attr($el_id).'"';
                    }
                    if ( $layout == 'boxed' ) {
                        $style_css='container';
                    } elseif ( $layout == 'container-fluid' ) {
                        $style_css='container-fluid';
                    }else
                    {
                        $style_css='fullwidth';
                    }
                    $output .= '<div '.$str_el_id.' class="'.$style_css . g5plus_getCSSAnimation($css_animation) .'" '.g5plus_getStyleAnimation($duration,$delay).'>';
                    if ($parallax_style != 'none' && $parallax_style != 'video-background') {
                        if($overlay_set!='hide_overlay'){
                            $css_overlay_video=' overlay-wapper';
                        }
                        $output .= '<div data-parallax_speed="'.(esc_attr($parallax_speed)/100) .'" data-scroll_effect="'.esc_attr($parallax_scroll_effect).'" class="' . esc_attr($css_class) .  ' '.esc_attr($parallax_style).$css_overlay_video.'"' . $style .'>';
                    }
                    else
                    {
                        if($overlay_set!='hide_overlay'){
                            $css_overlay_video=' overlay-wapper';
                        }
                        if ($parallax_style == 'video-background') {
                            $css_overlay_video.=' video-background-wapper';
                        }
                        $output .= '<div class="' . esc_attr($css_class) . $css_overlay_video.'"' . $style .'>';
                    }
                    if ($parallax_style == 'video-background') {
                        $output .= '<video data-top-default="0" muted="muted" loop="loop" autoplay="true" preload="auto">
                                <source src="' . esc_url($video_link) . '">
                            </video>';
                    }
                    if($overlay_set!='hide_overlay'){
                        $overlay_id='overlay-'.uniqid();
                        if($overlay_set=='show_overlay_color'){
                            $overlay_color = g5plus_hex_to_rgba(esc_attr($overlay_color),(esc_attr($overlay_opacity)/100));
                            $style_css=' data-overlay_color= '.esc_attr($overlay_color);
                        }
                        else if($overlay_set=='show_overlay_image'){
                            $image_attributes = wp_get_attachment_image_src( $overlay_image,'full' );
                            $style_css=' data-overlay_image= '.$image_attributes[0].' data-overlay_opacity='.(esc_attr($overlay_opacity)/100);
                        }
                        $output .= '<div id="'.$overlay_id.'" class="overlay" '.$style_css.'></div>';
                    }
                    $output .= wpb_js_remove_wpautop( $content );
                    $output .= '</div></div>';
                    return $output;
                }
                add_shortcode('vc_row', 'cupid_vc_row_shortcode');
                add_shortcode('vc_row_inner', 'cupid_vc_row_inner_shortcode');
                if (function_exists('vc_remove_param')) {
                    vc_remove_param('vc_row', 'full_width');
                    vc_remove_param('vc_row', 'parallax');
                    vc_remove_param('vc_row', 'parallax_image');
                }
                if(function_exists('vc_add_param')){
                    vc_add_param('vc_row',
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Row ID', 'js_composer' ),
                            'param_name' => 'el_id',
                        )
                    );
                    vc_add_param('vc_row',
                        array(
                            'type'       => 'dropdown',
                            'heading'    => __( 'Layout', 'wpb' ),
                            'param_name' => 'layout',
                            'value'      => array(
                                __( 'Full Width', 'wpb' )  => 'wide',
                                __( 'Container', 'wpb' ) => 'boxed',
                                __( 'Container Fluid', 'wpb' ) => 'container-fluid',
                            ),
                        )
                    );
                    vc_add_param("vc_row",
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Parallax Type','js_composer'),
                            'param_name' => 'parallax_style',
                            'value' => array(
                                __('None','js_composer') => "none",
                                __('Vertical Parallax On Scroll','js_composer') => 'vertical-parallax',
                                __('Horizontal Parallax On Scroll','js_composer') => 'horizontal-parallax',
                                __('Video Background','js_composer') => 'video-background',
                            ),
                            'description' => __("Select the kind of style you like for the background image of this row.",'js_composer'),
                        )
                    );
                    vc_add_param("vc_row",
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Link Video (.mp4 or .ogg)', 'js_composer' ),
                            'param_name' => 'video_link',
                            'value'      => '',
                            'dependency' => Array('element' => 'parallax_style','value' => array('video-background')),
                        )
                    );
                    vc_add_param('vc_row',
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Scroll effect', 'js_composer'),
                            'param_name' => 'parallax_scroll_effect',
                            'value' => array(
                                __('Fixed at its position', 'js_composer') => 'fixed',
                                __('Move with the content', 'js_composer') => 'scroll',
                            ),
                            'description' => __('Options to set whether a background image is fixed or scroll with the rest of the page.', 'js_composer'),
                            'dependency' => Array('element' => 'parallax_style','value' => array('vertical-parallax','horizontal-parallax')),
                        )
                    );
                    vc_add_param('vc_row',
                        array(
                            'type' => 'number',
                            'heading' => __('Parallax speed', 'js_composer'),
                            'param_name' => 'parallax_speed',
                            'value' =>'0',
                            'min'=>'0',
                            'max'=>'100',
                            'description' => __('Control speed of parallax. Enter value between 0 to 100', 'js_composer'),
                            'dependency' => Array('element' => 'parallax_style','value' => array('vertical-parallax','horizontal-parallax')),
                        )
                    );
                    vc_add_param('vc_row',
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Show background overlay', 'js_composer' ),
                            'param_name' => 'overlay_set',
                            'description' => __( 'Hide or Show overlay on background images.', 'js_composer' ),
                            'value' => array(
                                __( 'Hide, please', 'js_composer' ) =>'hide_overlay',
                                __( 'Show Overlay Color', 'js_composer' ) =>'show_overlay_color',
                                __( 'Show Overlay Image', 'js_composer' ) =>'show_overlay_image',
                            )
                        )
                    );
                    vc_add_param('vc_row',
                        array(
                            'type'        => 'attach_image',
                            'heading'     => __( 'Upload image:', 'js_composer' ),
                            'param_name'  => 'overlay_image',
                            'value'       => '',
                            'description' => __( "Upload image overlay.", 'js_composer' ),
                            'dependency'  => Array( 'element' => 'overlay_set', 'value' => array( 'show_overlay_image' ) ),
                        )
                    );
                    vc_add_param('vc_row',
                        array(
                            'type' => 'colorpicker',
                            'heading' => __( 'Overlay color', 'js_composer' ),
                            'param_name' => 'overlay_color',
                            'description' => __( 'Select color for background overlay.', 'js_composer' ),
                            'value' => '',
                            'dependency' => Array('element' => 'overlay_set','value' => array('show_overlay_color')),
                        )
                    );
                    vc_add_param('vc_row',array(
                            'type' => 'number',
                            'class' => '',
                            'heading' => __( 'Overlay opacity', 'js_composer' ),
                            'param_name' => 'overlay_opacity',
                            'value' =>'50',
                            'min'=>'1',
                            'max'=>'100',
                            'suffix'=>'%',
                            'description' => __( 'Select opacity for overlay.', 'js_composer' ),
                            'dependency' => Array('element' => 'overlay_set','value' => array('show_overlay_color','show_overlay_image')),
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Row ID', 'js_composer' ),
                            'param_name' => 'el_id',
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type'       => 'dropdown',
                            'heading'    => __( 'Layout', 'wpb' ),
                            'param_name' => 'layout',
                            'value'      => array(
                                __( 'Full Width', 'wpb' )  => 'wide',
                                __( 'Container', 'wpb' ) => 'boxed',
                                __( 'Container Fluid', 'wpb' ) => 'container-fluid',
                            ),
                        )
                    );
                    vc_add_param("vc_row_inner",
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Parallax Type','js_composer'),
                            'param_name' => 'parallax_style',
                            'value' => array(
                                __('None','js_composer') => "none",
                                __('Vertical Parallax On Scroll','js_composer') => 'vertical-parallax',
                                __('Horizontal Parallax On Scroll','js_composer') => 'horizontal-parallax',
                                __('Video Background','js_composer') => 'video-background',
                            ),
                            'description' => __("Select the kind of style you like for the background image of this row.",'js_composer'),
                        )
                    );
                    vc_add_param("vc_row_inner",
                        array(
                            'type'       => 'textarea',
                            'heading'    => __( 'Link Video (.mp4 or .ogg)', 'js_composer' ),
                            'param_name' => 'video_link',
                            'value'      => '',
                            'dependency' => Array('element' => 'parallax_style','value' => array('video-background')),
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Scroll effect', 'js_composer'),
                            'param_name' => 'parallax_scroll_effect',
                            'value' => array(
                                __('Fixed at its position', 'js_composer') => 'fixed',
                                __('Move with the content', 'js_composer') => 'scroll',
                            ),
                            'description' => __('Options to set whether a background image is fixed or scroll with the rest of the page.', 'js_composer'),
                            'dependency' => Array('element' => 'parallax_style','value' => array('vertical-parallax','horizontal-parallax')),
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type' => 'number',
                            'heading' => __('Parallax speed', 'js_composer'),
                            'param_name' => 'parallax_speed',
                            'value' =>'100',
                            'min'=>'1',
                            'max'=>'100',
                            'description' => __('Control speed of parallax. Enter value between 1 to 100', 'js_composer'),
                            'dependency' => Array('element' => 'parallax_style','value' => array('vertical-parallax','horizontal-parallax')),
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Show background overlay', 'js_composer' ),
                            'param_name' => 'overlay_set',
                            'description' => __( 'Hide or Show overlay on background images.', 'js_composer' ),
                            'value' => array(
                                __( 'Hide, please', 'js_composer' ) =>'hide_overlay',
                                __( 'Show Overlay Color', 'js_composer' ) =>'show_overlay_color',
                                __( 'Show Overlay Image', 'js_composer' ) =>'show_overlay_image',
                            )
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type'        => 'attach_image',
                            'heading'     => __( 'Upload image:', 'js_composer' ),
                            'param_name'  => 'overlay_image',
                            'value'       => '',
                            'description' => __( "Upload image overlay.", 'js_composer' ),
                            'dependency'  => Array( 'element' => 'overlay_set', 'value' => array( 'show_overlay_image' ) ),
                        )
                    );
                    vc_add_param('vc_row_inner',
                        array(
                            'type' => 'colorpicker',
                            'heading' => __( 'Overlay color', 'js_composer' ),
                            'param_name' => 'overlay_color',
                            'description' => __( 'Select color for background overlay.', 'js_composer' ),
                            'value' => '',
                            'dependency' => Array('element' => 'overlay_set','value' => array('show_overlay_color')),
                        )
                    );
                    vc_add_param('vc_row_inner',array(
                            'type' => 'number',
                            'class' => '',
                            'heading' => __( 'Overlay opacity', 'js_composer' ),
                            'param_name' => 'overlay_opacity',
                            'value' =>'50',
                            'min'=>'1',
                            'max'=>'100',
                            'suffix'=>'%',
                            'description' => __( 'Select opacity for overlay.', 'js_composer' ),
                            'dependency' => Array('element' => 'overlay_set','value' => array('show_overlay_color','show_overlay_image')),
                        )
                    );
                }
            }
        }
    }
    new Cupid_VC_Extend();
}