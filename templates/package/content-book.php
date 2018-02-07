<?php

/**
 * Template part for displaying packages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ptpkg
 * @since 1.0
 * @version 1.2
 * @author Ammon Casey
 */

use Ptpkg\lib\Api;

// Get an instance of the ptpkg api
$api = new Api();

/**
 * Custom meta fields
 */
$currentID = get_the_ID();
$package = $api->get_client()->tours()->show_wp($currentID);
$packageBannerUrl = wp_get_attachment_image_url(get_post_meta($currentID, 'package-banner', true), 'full');
$packageTeaser = get_post_meta($currentID, 'package-teaser', true);
$packagePrice = get_post_meta($currentID, 'package-price', true);
$packageLocation = get_post_meta($currentID, 'package-location', true);
$packageSEOAd = get_post_meta($currentID, 'package-seo-ad', true);
$packageSEOContent = get_post_meta($currentID, 'package-seo-content', true);

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('package-book'); ?>>

    <v-layout row>
        <v-flex xs10 offset-xs1>

            <v-card>
                <v-card-text>
                    <h2 class="card-title text-primary text-sm-center"><?php the_title(); ?></h2>
                </v-card-text>

                <booking-form>
                    <div slot="step-1">Step 1</div>
                    <div slot="step-2">Step 2</div>
                    <div slot="step-3">Step 3</div>
                    <div slot="step-4">Step 4</div>
                </booking-form>

            </v-card>

        </v-flex>
    </v-layout>

</article><!-- #post-## -->

<!-- <pre><?php print_r($package); ?></pre> -->
