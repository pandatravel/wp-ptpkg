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

$templates = new Ptpkg\lib\common\CustomTemplateLoader;

/**
 * Custom meta fields
 */
$currentID = get_the_ID();
$packageBannerUrl = wp_get_attachment_image_url(get_post_meta($currentID, 'package-banner', true), 'full');
$packagePrice = get_post_meta($currentID, 'package-price', true);
$packageLocation = get_post_meta(get_the_ID(), 'package-location', true);
$packageTeaser = get_post_meta($currentID, 'package-teaser', true);
$packageSEOAd = get_post_meta($currentID, 'package-seo-ad', true);
$packageSEOContent = get_post_meta($currentID, 'package-seo-content', true);

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
<div id="page-body" class="package-single-wrapper pt-3">
    <div id="app" class="package-details" v-cloak><!-- vue root -->

        <v-app>

            <v-content class="page-content">
                <v-container class="page-content-wrapper" pa-0>

                    <v-layout align-start justify-center class="hidden-sm-and-down">
                        <v-flex xs12 py-0>
                            <corner-ribbon color="danger"></corner-ribbon>
                            <v-jumbotron src="<?php echo $packageBannerUrl; ?>" height="300px" class="package-banner mb-3">
                                <v-container fill-height py-5>
                                    <v-layout row wrap pt-5>
                                        <v-flex sm4 offset-sm4 class="package-cta-book">
                                            <action-button :id="<?php echo $currentID ?>" url="<?php echo get_post_permalink($currentID) ?>" color="primary" large block round load-status></action-button>
                                        </v-flex>
                                        <v-flex sm9 offset-sm3 text-sm-left>
                                            <h2 class="package-title white--text"><?php the_title(); ?></h2>
                                            <div class="package-teaser">
                                                <?php echo $packageTeaser; ?>
                                            </div>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-jumbotron>
                        </v-flex>
                    </v-layout>

                    <?php
                    /* Start the Loop */
                    while (have_posts()) : the_post();

                        $templates->get_template_part('package/content');

                    endwhile; // End of the loop.
                    ?>
                </v-container>
            </v-content>

        </v-app>

    </div><!-- #app -->
    <div class="theme--light">
        <hr class="divider mt-0">
    </div>
<!-- #page-body .package-single-wrapper -->

<?php get_footer(); ?>
