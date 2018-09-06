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
$packageBannerUrl = wp_get_attachment_image_url(get_post_meta($currentID, 'package-banner', true), 'full');
$packageTeaser = get_post_meta($currentID, 'package-teaser', true);
$packagePrice = get_post_meta($currentID, 'package-price', true);
$packageLocation = get_post_meta($currentID, 'package-location', true);
$packageSEOAd = get_post_meta($currentID, 'package-seo-ad', true);
$packageSEOContent = get_post_meta($currentID, 'package-seo-content', true);

$primary_cat_id=get_post_meta($currentID, '_yoast_wpseo_primary_category', true);
if ($primary_cat_id) {
    $package_cat = get_term($primary_cat_id);
}
$category = get_the_category();

?>

<v-container>
<article id="post-<?php the_ID(); ?>" <?php post_class('package-details'); ?>>

    <v-layout wrap>
        <v-flex md8 sm12>

            <v-card>
                <v-card-text>
                    <v-card-actions>
                        <?php
                        $prev_post = get_previous_post($category[0]->term_id);
                        if (!empty($prev_post)): ?>
                        <v-btn flat small outline absolute left href="<?php echo esc_url(rtrim(get_permalink($prev_post->ID), "book/")); ?>" color="orange"><v-icon dark left>arrow_back</v-icon>Previous</v-btn>
                        <?php endif; ?>
                        <?php
                        $next_post = get_next_post($category[0]->term_id);
                        if (!empty($next_post)): ?>
                        <v-btn flat small outline absolute right href="<?php echo esc_url(rtrim(get_permalink($next_post->ID), "book/")); ?>" color="orange">Next<v-icon dark right>arrow_forward</v-icon></v-btn>
                        <?php endif; ?>
                    </v-card-actions>
                </v-card-text>

                <v-card-title>
                    <span class="card-title text-primary text-sm-center"><?php the_title(); ?></span>
                </v-card-title>
                <v-card-text>
                    <div class="package-thumbnail-wrapper">
                        <?php the_post_thumbnail('medium', ['class' => 'img-thumbnail']); ?>
                    </div>
                    <?php the_content(); ?>
                </v-card-text>

                <v-card-actions>
                    <action-button label="Start Booking Now!" :id="<?php echo $currentID ?>" url="<?php echo get_post_permalink($currentID) ?>" color="primary" large block></action-button>
                </v-card-actions>
            </v-card>

        </v-flex>

        <v-flex md4 sm12>

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
            <v-container grid-list-lg fluid class="additional-package-details">
                <v-layout row wrap>
                    <v-flex xs12 mb-3>
                        <v-card>
                            <v-card-text>
                                <div class="title blue-grey--text text--darken-1 text-xs-center">Additional Tours</div>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                    <?php while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); $pId = get_the_ID(); ?>
                    <v-flex xs12 mb-2>
                        <v-card>
                            <v-container fluid fill-height grid-list-lg pb-0>
                                <v-layout row>
                                    <v-flex xs8>
                                        <div class="title blue-grey--text text--darken-3"><?php the_title(); ?></div>
                                        <div class=""><?php echo get_post_meta($pId, "package-teaser", true); ?></div>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-card-media src="<?php echo get_the_post_thumbnail_url(); ?>" height="125px" contain></v-card-media>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat block color="primary" href="<?php echo rtrim(get_permalink(), '/book'); ?>">Details</v-btn>
                                <!-- <v-btn flat block color="primary" href="<?php echo get_permalink(); ?>">Book Now</v-btn> -->
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <?php endwhile; ?>

                </v-layout>
            </v-container>

        </v-flex>

    </v-layout>

    <v-layout pt-4>
        <v-flex sm3>
            <?php echo $packageSEOAd; ?>
        </v-flex>
        <v-flex sm9>
            <?php echo $packageSEOContent; ?>
        </v-flex>
    </v-layout>

</article><!-- #post-## -->
</v-container>
