<?php
/**
 * Created by PhpStorm.
 * User: phuongth
 * Date: 2/26/15
 * Time: 3:23 PM
 */

$args = array(
    'offset' => $offset,
    'posts_per_page'   => $post_per_page,
'orderby'          => 'post_date',
'order'            => 'DESC',
'post_type'        => 'cupid_gallery',
'post_status'      => 'publish');
$posts_array  = new WP_Query( $args );
$total_display = $offset + $posts_array->post_count;
$total_items = $posts_array->found_posts;
$image_size = 'thumbnail-480x320';
$col_class = 'gallery-col-'.$column;
if($column==3){
$image_size = 'thumbnail-639x380';
}
$uniqid = uniqid();
?>
<div class="cupid-gallery">
    <div id="<?php echo esc_attr($uniqid);?>" class="gallery-wrapper <?php if($load_more=='1' && $total_display < $total_items) { echo esc_attr("gallery-infinite-scroll"); }?> <?php echo esc_attr($g5plus_animation) ?> <?php echo esc_attr($styles_animation) ?>">
        <?php
        while ( $posts_array->have_posts() ) : $posts_array->the_post();
            $permalink = get_permalink();
            $img = '';
            if( has_post_thumbnail()){
                $img = get_the_post_thumbnail(get_the_ID(),$image_size);
            }
            ?>
            <div class="gallery-item <?php echo esc_attr($col_class) ?>">
                <?php echo wp_kses_post($img);?>
                <div style="display: none">
                    <?php
                    $meta_values = get_post_meta( get_the_ID(), 'cupid-format-gallery', false );
                    if(count($meta_values) > 0){
                        foreach($meta_values as $image){
                            $urls = wp_get_attachment_image_src($image,'full');
                            $gallery_img = '';
                            if(count($urls)>0)
                                $gallery_img = $urls[0];
                            ?>
                            <div>
                                <a href="<?php echo esc_url($gallery_img) ?>" rel="prettyPhoto[pp_gal_<?php echo get_the_ID()?>]" title="<?php echo esc_attr(get_the_title()) ?>"></a>
                            </div>
                        <?php        }
                    }
                    ?>
                </div>
            </div>
        <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
    <?php if($load_more=='1' && $total_display < $total_items) { ?>
        <?php if($load_more_style=='scroll') {?>
            <nav id="infinite_scroll_button_<?php echo esc_attr($uniqid) ?>">
                <a href="<?php echo esc_url(get_site_url().'/wp-admin/admin-ajax.php?action=cupid_gallery_load_more&item='.$item.'&column='.$column.'&load_more='.$load_more.'&current_page='.$current_page) ?>"></a>
            </nav>
            <div id="infinite_scroll_loading_<?php echo esc_attr($uniqid) ?>" class="text-center"></div>
        <?php } ?>
        <?php
        if($load_more_style =='load-more-button'){
           ?>
            <div class="gallery-load-more-wrapper">
                <button data-wrapper-id="<?php echo esc_attr($uniqid);?>" data-href="<?php echo esc_url(get_site_url().'/wp-admin/admin-ajax.php?action=cupid_gallery_load_more&item='.$item.'&column='.$column.'&load_more='.$load_more.'&load_more_style='.$load_more_style.'&current_page='.($current_page+1)) ?>" type="button"  data-loading-text="<i class='fa fa-refresh fa-spin'></i> <?php _e("Loading...",'cupid'); ?>" class="gallery-load-more cupid-btn-2" autocomplete="off">
                    <?php _e("Load More",'cupid'); ?>
                </button>
            </div>
        <?php }?>
    <?php } ?>
</div>


