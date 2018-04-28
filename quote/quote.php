<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Quote')){
    class Cupid_Quote{
        function __construct(){
            add_shortcode('cupid_quote', array($this, 'cupid_quote_shortcode'));
        }
        function cupid_quote_shortcode($atts){
            $subtext = $text = $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'text'          => '',
                'subtext'	    => '',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $html .= '<div class="cupid-quote content-middle ' . $g5plus_animation . '" '.$styles_animation.'>';
            $html .= '<div class="content-middle-inner">';
            $html .= '<q>'.esc_html($text).'</q>';
            $html .= '<p>'.esc_html($subtext).'</p>';
            $html .= '</div></div>';
            return $html;
        }
    }
    new Cupid_Quote;
}