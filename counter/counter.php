<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Counter')){
    class Cupid_Counter{
        function __construct(){
            add_shortcode('cupid_counter', array($this, 'cupid_counter_shortcode'));
        }
        function cupid_counter_shortcode($atts){
            $value = $title = $html = $el_class ='';
            extract( shortcode_atts( array(
                'value' => '',
                'title' => '',
                'el_class'       => ''
            ), $atts ) );

			$min_suffix = defined( 'CUPID_SCRIPT_DEBUG' ) && CUPID_SCRIPT_DEBUG ? '' : '.min';
            wp_enqueue_script('cupid_counter',plugins_url('cupid-shortcode/counter/jquery.countTo'.$min_suffix.'.js'),array(),false, true);

            $html .= '<div class="cupid-counter '.esc_attr($el_class).'">';
            if ( $value != '' ) {
                $html .= '<span class="display-percentage" data-percentage="' . esc_attr($value) . '">' . esc_html($value) . '</span>';
                if ( $title != '' ) {
                    $html .= '<p class="counter-title">' .wp_kses_post($title) . '</p>';
                }
            }
            $html .= '</div>';
            return $html;
        }
    }
    new Cupid_Counter;
}