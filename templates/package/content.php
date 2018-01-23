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

/**
 * Custom meta fields
 */
$currentID = get_the_ID();
$packageTeaser = get_post_meta($currentID, 'package_teaser', true);
$packageBanner = get_post_meta($currentID, 'package_banner', true);
$packagePrice = get_post_meta($currentID, 'package_price', true);
$packageLocation = get_post_meta($currentID, 'package_location', true);
$packageSEOAd = get_post_meta($currentID, 'package_seo_ad', true);
$packageSEOContent = get_post_meta($currentID, 'package_seo_content', true);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="row">
        <div class="package-banner" style="min-height:333px; padding:0px 5px; background: url('<?php echo $packageBanner['url']; ?>') top center no-repeat;">
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
        <?php
        // Get Yoast primary category
        $primary_cat_id=get_post_meta($currentID, '_yoast_wpseo_primary_category', true);

        if ($primary_cat_id) {
            $package_cat = get_term($primary_cat_id);
        }

        $category = get_the_category() ;
        ?>
        <div class="col-sm-8">
            <div class="package-post-wrapper">
                <div class="row package-navigation-wrapper">
                    <?php
                    $prev_post = get_previous_post($category[0]->term_id);
                    if (!empty($prev_post)): ?>
                      <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>"><div id="package-previous-button">&nbsp;</div></a>
                    <?php endif ?>

                    <?php
                    $next_post = get_next_post($category[0]->term_id);
                    if (!empty($next_post)): ?>
                      <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>"><div id="package-next-button">&nbsp;</div></a>
                    <?php endif; ?>

                </div>
                <?php the_post(); ?>
                <div class="package-thumbnail-wrapper">
                    <?php the_post_thumbnail('medium'); ?>
                </div>
                <?php the_content(); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <h2>Additional Tours</h2>
            <?php

                $args = [
                    // Change these category SLUGS to suit your use.
                    'post_type' => 'package',
                    'cat' => $category[0]->term_id,
                    'orderby' => 'date',
                    'order' => 'ASC',
                    'posts_per_page' => -1,
                    'post__not_in' => [$currentID],
                    'post_status' => 'publish'
                ];
                $list_of_posts = new WP_Query($args);
                wp_reset_postdata();
            ?>
            <?php while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); $pId = get_the_ID(); ?>
                <div class="sp-column-wrapper">
                    <div class="sp-package-wrapper" id="<?php echo 'package-id-'.$pId?>">
                        <div class="row">
                            <div class="col-xs-4">
                                <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('small');
                                    }
                                ?>
                            </div>
                            <div class="col-xs-8 padding-left-reset">
                                <div class="sp-package-title">
                                    <?php the_title(); ?>
                                </div>
                                <?php echo get_post_meta($pId, "package_teaser", true); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-reset sp-action-wrapper">
                        <div class="col-sm-6 sp-action" data-toggle="modal" data-target="#myModal-<?php echo $pId; ?>">
                            <a href="<?php echo get_permalink(); ?>">Details</a>
                        </div>
                        <div id="book-now-<?php echo $pId; ?>" post-id="<?php echo $pId; ?>" class="col-sm-6 sp-action" >
                            <a href="#">Book Now</a>
                        </div>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="package-seo-wrapper" class="row">
        <div class="col-sm-3">
            <?php echo $packageSEOAd; ?>
        </div>
        <div class="col-sm-9">
            <?php echo $packageSEOContent; ?>
        </div>
    </div>

</article><!-- #post-## -->
