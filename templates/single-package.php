<?php
/**
* Template Name: Single Package
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

/**
 * Custom meta fields
 */
$packagePrice = get_post_meta(get_the_ID(), 'package_price', true);
$packageLocation = get_post_meta(get_the_ID(), 'package_location', true);

setPostViews(get_the_ID());

get_header(); ?>

<script type="application/ld+json">
{
    "@context" : "http://schema.org",
    "@type" : "Product",
    "name" : "<?php the_title(); ?>",
    "offers" : {
        "@type" : "Offer",
        "price" : "<?php echo $packagePrice; ?>"
    },
    "location" : {
        "@type" : "Place",
        "name" : "<?php echo $packageLocation; ?>"
    }
}
</script>

<!-- Start single-package.php -->
<div id="page-body" class="package-single-wrapper">
    <div class="container-fluid">

        <?php
        /* Start the Loop */
        while (have_posts()) : the_post();

            $templates->get_template_part('package/content');

        endwhile; // End of the loop.
        ?>

    </div><!-- .container-fluid -->
</div><!-- #page-body .package-single-wrapper -->

<?php get_footer(); ?>
