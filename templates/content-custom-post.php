<?php
/**
 * Template part for displaying custom post type posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ptpkg
 */

/*
 * ExopiteTemplate template engine uses includes/libraries/class-exopite-template.php
 *
 * See tutorial file:
 * add_templater_engine_to_plugin.php
 */
$placeholders = [
    'post-id' => 'post-' . get_the_ID(),
    'title' => get_the_title(),
    'the_excerpt'  => get_the_excerpt(),
    'footer' => 'This is the footer.',
];

ExopiteTemplate::$variables_array = $placeholders;
ExopiteTemplate::$filename = 'templates/content-custom-post.html';
echo ExopiteTemplate::get_template();
