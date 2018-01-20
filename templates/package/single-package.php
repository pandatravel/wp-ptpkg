<?php
/**
* The Template for displaying all package single posts.
*
* @package GetReal
* @subpackage versionthree
* @since versionthree 1.0
* @created by: Allen Cazar
*/

get_header();
setPostViews(get_the_ID());

/*
    get advance custom field variables
*/
$packageBanner = get_field_object("package-banner");
$packagePrice = get_field_object("package-price");
$packageLocation = get_field_object("package-location");
$packageTeaser = get_field_object("package-teaser");
$packageSEOAd = get_field_object("package-seo-ad");
$packageSEOContent = get_field_object("package-seo-content");

?>
    <script type="application/ld+json">
    {
        "@context" : "http://schema.org",
        "@type" : "Product",
        "name" : "<?php the_title(); ?>",
        "offers" : {
            "@type" : "Offer",
            "price" : "<?php echo $packagePrice['value']; ?>"
        },
        "location" : {
            "@type" : "Place",
            "name" : "<?php echo $packageLocation['value']; ?>"
        }
    }
    </script>
                <!-- Start package-single.php -->
                <div id="page-body" class="package-single-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="package-banner" style="min-height:333px; padding:0px 5px; background: url('<?php echo $packageBanner['value']['url']; ?>') top center no-repeat;">
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <h1 class="package-title"><?php the_title(); ?></h1>
                                        <div class="package-teaser">
                                            <?php echo $packageTeaser['value']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row package-content-wrapper">
                            <?php
                                $currentID = get_the_ID();

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
                                    $debug_posts = $list_of_posts->posts;
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
                                                    <?php echo get_post_meta($pId, "package-teaser", true); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row margin-reset sp-action-wrapper">
                                            <div class="col-sm-6 sp-action" data-toggle="modal" data-target="#myModal-<?php echo $pId; ?>">
                                                <a href="<?php echo get_permalink(); ?>">Details</a>
                                            </div>
                                            <div id="book-now-<?php echo $pId; ?>" post-id="<?php echo $pId; ?>" class="col-sm-6 sp-action" >
                                                <a href="<?php $bookingLink =  get_field_object("package-booking-link", $pId); echo $bookingLink['value']; ?>">Book Now</a>
                                            </div>
                                        </div>

                                    </div>
                                <?php endwhile; ?>
                                <?php
                                    $args = [
                                        // Change these category SLUGS to suit your use.
                                        'post_type' => 'post',
                                        'category_name' => $category[0]->name,
                                        'orderby' => 'date',
                                        'order' => 'ASC',
                                        'posts_per_page' => -1,
                                        'post__not_in' => [$currentID],
                                        'post_status' => 'publish'
                                    ];
                                    $list_of_posts = new WP_Query($args);
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
                                                    <?php echo get_post_meta($pId, "package-teaser", true); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row margin-reset sp-action-wrapper">
                                            <div class="col-sm-6 sp-action" data-toggle="modal" data-target="#myModal-<?php echo $pId; ?>">
                                                <a href="<?php echo get_permalink(); ?>">Details</a>
                                            </div>
                                            <div id="book-now-<?php echo $pId; ?>" post-id="<?php echo $pId; ?>" class="col-sm-6 sp-action" >
                                                <a href="<?php $bookingLink =  get_field_object("package-booking-link", $pId); echo $bookingLink['value']; ?>">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <div id="package-seo-wrapper" class="row">
                            <div class="col-sm-3">
                                <?php echo $packageSEOAd['value']; ?>
                            </div>
                            <div class="col-sm-9">
                                <?php echo $packageSEOContent['value']; ?>
                            </div>
                        </div>


                    </div><!-- #main-content -->
                    <!-- End package-single.php -->
<?php get_footer(); ?>
