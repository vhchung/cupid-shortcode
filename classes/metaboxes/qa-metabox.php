<div class="my_meta_control">
    <p style="float:right">
        <a href="#" class="dodelete-classes_qa button">Remove All</a>
    </p>
    <div style="clear: both;margin-bottom: 15px;" ></div>
    <?php

        while($mb->have_fields_and_multi('classes_qa',array('length' => 1))): ?>
        <?php $mb->the_group_open(); ?>

        <?php $mb->the_field('qaQuestion'); ?>
        <label><?php _e('Question', 'cupid') ; ?></label>
        <input class="form-control" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>

        <?php $mb->the_field('qaAnswer'); ?>
        <label><?php _e('Answer', 'cupid'); ?></label>
        <textarea name="<?php $mb->the_name(); ?>" class="form-control" rows="7"><?php echo wp_kses_post($mb->the_value()); ?></textarea>
        <a href="#" class="dodelete button">Remove</a>
        <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
    <div style="clear: both;"></div>
    <p>
        <a href="#" class="docopy-classes_qa button"><?php _e('Add Q&A', 'cupid'); ?></a>
    </p>
</div>
<style>
    .my_meta_control .description
    { display:none; }

    .my_meta_control label
    { display:block; font-weight:bold; margin:6px; margin-bottom:0; margin-top:12px; }

    .my_meta_control label span
    { display:inline; font-weight:normal; }

    .my_meta_control span
    { color:#999; display:block; }

    .my_meta_control textarea, .my_meta_control input[type='text']
    { margin-bottom:3px; width:99%; }

    .my_meta_control h4
    { color:#999; font-size:1em; margin:15px 6px; text-transform:uppercase; }
    .wpa_group.wpa_group-process {
        border: 1px solid #ccc;
        padding: 10px;
        margin: 0 15px 15px 0;
        background: #fff;
        width: 20%;
        float: left;
    }
</style>