<?php
/*
Template Name Posts: Package Article
*
* The Template for displaying single posts in one column wrapping the search widget and packages in the other column.
*
* @package GetReal
* @subpackage versionthree
* @since versionthree 1.0
*/

get_header();
?>

<!-- Start post-with-widget.php -->
<div id="page-body">
    <div id="main-content" class="clearfix">
        <?php the_post(); ?>

        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="row entry-content">
                <div class="col-md-4">
                    <?php include('booking-widget.php'); ?>
                    <div> &nbsp; </div>
                </div>
                <div class="col-md-8 col-md-float-right">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </div>

                <div class="col-md-4">
                    <p>
                        <a href="/cheap-hawaii-vacation-packages/"><img style="margin-right: 5px;" alt="" src="/wp-content/uploads/2017/05/nav-banner-cheaphawaiivacationpackages-barry.jpg" /></a>
                    </p>
                    <p>
                        <a href="/hawaii-family-vacation/"><img style="margin-right: 5px;" alt="" src="/wp-content/uploads/2012/02/navbanner-hawaiifamilyvacationpackages-barry-002.jpg" /></a>
                    </p>
                    <p>
                        <a href="/hawaii-resort-vacations/"><img style="margin-right: 5px;" alt="" src="/wp-content/uploads/2012/02/nav-banner-hawaiiresortvacations-002.jpg" /></a>
                    </p>
                    <p>
                        <a href="/hawaii-travel-packages/"><img alt="" src="/wp-content/uploads/2012/02/nav-banner-hawaiitravelpackages-003.jpg" /></a>
                    </p>
                </div>
            </div>

        </div><!-- #post- -->
    </div><!-- #main-content -->
    <!-- End post-with-widget.php -->
<?php get_footer(); ?>
