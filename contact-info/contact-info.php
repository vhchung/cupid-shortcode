<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Contact_Info')){
    class Cupid_Contact_Info{
        function __construct(){
            add_shortcode('cupid_contact_info', array($this, 'cupid_contact_info_shortcode'));
        }
        function cupid_contact_info_shortcode($atts){
            $mobile=$phone=$email=$address=$type=$icon = $title = $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation ='';
            extract( shortcode_atts( array(
                'type'          => 'address',
                'icon'	        => '',
                'title'         => '',
                'address'       => '',
                'email'         => '',
                'phone'         => '',
                'mobile'        => '',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            $html .= '<div class="cupid-contact-info ' . $g5plus_animation . '" '.$styles_animation.'>';
            $html .= '<div class="contact-icon"><i class="fa '.esc_attr($icon).'"></i></div>';
            $html .= '<h4>'.esc_html($title).'</h4>';
            $html .= '<div class="contact-info">';
            if($type=='address')
            {
                $html .= '<i class="fa fa-bank"></i><p>'.wp_kses_post($address).'</p>';
            }
            else if($type=='phone')
            {
                if($phone!='')
                {
                    $html .= '<i class="fa fa-phone"></i><p>'.wp_kses_post($phone).'</p>';
                }
                if($mobile!='')
                {
                    $html .= '<br><i class="fa fa-mobile"></i><p>'.wp_kses_post($mobile).'</p>';
                }
            }
            else
            {
                $html .= '<i class="fa fa-envelope"></i><p>'.wp_kses_post($email).'</p>';
            }
            $html .= '</div></div>';
            return $html;
        }
    }
    new Cupid_Contact_Info;
}