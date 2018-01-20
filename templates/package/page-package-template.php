<?php
/**
  * Template Name: Package Template
  * Description: Custom template for Tours and packages.
  */
get_header();
?>

<style>
    #main-content article p{
        font-size: 18px;
        line-height: 1.3em;
        font-weight: 300;
        color: white;
        margin-top: -15px;
        font-family: 'Roboto';
    }

    #mobile-main-content article p{
        font-size: 14px;
        line-height: 1.3em;
        font-weight: 300;
        color: white;
        margin-top: -10px;
        font-family: 'Roboto';
        text-align: left;
    }

    #mobile-package-content p {
        font-size: 14px;
    }

    .package-thumb-wrapper{
        float:right;
        width: 250px;
        border: 6px solid white;
        margin-left: 20px;
    }
    .package-wrapper {
        background-color: #ebebeb;
    }
    .package-thumb-wrapper img{
        width: 250px;
        height: auto;
    }
    .tab-content{
        line-height: 2em;
        font-family: 'Roboto';
        font-weight: 300;
        font-size: 14px !important;
    }

    .tab-content p a {
        font-color: #000 !important;
        text-decoration: none;
    }
    .tab-content h4{
        max-width: 410px;
    }
    #package-seo-ad ul li h5{
        font-family: 'Roboto';
        font-weight: normal !important;
        font-size: 1em !important;
    }
    #package-seo-ad{
        max-width: 265px;
        float:left;
        line-height: 2em;
        text-align: justify;
        color: black;
        font-family: 'Roboto';
        font-weight: 300;
    }
    #package-seo-content{
        float:left;
        min-height:300px;
        padding-left: 40px;
        width: 700px;
        line-height: 2em;
        text-align: justify;
        color: black;
        font-family: 'Roboto';
        font-weight: 300;
    }
    #package-seo-content > h4{
        font-size: 20px !important;
        font-weight: normal !important;
    }
    #package-seo-ad ul{
        list-style-type: none;
        padding-left: 20px;
    }
    #package-seo-ad > ul > li > p > a{
        color: black !important;
        font-weight: bold;
        text-decoration: none;

    }
    #package-seo-ad img{
        width: 100%;
    }
    #package-seo-section{
        margin-top:20px;
        width:100%;
        padding-bottom:20px;
    }
    .package-header{
        font-size: 22px !important;
        font-weight: normal !important;
    }
    .package-book-button,
    .package-button {
        padding: 13px 16px;
        background-color: #313193;
        color: white;
        display: inline-block;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }
    .package-book-button a,
    .package-button a {
        color: white;
        text-decoration: none;
        font-size: 14px;
    }
    #main-content {
        background: none !important;
    }

    #package-landing-content h1 {
        margin-bottom: 15px;
    }
