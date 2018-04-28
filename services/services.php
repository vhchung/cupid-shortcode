<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Services')){
    class Cupid_Services{
        function __construct(){
            add_shortcode('cupid_services', array($this, 'cupid_services_shortcode'));
        }
        function cupid_services_shortcode($atts){
            $link_target= $link = $title = $description = $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'title'         => '',
                'description'   => '',
                'link'	        => '',
                'link_target'	=> '',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $link_target= ($link_target == 'yes') ? '_blank' : '_self';
            $html .= '<div class="cupid-services content-middle ' . $g5plus_animation . '" '.$styles_animation.'>';
            $html .= '<div class="content-middle-inner">';
            $html .= '<a href="'.esc_url($link).'" target="'.$link_target.'">'.esc_html($title).'</a>';
            $html .= '<p>'.esc_html($description).'</p>';
            $html .= '</div></div>';
            return $html;
        }
    }
    new Cupid_Services;
}