<?php
/**
  * Template Name: Package Promotions Template
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

    .package-thumb-wrapper img{
        width: 250px;
        height: auto;
    }
    .tab-content{
        line-height: 2em;
        font-family: 'Roboto';
        font-weight: 300;
        font-size: 14px !important;
        background-color: #ffffff;
        border-bottom: 3px solid #ebebeb;
    }

    .tab-content:hover, .tours-package-wrapper a div:hover{
        cursor:pointer;
        background-color: #e4eafd;
    }

    .tours-package-wrapper a{
        font-color: #000 !important;
        text-decoration: none;
    }

    .tab-content h4{
        max-width: 410px;
        color: blue;
        text-decoration: underline;
        margin: 0 0 10px 0;
    }

    .tab-content p {
        margin: 0;
        color: black;
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
        font-size: 18px !important;
        font-weight: normal !important;
    }
    .package-book-button{
        padding: 13px 16px;
        background-color: #313193;
        color: white;
        display: inline-block;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }
    .package-book-button a{
        color: white;
        text-decoration: none;
        font-size: 14px;
    }
    #main-content {
        background: none !important;
    }
    .single-tours-package-wrapper, .tours-package-wrapper {
        position: relative;
        float: left;
        min-height: 200px;
        width: 100%;
    }


    .tours-package-wrapper {
        width: 100%;
    }

    .single-tours-package-wrapper {

    }

    .single-tours-package-wrapper a {
        text-decoration: none;
    }

    .single-tours-package-wrapper a div{
        width: auto;
        text-decoration: none;
        background-color: #fff;
        border-bottom: 3px solid #ebebeb;
        padding: 10px;
    }



    .tours-package-wrapper h3, .single-tours-package-wrapper h3 {
        font-family: 'Roboto';
        font-weight: 300;
        font-size: 27px !important;
        line-height: 1em;
        padding: 10px;
        color: #fff;
        -webkit-border-radius: 10px 10px 0px 0px;
        -moz-border-radius: 10px 10px 0px 0px;
        border-radius: 10px 10px 0px 0px;
        background-color: #5366a1;
        margin: 0;
        text-align: center;
    }

    .tours-package-wrapper a > div{
        background-color: #fff;
        border-bottom: 3px solid #ebebeb;
        padding: 20px;
    }

    .package-thumb-wrapper {
        padding: 5px;
        border: none;
        background-color: transparent;
    }

    .package-thumb-wrapper:hover {
        border: none !important;
        background-color: #e4eafd !important;
    }




    /* Small devices (tablets, 768px and up) */
    @media (min-width: 768px) {
        .single-tours-package-wrapper a {
            width: 452px;
            float: left;
            padding:20px;

        }

        .single-tours-package-wrapper a:nth-child(odd), .tours-package-wrapper:nth-child(even)  {
            margin-left:10px;
        }

        #package-posts {
            padding: 20px;
        }

        .single-tours-package-wrapper a div{
            background-color: transparent;
            border-bottom: none;
            padding: 0;
        }



        .tours-package-wrapper {
            width: 490px;
        }
    }

    /* Medium devices (desktops, 992px and up) */
    @media (min-width: 992px) {

    }

    /* Large devices (large desktops, 1200px and up) */
    @media (min-width: 1200px) {

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
                        $package_one_args = [
                            // Change these category SLUGS to suit your use.
                            'category_name' => get_post_meta($theID, "package-category-one", true),
                            'orderby' => 'date',
                            'order' => 'ASC',
                            'posts_per_page' => -1
                        ];

                        $list_of_posts_one = new WP_Query($package_one_args);
                        $mobile_list_of_posts_one = $list_of_posts_one;
                        $package_category_one_title = get_post_meta($theID, "package-category-one-title", true);

                        //check if there is 2nd category
                        $package_category_two = get_post_meta($theID, "package-category-two", true);
                        if ($package_category_two) {
                            $package_two_args = [
                                // Change these category SLUGS to suit your use.
                                'category_name' => get_post_meta($theID, "package-category-two", true),
                                'orderby' => 'date',
                                'order' => 'ASC',
                                'posts_per_page' => -1
                            ];

                            $list_of_posts_two = new WP_Query($package_two_args);
                            $mobile_list_of_posts_two = $list_of_posts_two;
                            $package_category_two_title = get_post_meta($theID, "package-category-two-title", true);
                        }

                    ?>

                    <?php if ($package_category_two) : ?>
                    <section class="tours-package-wrapper">
                            <h3><?php echo $package_category_one_title; ?></h3>
                            <?php while ($list_of_posts_one->have_posts()) : $list_of_posts_one->the_post(); ?>
                            <a href="<?php echo get_post_meta(get_the_ID(), "booking-link", true)?>" rel="nofollow" class="tab-content">
                            <div >
                                <h4 class="package-header"><?php the_title(); ?></h4>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="package-thumb-wrapper">
                                <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <?php endif; ?>
                                <?php the_content(); ?>
                            </div>
                            </a>
                            <?php endwhile; ?>

                    </section>

                    <section class="tours-package-wrapper">
                            <h3><?php echo $package_category_two_title; ?></h3>
                            <?php while ($list_of_posts_two->have_posts()) : $list_of_posts_two->the_post(); ?>
                            <a href="<?php echo get_post_meta(get_the_ID(), "booking-link", true)?>" rel="nofollow" class="tab-content">
                            <div>
                                <h4 class="package-header"><?php the_title(); ?></h4>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="package-thumb-wrapper">
                                <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <?php endif; ?>
                                <?php the_content(); ?>
                            </div>
                            </a>
                            <?php endwhile; ?>

                    </section>
                    <?php else: ?>

                    <section class="single-tours-package-wrapper">
                            <h3><?php echo $package_category_one_title; ?></h3>
                            <?php while ($list_of_posts_one->have_posts()) : $list_of_posts_one->the_post(); ?>
                            <a href="<?php echo get_post_meta(get_the_ID(), "booking-link", true)?>" rel="nofollow" class="tab-content">
                            <div>
                                <h4 class="package-header"><?php the_title(); ?></h4>
                                <?php if (has_post_thumbnail()): ?>
                                <div class="package-thumb-wrapper">
                                <?php the_post_thumbnail('medium'); ?>
                                </div>
                                <?php endif; ?>
                                <?php the_content(); ?>
                            </div>
                            </a>
                            <?php endwhile; ?>

                    </section>

                    <?php endif; ?>

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
            <script type="text/javascript">

            </script>




<?php get_footer(); ?>
