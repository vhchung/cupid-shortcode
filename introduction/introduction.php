<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Introduction')){
    class Cupid_Introduction{
        function __construct(){
            add_shortcode('cupid_introduction', array($this, 'cupid_introduction_shortcode'));
        }
        function cupid_introduction_shortcode($atts){
            $text_align=$description = $text = $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'text'          => '',
                'description'	=> '',
                'text_align'	=> 'left',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $html .= '<div class="cupid-introduction '.$text_align . $g5plus_animation . '" '.$styles_animation.'>';
            $html .= '<h3>'.wp_kses_post($text).'</h3>';
            $html .= '<p>'.esc_html($description).'</p>';
            $html .= '</div>';
            return $html;
        }
    }
    new Cupid_Introduction;
}