<?php
/**
 * Created by PhpStorm.
 * User: phuongth
 * Date: 2/26/15
 * Time: 3:40 PM
 */
if (!defined('ABSPATH')) die('-1');

if (!class_exists('WPAlchemy_MetaBox')) {
    include_once(plugin_dir_path(__FILE__) . 'libraries/wpalchemy/MetaBox.php');
}

include_once(plugin_dir_path(__FILE__) . 'metaboxes/spec.php');

if (!class_exists('Cupid_Classes')) {
    class Cupid_Classes
    {
        function __construct()
        {
            add_action('init', array($this, 'register_taxonomies'), 5);
            add_action('init', array($this, 'register_post_types'), 6);
            add_shortcode('cupid_classes', array($this, 'cupid_classes_shortcode'));
            add_filter('rwmb_meta_boxes', array($this, 'cupid_register_meta_boxes'));
            add_action('after_setup_theme', array($this, 'register_image_size'));
            add_action('admin_menu', array($this, 'addMenuChangeSlug'));
        }

        function register_taxonomies()
        {
            if (taxonomy_exists('classes_category')) {
                return;
            }
            $post_type = 'cupid_classes';
            $taxonomy_slug = 'classes-category';
            $taxonomy_name = 'Classes Categories';

            $post_type_slug = get_option('cupid-' . $post_type . '-config');
            if (isset($post_type_slug) && is_array($post_type_slug) &&
                array_key_exists('taxonomy_slug',$post_type_slug) &&  $post_type_slug['taxonomy_slug']!='') {
                $taxonomy_slug = $post_type_slug['taxonomy_slug'];
                $taxonomy_name = $post_type_slug['taxonomy_name'];
            }

            register_taxonomy('classes_category', 'cupid_classes', array(
                'public' => true,
                'hierarchical' => true,
                'label' => $taxonomy_name,
                'query_var' => true,
                'rewrite' => array('slug' => $taxonomy_slug)));

        }

        function register_post_types()
        {
            $post_type = 'cupid_classes';
            if (post_type_exists($post_type)) {
                return;
            }

            $post_type_slug = get_option('cupid-' . $post_type . '-config');
            if (!isset($post_type_slug) || !is_array($post_type_slug)) {
                $slug = 'cupid-classes';
                $name = $singular_name = 'Classes';
            } else {
                $slug = $post_type_slug['slug'];
                $name = $post_type_slug['name'];
                $singular_name = $post_type_slug['singular_name'];
            }

            register_post_type($post_type,
                array(
                    'label' => esc_attr__('Cupid Classes', 'cupid'),
                    'description' => esc_attr__('Classes Description', 'cupid'),
                    'labels' => array(
                        'name' => $name,
                        'singular_name' => $singular_name,
                        'menu_name' => esc_attr($name),
                        'parent_item_colon' => esc_attr__('Parent Item:', 'cupid'),
                        'all_items' => sprintf(esc_attr__('All %s','cupid'),$name),
                        'view_item' => esc_attr__('View Item', 'cupid'),
                        'add_new_item' => sprintf(esc_attr__('Add New %s','cupid'),$name),
                        'add_new' => esc_attr__('Add New', 'cupid'),
                        'edit_item' => esc_attr__('Edit Item', 'cupid'),
                        'update_item' => esc_attr__('Update Item', 'cupid'),
                        'search_items' => esc_attr__('Search Item', 'cupid'),
                        'not_found' => esc_attr__('Not found', 'cupid'),
                        'not_found_in_trash' => esc_attr__('Not found in Trash', 'cupid'),
                    ),
                    'rewrite' => array('slug' => $slug, 'with_front' => true),
                    'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments'),
                    'public' => true,
                    'has_archive' => true,
                    'query_var' => true
                )
            );
            flush_rewrite_rules();
        }

        function cupid_classes_shortcode($atts)
        {
            $g5plus_animation = $show_paging = $column = $duration = $delay = $item = $css_animation = $el_class = '';
            extract(shortcode_atts(array(
                'column' => '3',
                'item' => '6',
                'show_paging' => '0',
                'css_animation' => '',
                'duration' => '',
                'delay' => '',
                'el_class' => '',
            ), $atts));

            $post_per_page = $item;
            if ($show_paging == '1')
                $post_per_page = -1;

            $g5plus_animation .= ' ' . esc_attr($el_class);
            $g5plus_animation .= g5plus_getCSSAnimation($css_animation);
            $styles_animation = g5plus_getStyleAnimation($duration, $delay);

            $plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
            $template_path = $plugin_path . '/templates/listing.php';

            ob_start();
            include($template_path);
            $ret = ob_get_contents();
            ob_end_clean();
            return $ret;
        }

        function cupid_register_meta_boxes($meta_boxes)
        {
            $args = array(
                'nopaging' => true,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => 'our-staffs',
                'post_status' => 'publish');
            $query = new WP_Query($args);
            $posts = $query->get_posts();
            $list_teacher = array();
            foreach ($posts as $post) {
                if ($post->post_name != '' && $post->post_title != '')
                    $list_teacher[$post->post_name] = $post->post_title;
            }
            $meta_boxes[] = array(
                'title' => __('Classes Extra', 'cupid'),
                'id' => 'cupid-meta-box-format-classes',
                'pages' => array('cupid_classes'),
                'fields' => array(
                    array(
                        'name' => __('Teacher', 'cupid'),
                        'id' => 'teacher-class',
                        'type' => 'select',
                        'options' => $list_teacher,
                        'multiple' => false,
                        'std' => 'none'
                    ),
                    array(
                        'name' => __('Month Old', 'cupid'),
                        'id' => 'month-olds',
                        'type' => 'text',
                    ),
                    array(
                        'name' => __('Class Size', 'cupid'),
                        'id' => 'class-size',
                        'type' => 'text',
                    ),
                    array(
                        'name' => __('Enroll link', 'cupid'),
                        'id' => 'enroll-link',
                        'type' => 'text',
                    ),
                    array(
                        'name' => __('Popular', 'cupid'),
                        'id' => 'popular-class',
                        'type' => 'select',
                        'options' => array('0' => 'No', '1' => 'Yes'),
                        'multiple' => false,
                        'std' => '0'
                    ),
                    array(
                        'name' => __('Title Overview', 'cupid'),
                        'id' => 'title_overview',
                        'type' => 'text',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Overview', 'cupid'),
                        'id' => 'overview',
                        'type' => 'wysiwyg',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Title Structure', 'cupid'),
                        'id' => 'title_structure',
                        'type' => 'text',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Class structure', 'cupid'),
                        'id' => 'class_structure',
                        'type' => 'wysiwyg',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Title Requirement', 'cupid'),
                        'id' => 'title_requirement',
                        'type' => 'text',
                        'options' => array(),
                    ),
                    array(
                        'name' => __('Entry requirement', 'cupid'),
                        'id' => 'entry_requirement',
                        'type' => 'wysiwyg',
                        'options' => array(),
                    ),
                )
            );
            return $meta_boxes;
        }

        function register_image_size()
        {
            add_image_size('thumbnail-350x350', 350, 350, true);
            add_image_size('thumbnail-870x430', 870, 430, true);
        }

        function addMenuChangeSlug()
        {
            add_submenu_page('edit.php?post_type=cupid_classes', 'Setting', 'Settings', 'edit_posts', wp_basename(__FILE__), array($this, 'initPageSettings'));
        }

        function initPageSettings()
        {
            $template_path = ABSPATH . 'wp-content/plugins/cupid-shortcode/posttype-settings/settings.php';
            if (file_exists($template_path))
                require_once $template_path;
        }
    }

    new Cupid_Classes();
}
