<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) die( '-1' );
if(!class_exists('Cupid_Icon_Box')){
    class Cupid_Icon_Box{
        function __construct(){
            add_shortcode('cupid_icon_box', array($this, 'cupid_icon_box_shortcode'));
        }
        function cupid_icon_box_shortcode($atts){
            $icon_position=$icon_color=$layout_style=$link=$description=$title=$image=$icon= $html = $el_class = $g5plus_animation = $css_animation = $duration = $delay = $styles_animation = '';
            extract( shortcode_atts( array(
                'layout_style'  => 'style1',
                'icon'          => '',
                'icon_color'    => '',
                'icon_position' => 'left',
                'link'          => '',
                'title'         => '',
                'description'   => '',
                'el_class'      => '',
                'css_animation' => '',
                'duration'      => '',
                'delay'         => ''
            ), $atts ) );
            $number_id = rand( 0, 10000 ).'-'.rand( 0, 10000 );
            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation( $css_animation );
            $styles_animation= g5plus_getStyleAnimation($duration,$delay);
            if($layout_style=='style2') $description='';
            if($icon_position!='') $icon_position=' '.esc_attr($icon_position);
            $html .= '<div id="icon-box-'.$number_id.'" class="cupid-icon-box ' . esc_attr($layout_style). $icon_position . $g5plus_animation .'" '.$styles_animation .'>';
            $html .= '<a href="'.esc_url($link).'"><i class="fa '.esc_attr($icon).'"></i></a>
                              <h4>'. wp_kses_post($title) .'</h4>';
                if($description!='')
                {
                    $html .= '<p>'.esc_html($description).'</p>';
                }
                $html .= '</div>';
            if($icon_color!='')
            {
                $icon_color=esc_attr($icon_color);
                if($layout_style=='style1')
                {
                    $style_css='#icon-box-'.$number_id.'.cupid-icon-box.style1 a{color: '.$icon_color.' !important;}#icon-box-'.$number_id.'.cupid-icon-box.style1 a:hover{background-color: '.$icon_color.' !important; color:#FFFFFF !important;}#icon-box-'.$number_id.'.cupid-icon-box.style1:hover a{background-color: '.$icon_color.' !important; color:#FFFFFF !important;}#icon-box-'.$number_id.'.cupid-icon-box.style1:hover a:after{border-top: 17px solid '.$icon_color.' !important;}';
                }
                else
                {
                    $style_css='#icon-box-'.$number_id.'.cupid-icon-box.style2 i{background-color: '.$icon_color.' !important;}#icon-box-'.$number_id.'.cupid-icon-box.style2 i:after {-webkit-box-shadow: 0 0 0 2px '.$icon_color.' !important;-moz-box-shadow: 0 0 0 2px '.$icon_color.' !important;-o-box-shadow: 0 0 0 2px '.$icon_color.' !important;-ms-box-shadow: 0 0 0 2px '.$icon_color.' !important;box-shadow: 0 0 0 2px '.$icon_color.' !important;}';
                }
                $html .= '<script>
                            jQuery(document).ready(function () {
                                jQuery("[data-type=vc_shortcodes-custom-css]").append("'.$style_css.'");
                            });
                         </script>';
            }
            return $html;

        }
    }
    new Cupid_Icon_Box;
}