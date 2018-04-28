<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Heading')){
    class Cupid_Heading{
        function __construct(){
            add_shortcode('cupid_heading', array($this, 'cupid_heading_shortcode'));
        }
        function cupid_heading_shortcode($atts){
            $description = $title = $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'title'         => '',
                'description'   => '',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $html = '<div class="cupid-heading ' . $g5plus_animation . '" '.$styles_animation.'>
                    <h2>'. wp_kses_post($title) .'</h2>';
            if($description!='')
            {
                $html .= '<span>'.esc_html($description).'</span>';
            }
            $html .= '</div>';
            return $html;
        }
    }
    new Cupid_Heading;
}