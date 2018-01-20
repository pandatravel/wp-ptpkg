<?php
/**
  * Template Name: Package Inline Template
  * Description: Custom template for Tours and packages.
  */
get_header();

?>

<style>

    .margin-reset {
        margin: 0;
    }

    .padding-reset {
        padding: 0;
    }


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

    .package-thumb-wrapper img {
        width: 250px;
        height: auto;
    }

    #package-wrapper {
        padding: 20px;
        margin: -5px;
    }

    .sp-package-wrapper {
        border: 1px solid #e3e3e3;
        background-color: #ffffff;
        padding: 10px;
        border-bottom: none;
        height: 150px;
        overflow: hidden;
    }

    .sp-column-wrapper {
        padding:5px;
    }

    .sp-package-wrapper img{
        width: 100%;
        height: auto;
    }

    .sp-package-title {
        font-size: 1.5em;
        line-height: 1em;
        color: black;
    }

    .sp-action-wrapper {
        background-color: #ffffff;
    }

    .sp-action {
        border: 1px solid #e3e3e3;
        text-align: center;
        text-transform: uppercase;
        margin: 0px;
        font-family: 'Roboto', sans-serif;
        color: black;
        padding: 10px;
    }

    .sp-action a {
    }

    .sp-action:hover, .sp-action:active, .sp-action a:hover, .sp-action a:active {
        background-color: #688ac9;
        color: white;
        text-decoration: none;
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
    .col-sm-4.sp-column-wrapper:nth-child(3n+4) {
        clear: both;
    }

    .sp-action a {
        text-decoration: none;
        color: black;
    }
        #main-content {
            background: none !important;
        }

        #package-landing-content h1 {
            margin-bottom: 15px;
        }

        .sp-thumb-wrapper {
                padding-right: 0;
            }

        @media (min-width: 768px) {
            .sp-thumb-wrapper {
                padding-right: 15px;
            }
        }

        @media (min-width: 1000px) {
            .sp-thumb-wrapper {
                padding-right: 0;
            }
        }
    </style>

        <div id="page-body">


                <?php if (have_posts()) : while (have_posts()) : the_post(); $theID = get_the_ID(); $defaultThumb = get_post_meta($theID, "package-default-thumbnail", true); ?>
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
                        <section class="row margin-reset" id="package-wrapper">
                            <?php
                                $args = [
                                    // Change these category SLUGS to suit your use.
                                    'post_type' => 'package',
                                    'category_name' => get_post_meta($theID, "package-category", true),
                                    'orderby' => 'post_date',
                                    'order' => 'ASC',
                                    'posts_per_page' => -1,
                                    'post_status' => 'publish'
                                ];

                                $list_of_posts = new WP_Query($args);
                                $mobile_list_of_posts = $list_of_posts;
                            ?>
                            <?php while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); $pId = get_the_ID(); ?>
                            <div class="col-sm-4 padding-reset sp-column-wrapper">
                                <div class="sp-package-wrapper" id="<?php echo 'package-id-'.$pId?>">
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-12 col-md-4 sp-thumb-wrapper">
                                            <?php
                                                if (has_post_thumbnail()) {
                                                    the_post_thumbnail('small');
                                                } else {
                                                    echo '<img width="336" height="350" src="'. $defaultThumb .'" class="attachment-small size-small wp-post-image" >';
                                                }
                                            ?>
                                        </div>
                                        <div class="col-xs-8 col-sm-12 col-md-8">
                                            <div class="sp-package-title" pub-date="<?php the_date(); ?>">
                                                <?php the_title(); ?>
                                            </div>
                                            <?php echo get_post_meta($pId, "package-teaser", true); ?>
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal-<?php echo $pId; ?>" post-id="<?php echo $pId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><?php the_title(); ?></h4>
                                                  </div>
                                                  <div class="modal-body">
                                                    <?php the_content(); ?>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-reset sp-action-wrapper">
                                    <div class="col-sm-6 sp-action">
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
                                    'category_name' => get_post_meta($theID, "package-category", true),
                                    'orderby' => 'date',
                                    'order' => 'ASC',
                                    'posts_per_page' => -1
                                ];

                                $list_of_posts = new WP_Query($args);
                                $mobile_list_of_posts = $list_of_posts;
                            ?>
                            <?php while ($list_of_posts->have_posts()) : $list_of_posts->the_post(); $pId = get_the_ID(); ?>
                            <div class="col-sm-4 padding-reset sp-column-wrapper">
                                <div class="sp-package-wrapper" id="<?php echo 'package-id-'.$pId?>">
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-12 col-md-4 sp-thumb-wrapper">
                                            <?php
                                                if (has_post_thumbnail()) {
                                                    the_post_thumbnail('small');
                                                } else {
                                                    echo '<img width="336" height="350" src="'. $defaultThumb .'" class="attachment-small size-small wp-post-image" >';
                                                }
                                            ?>
                                        </div>
                                        <div class="col-xs-8 col-sm-12 col-md-8">
                                            <div class="sp-package-title">
                                                <?php the_title(); ?>
                                            </div>
                                            <?php echo get_post_meta($pId, "package-teaser", true); ?>
                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal-<?php echo $pId; ?>" post-id="<?php echo $pId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                              <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"><?php the_title(); ?></h4>
                                                  </div>
                                                  <div class="modal-body">
                                                    <?php the_content(); ?>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row margin-reset sp-action-wrapper">
                                    <div class="col-sm-6 sp-action" data-toggle="modal" data-target="#myModal-<?php echo $pId; ?>">
                                        Details
                                    </div>
                                    <div id="book-now-<?php echo $pId; ?>" post-id="<?php echo $pId; ?>" class="col-sm-6 sp-action sp-book-now" >
                                        Book Now
                                    </div>
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
