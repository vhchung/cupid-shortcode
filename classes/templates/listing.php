<?php
/**
 * Created by PhpStorm.
 * User: phuongth
 * Date: 2/28/15
 * Time: 8:59 AM
 */
$args = array(
    'orderby'          => 'post_date',
    'posts_per_page'    => $post_per_page,
    'order'            => 'DESC',
    'post_type'        => 'cupid_classes',
    'post_status'      => 'publish');
$posts_array  = new WP_Query( $args );
$total_items = $posts_array->found_posts;

$image_size ='thumbnail-350x350';
$col_class = 'classes-col-'.$column;

$data_plugin_options = '';
$owl_carousel_class = '';
if($show_paging=='1' && $total_items > $item)
{
    $col_class = '';
    $data_plugin_options = 'data-plugin-options=\'{ "items" : '.$column.',"pagination": false, "navigation" : true, "autoPlay": false}\'';
    $owl_carousel_class = 'owl-carousel';
}
?>
<div class="cupid-class">
<div class="classes-wrapper <?php echo esc_attr($owl_carousel_class) ?>" <?php echo wp_kses_post($data_plugin_options)  ?> >
        <?php
        $index = 1;
        while ( $posts_array->have_posts() ) : $posts_array->the_post();
            $month_old = get_post_meta( get_the_ID(), 'month-olds', false );
            $class_size = get_post_meta( get_the_ID(), 'class-size', false );
            $img = '';
            if( has_post_thumbnail()){
                $img = get_the_post_thumbnail(get_the_ID(),$image_size);
            }
        ?>
            <div class="classes-item <?php echo esc_attr($col_class) ?>" <?php if($index%$column==1 && $index>1) { echo 'style="clear: both"';} ?>>
                <div class="thumbnail-wrap">
                    <?php echo wp_kses_post($img) ?>
                </div>
                <h6><a href="<?php echo esc_attr(get_permalink())?>" title="<?php echo get_the_title() ?>"><?php echo get_the_title(); ?></a></h6>
                <div class="excerpt"><?php echo get_the_excerpt() ?></div>

            </div>
        <?php
            $index++;
            endwhile;
            wp_reset_postdata();
        ?>
    </div>
</div>