</style>

    <div id="page-body">


            <?php if (have_posts()) : while (have_posts()) : the_post(); $theID = get_the_ID(); ?>
            <div id="main-content" class="clearfix">
                <article id="package-landing-content" style="min-height:333px; padding:0px 5px; text-align: right; background: url('<?php echo get_post_meta($theID, "package-bg-banner", true); ?>') top center no-repeat;">
                        <div class="clearfix">
                            <h1 style="text-align: left; color: white; margin-top:197px; font-weight: 300; font-size: 2.5em; font-family: 'Roboto', sans-serif; margin-bottom:16px">
                                <?php
                                if (strtolower(trim(get_post_meta(get_the_ID(), "package-hide-title", true))) == 'true') {
                                    echo '';
                                } else {
                                    the_title();
                                }
                                ?>
                            </h1>
                            <?php the_content(); ?>
                        </div>
                    </article>
                <div id="package-posts" class="clearfix">
                    <?php
                        $args_package = [
                            // Change these category SLUGS to suit your use.
                            'post_type' => 'package',
                            'category_name' => get_post_meta($theID, "package-category", true),
                            'orderby' => 'date',
                            'order' => 'ASC',
                            'posts_per_page' => -1
                        ];

                        $list_of_posts_packages = new WP_Query($args_package);
                        $mobile_list_of_posts_packages = $list_of_posts_packages;

                        $args = [
                            // Change these category SLUGS to suit your use.
                            'category_name' => get_post_meta($theID, "package-category", true),
                            'orderby' => 'date',
                            'order' => 'ASC',
                            'posts_per_page' => -1
                        ];

                        $list_of_posts = new WP_Query($args);
                        $mobile_list_of_posts = $list_of_posts;
                    ?>

                    <section class="package-wrapper hide-on-mobile" id="package-wrapper">
                        <div id="v-nav">
                            <ul>
                            </ul>
                            <?php while ($list_of_posts_packages->have_posts()) : $list_of_posts_packages->the_post(); ?>
                            <div class="tab-content">

                                <?php if (has_post_thumbnail()): ?>
                                <div class="package-thumb-wrapper">
                                <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <?php endif; ?>

                                <h4 class="package-header"><?php the_title(); ?></h4>

                                <p><?php the_content(); ?></p>
                                <span class="package-teaser"><?php echo get_post_meta(get_the_ID(), "package-teaser", true); ?></span>
                            </div>
                            <?php endwhile; ?>
                            <?php while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); ?>
                            <div class="tab-content">

                                <?php if (has_post_thumbnail()): ?>
                                <div class="package-thumb-wrapper">
                                <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <?php endif; ?>

                                <h4 class="package-header"><?php the_title(); ?></h4>

                                <p><?php the_content(); ?></p>
                                <span class="package-teaser"><?php echo get_post_meta(get_the_ID(), "package-teaser", true); ?></span>
                            </div>
                            <?php endwhile; ?>
                        </div>


                    </section>

                    <section class="mobile-package-wrapper hide-on-desktop" id="mobile-package-wrapper">
                            <?php while ($mobile_list_of_posts_packages->have_posts()) : $mobile_list_of_posts_packages->the_post(); ?>
                            <div class="mobile-tab-content">
                                <div style="font-family: 'Roboto';border-bottom:1px solid #dfdfdf; background-color: white; padding: 10px;">
                                    <div style="font-size: 1.11em; font-weight: bold;"><?php the_title(); ?></div>
                                    <span style="font-size: 1em; color: blue;"><?php echo get_post_meta(get_the_ID(), "package-teaser", true); ?></span>
                                </div>
                                <div class="mobile-package-content clearfix" style="display: none; padding: 10px; font-size:14px;">
                                    <h4 class="package-header"><?php the_title(); ?></h4>

                                    <p><?php the_content(); ?></p>
                                </div>

                            </div>
                            <?php endwhile; ?>
                            <?php while ($mobile_list_of_posts->have_posts()) : $mobile_list_of_posts->the_post(); ?>
                            <div class="mobile-tab-content">
                                <div style="font-family: 'Roboto';border-bottom:1px solid #dfdfdf; background-color: white; padding: 10px;">
                                    <div style="font-size: 1.11em; font-weight: bold;"><?php the_title(); ?></div>
                                    <span style="font-size: 1em; color: blue;"><?php echo get_post_meta(get_the_ID(), "package-teaser", true); ?></span>
                                </div>
                                <div class="mobile-package-content clearfix" style="display: none; padding: 10px; font-size:14px;">
                                    <h4 class="package-header"><?php the_title(); ?></h4>

                                    <p><?php the_content(); ?></p>
                                </div>

                            </div>
                            <?php endwhile; ?>
                    </section>

                </div>
                <section id="package-seo-section" class="clearfix">
                    <div id="package-seo-ad">
                        <?php echo get_post_meta($theID, "package-ad", true); ?>
                    </div>
                    <div id="package-seo-content">
                        <?php echo get_post_meta($theID, "package-seo-content", true); ?>
                    </div>
                    <div class="clearfix"></div>
                </section>
            </div><!-- #main-content -->
            <?php endwhile; endif; ?>



<?php get_footer(); ?>
