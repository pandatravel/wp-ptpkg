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

$templates = new Ptpkg\lib\common\CustomTemplateLoader;

/**
 * Custom meta fields
 */
$currentID = get_the_ID();
$packageBannerUrl = wp_get_attachment_image_url(get_post_meta($currentID, 'package-banner', true), 'full');
$packageTeaser = get_post_meta($currentID, 'package-teaser', true);
$packagePrice = get_post_meta($currentID, 'package-price', true);

get_header(); ?>

<!-- Start single-package-book.php -->
<div id="page-body" class="package-single-wrapper">
    <div id="app" class="package-book" v-cloak><!-- vue root -->
        <notifications group="package" position="bottom right" :duration="4000"></notifications>

        <v-app>
            <v-jumbotron src="<?php echo $packageBannerUrl; ?>" height="333px" class="package-banner mb-3">
                <v-container fill-height py-5>
                    <v-layout row pt-5>
                        <v-flex sm9 offset-sm3 text-sm-left>
                            <h2 class="package-title white--text"><?php the_title(); ?></h2>
                            <div class="package-teaser">
                                <?php echo $packageTeaser; ?>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-jumbotron>

            <v-content>
                <v-container fluid>
                    <?php
                    /* Start the Loop */
                    while (have_posts()) : the_post();

                        $templates->get_template_part('package/content-book');

                    endwhile; // End of the loop.
                    ?>
                </v-container>
            </v-content>
        </v-app>

    </div><!-- #app -->
</div><!-- #page-body .package-single-wrapper -->

<?php get_footer(); ?>
