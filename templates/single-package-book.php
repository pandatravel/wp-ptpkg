<?php
/**
* Template Name: Single Package Booking
* Template Post Type: package
*
* The Template for displaying all package single posts.
*
* @package ptpkg
* @since 1.0
* @version 1.0
* @author Ammon Casey
*/

$templates = new Ptpkg\lib\CustomTemplateLoader;

get_header(); ?>

<!-- Start single-package.php -->
<div id="page-body" class="package-single-wrapper">
    <div id="app" class="container-fluid">

        <?php
        /* Start the Loop */
        while (have_posts()) : the_post();

            $templates->get_template_part('package/content-book');

        endwhile; // End of the loop.
        ?>

    </div><!-- .container-fluid -->
</div><!-- #page-body .package-single-wrapper -->

<?php get_footer(); ?>
