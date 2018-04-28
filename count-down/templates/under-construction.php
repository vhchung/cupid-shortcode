<?php
/**
 * Created by PhpStorm.
 * User: phuongth
 * Date: 1/17/15
 * Time: 2:56 PM
 */
global $cupid_data;

wp_enqueue_script('cupid-jquery-countdown',plugins_url() . '/cupid-shortcode/count-down/assets/jquery.countdown/jquery.countdown.min.js',array(), false, true);
wp_enqueue_script('cupid-jquery-knob',plugins_url() . '/cupid-shortcode/count-down/assets/jquery.countdown/jquery.knob.min.js',array(), false, true);

$primary_color = $cupid_data['primary-color'];
$font_family = $cupid_data['body-font']['face'];
$args = array(
    'posts_per_page'   => -1,
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'post_type'        => 'cupid_countdown',
    'post_status'      => 'publish');
$posts_array  = new WP_Query( $args );
$title_post = $opening_hours = $description = $bg_image = '';
$urlRedirect = '';
while ( $posts_array->have_posts() ) : $posts_array->the_post();
    $countdown_type= rwmb_meta('countdown-type');
    if($type==$countdown_type){
        $urlRedirect = rwmb_meta('countdown-url');
        $opening_hours = rwmb_meta('countdown-opening');
        $title_post = get_the_title();
        $description = get_the_content();
        $images = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'full');
        if(count($images)>0)
            $bg_image = $images[0];
        break;
    }

endwhile;
wp_reset_postdata();
?>

<div class="under-construction cupid-overlay <?php echo esc_attr($css) ?>" style="background-image: url(<?php echo esc_url($bg_image) ?>);background-repeat: no-repeat;background-size: cover;">
    <div id="ryl-home-content" class="container ryl-cell-vertical-wrapper ryl-home-content">
        <div class="ryl-cell-middle">
            <div class="ryl-underconstruction-wrapper ryl-margin-bottom-30">
                <div class="ryl-title">
                    <h1 class="ryl-bold-heading"><?php echo wp_kses_post($title_post)?></h1>
                    <p><?php echo wp_kses_post($description) ?></p>
                </div>
                <div class="ryl-countdown-clock is-countdown">
            <span class="countdown-row countdown-show5">
                <span class="countdown-section">
                    <input type="text" data-min="0" data-width="80" data-height="80"  data-readOnly="true"
                           data-thickness=".1" value="0" class="days" id="months"
                           data-fgColor="#FFA73C" data-inputColor="#fff" data-bgColor="rgba(255, 255, 255, 0.4)"
                           data-font="<?php echo esc_attr($font_family) ?>"
                        >
                    <span class="countdown-period"><?php _e('Months','cupid') ?></span>
                </span>
                <span class="countdown-section">
                    <input type="text" data-min="0" data-max="31" data-width="80" data-height="80" data-readOnly="true"
                           data-thickness=".1" value="0" class="days" id="days"
                           data-fgColor="#FFA73C" data-inputColor="#fff" data-bgColor="rgba(255, 255, 255, 0.4)"
                           data-font="<?php echo esc_attr($font_family) ?>"
                        >
                    <span class="countdown-period"><?php _e('Days','cupid') ?></span>
                </span>
                <span class="countdown-section">
                      <input type="text" data-min="0" data-max="23" data-width="80" data-height="80" data-readOnly="true"
                             data-thickness=".1" value="0" class="hours" id="hours"
                             data-fgColor="#FFA73C" data-inputColor="#fff" data-bgColor="rgba(255, 255, 255, 0.4)"
                             data-font="<?php echo esc_attr($font_family) ?>"
                          >
                    <span class="countdown-period"><?php _e('Hours','cupid') ?></span>
                </span>
                <span class="countdown-section">
                    <input type="text" data-min="0" data-max="59" data-width="80" data-height="80" data-readOnly="true"
                           data-thickness=".1"  value="0" class="minutes" id="minutes"
                           data-fgColor="#FFA73C" data-inputColor="#fff" data-bgColor="rgba(255, 255, 255, 0.4)"
                           data-font="<?php echo esc_attr($font_family) ?>"
                        >
                    <span class="countdown-period"><?php _e('Minutes','cupid') ?></span>
                </span>
                <span class="countdown-section">
                    <input type="text" data-min="0" data-max="59" data-width="80" data-height="80" data-readOnly="true"
                           data-thickness=".1"  value="0" class="minutes" id="second"
                           data-fgColor="#FFA73C" data-inputColor="#fff" data-bgColor="rgba(255, 255, 255, 0.4)"
                           data-font="<?php echo esc_attr($font_family) ?>"
                        >
                    <span class="countdown-period"><?php _e('Seconds','cupid') ?></span>
                </span>
            </span>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    (function($) {
        "use strict";

        function set_under_construction_height(){
            var $windowHeight = $(window).height();
            if($windowHeight>620){
                $('.under-construction').css('height',$windowHeight+'px');
                $('.ryl-home-content').css('height',$windowHeight+'px');
            }
        }

        $(document).ready(function(){

            var isInitCountdown = 0;

            $("#ryl-home-content").countdown('<?php echo esc_html($opening_hours); ?>',function(event){
                var months = parseInt(event.strftime('%m'));
                $('#months').attr('data-max',months);
                if(isInitCountdown==0){
                    $('input','#ryl-home-content').css('font-size','45px');
                    $('input','#ryl-home-content').knob();
                    isInitCountdown=1;
                }

            });

            $("#ryl-home-content").countdown('<?php echo esc_html($opening_hours); ?>').on('update.countdown', function(event) {
                var second = parseInt(event.strftime('%S'));
                var minutes = parseInt(event.strftime('%M'));
                var hours = parseInt(event.strftime('%H'));
                var days = parseInt(event.strftime('%d'));
                var months = parseInt(event.strftime('%m'));
                var weeks = parseInt(event.strftime('%w'));

                if(months>0){
                    var bufferDay = weeks%4 * 7;
                    if(bufferDay>0){
                            days = bufferDay;
                    }
                }
                else{
                    days =  weeks*7 + days;
                }
                $('#second').val(second).trigger('change');
                $('#minutes').val(minutes).trigger('change');
                $('#hours').val(hours).trigger('change');
                $('#days').val(days).trigger('change');
                $('#months').val(months).trigger('change');


            }).on('finish.countdown', function(event){
                $('.seconds',this).html(0);
                <?php if( $urlRedirect!=''){ ?>
                window.location.href= '<?php echo esc_url($urlRedirect) ?>';
                <?php } ?>
            });
            if ((navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)) {
                $( window ).resize(function() {
                    set_under_construction_height();
                });
                set_under_construction_height();
            }

            var height, width;
            var marginTop = '12px';
            var marginLeft = '-65px';
            height = width =  '54px';
            if(navigator.userAgent.indexOf("Chrome") != -1 ){
                height = width = '60px';
                marginTop = '8px';
                marginLeft = '-69px';
            }
            $('input','#ryl-home-content').css('font-size','45px');
            $('input','#ryl-home-content').css('font-weight','600');
            $('input','#ryl-home-content').css('height',height);
            $('input','#ryl-home-content').css('width',width);
            $('input','#ryl-home-content').css('margin-top',marginTop);
            $('input','#ryl-home-content').css('margin-left',marginLeft);
        });
    })(jQuery);
</script>