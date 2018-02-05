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

    <div class="row">
        <div class="package-banner" style="min-height:333px; padding:0px 5px; background: url('<?php echo $packageBannerUrl; ?>') top center no-repeat;">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-4">
                    <h1 class="package-title"><?php the_title(); ?></h1>
                    <div class="package-teaser">
                        <?php echo $packageTeaser; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row package-content-wrapper">
        <div class="col-sm-10 col-sm-offset-1">

            <div class="card">
                <div class="card-header text-center">
                    <h2 class="card-title text-primary text-center"><?php the_title(); ?></h2>
                </div>
                <div class="card-body">
                    body
                </div>
                <div class="card-footer">
                    footer
                </div>
            </div>

        </div>
    </div>

</article><!-- #post-## -->

<!-- <pre><?php print_r($package); ?></pre> -->
