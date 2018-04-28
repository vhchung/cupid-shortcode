<?php
/**
 * Add the question & answer meta box
 * @var [type]
 */
$class_metabox_qa = new WPAlchemy_MetaBox(array
(
    'id' => 'cupid_classes_qa_settings',
    'title' => __('Question & Answer', 'cupid'),
    'template' => plugin_dir_path( __FILE__ ) . 'qa-metabox.php',
    'types' => array('cupid_classes'),
    'autosave' => TRUE,
    'priority' => 'high',
    'context' => 'normal',
    'hide_editor' => FALSE
));


