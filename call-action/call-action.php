<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Call_Action')){
    class Cupid_Call_Action{
        function __construct(){
            add_shortcode('cupid_call_action', array($this, 'cupid_call_action_shortcode'));
        }
        function cupid_call_action_shortcode($atts){
            $link_target= $link = $button_label = $text =  $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'text' => '',
                'button_label' => '',
                'link'	=> '',
                'link_target'	=> '',
                'el_class'       => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $link_target= ($link_target == 'yes') ? '_blank' : '_self';
            $html .= '<div class="cupid-call-action ' . $g5plus_animation . ' content-middle" '.$styles_animation.'>
                            <div class="content-middle-inner">
                                <p>'.wp_kses_post($text).'
                                <a class="cupid-button button-lg" href="'.esc_url($link).'" target="'.$link_target.'">'.esc_html($button_label).'</a></p>
                            </div>
                      </div>';
            return $html;
        }
    }
    new Cupid_Call_Action;
}