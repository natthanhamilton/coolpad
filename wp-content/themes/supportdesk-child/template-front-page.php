<?php
/**
 * Template Name: Homepage
 */
get_header(); ?>

<?php
// get the id of the front page
$st_front_id            = get_option('page_on_front ');
$st_hp_sidebar_position = of_get_option('st_hp_sidebar');
?>

<?php if (of_get_option('st_hp_headline') || (of_get_option('st_hp_tagline')) || (of_get_option('st_hp_search') == 1)) { ?>
    <!-- #page-header -->
    <div id="page-header" class="clearfix" style="height: 487px;">
        <div class="ht-container">
            <div class="title"><?php echo of_get_option('st_hp_headline'); ?></div>
            <?php if (of_get_option('st_hp_search') == 1) { ?>
                <!-- #live-search -->
                <div id="live-search">
                    <form role="search" method="get" id="searchform" class="clearfix"
                          action="<?php echo home_url('/'); ?>" autocomplete="off">
                        <input type="text"
                               onfocus="if (this.value == '<?php _e("", "framework") ?>') {this.value = '';}"
                               onblur="if (this.value == '')  {this.value = '<?php _e("", "framework") ?>';}"
                               value="<?php _e("", "framework") ?>" name="s" id="s"/>
                        <input type="hidden" name="post_type[]" value="st_kb"/>
                    </form>
                </div>
                <!-- /#live-search -->
            <?php } ?>
        </div>
    </div>
    <!-- /#page-header -->
<?php } ?>


<?php
if (of_get_option('st_hpblock') == '2col') {
    $st_hpblock_col = 'col-half';
} elseif (of_get_option('st_hpblock') == '3col') {
    $st_hpblock_col = 'col-third';
} elseif (of_get_option('st_hpblock') == '4col') {
    $st_hpblock_col = 'col-fourth';
} else {
    $st_hpblock_col = 'col-third';
}
$args     = [
    'post_type'      => 'st_hpblock',
    'posts_per_page' => '-1',
    'orderby'        => 'menu_order',
    'order'          => 'ASC'
];
$wp_query = new WP_Query($args);
if ($wp_query->have_posts()) : ?>

    <!-- #features-list -->

    <div id="features-list">
        <div class="ht-container">
            <div class="row stacked">

                <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

                    <div class="column <?php echo $st_hpblock_col ?>">

                        <?php if (get_post_meta($post->ID, '_st_hpblock_link', TRUE))
                        { ?><a href="<?php echo get_post_meta($post->ID, '_st_hpblock_link', TRUE); ?>"><?php } ?>
                            <?php if (get_post_meta($post->ID, '_st_hpblock_icon', TRUE)) { ?>
                                <div class="feature-icon"><img alt=""
                                                               src="<?php echo get_post_meta($post->ID,
                                                                                             '_st_hpblock_icon',
                                                                                             TRUE); ?>"/>
                                </div>
                            <?php } ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if (get_post_meta($post->ID, '_st_hpblock_link', TRUE))
                            { ?></a><?php } ?>
                        <?php if (get_post_meta($post->ID, '_st_hpblock_text', TRUE)) { ?>
                            <p><?php echo get_post_meta($post->ID, '_st_hpblock_text', TRUE); ?></p>
                        <?php } ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <!-- /#features-list -->
<?php endif;
wp_reset_postdata(); ?>


<?php
// Show homepage content if it's present
$post    = get_page($st_front_id);
$content = apply_filters('the_content', $post->post_content);
if ($content != '') { ?>
    <!-- #homepage-content -->
    <div id="homepage-content">
        <div class="ht-container">
            <?php echo $content; ?>
        </div>
    </div>
    <!-- /#homepage-content -->
<?php } ?>


    <!-- #Product Carousel -->
    <div class="container home-products-carousel">
        <div class="row">
            <div class="col-md-12">
                <div class="carousel slide" id="home-products-carousel">
                    <div class="carousel-inner">
                        <div class="item active">
                            <a href="http://support.coolpad.us/defiant" title="Coolpad Defiant">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://support.coolpad.us/wp-content/uploads/sites/2/2017/06/front-image-300X600.png"
                                             alt="Coolpad Defiant">
                                    </div>
                                    <span class="title">Defiant</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/canvas" title="Coolpad Canvas">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://coolpad.us/wp-content/uploads/2017/05/canvas.jpg"
                                             alt="Coolpad Canvas">
                                    </div>
                                    <span class="title">Canvas</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/conjr" title="Coolpad Conjr">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="https://coolpad.us/wp-content/themes/Coolpad/assets/images/phones/conjr/gallery/front.jpg"
                                             alt="Coolpad Conjr">
                                    </div>
                                    <span class="title">Conjr</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/tattoo" title="Coolpad Tattoo">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536542/support/products/tattoo.jpg"
                                             alt="Coolpad Tattoo">
                                    </div>
                                    <span class="title">Tattoo</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/rogue" title="Coolpad Rogue">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536514/support/products/rogue.jpg"
                                             alt="Coolpad Rogue">
                                    </div>
                                    <span class="title">Rogue</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/arise" title="Coolpad Arise">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536523/support/products/arise.jpg"
                                             alt="Coolpad Arise">
                                    </div>
                                    <span class="title">Arise</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/catalyst" title="Coolpad Catalyst">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536517/support/products/catalyst.jpg"
                                             alt="Coolpad Catalyst">
                                    </div>
                                    <span class="title">Catalyst</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/flo" title="Coolpad Flo">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536558/support/products/flo.jpg"
                                             alt="Coolpad Flo">
                                    </div>
                                    <span class="title">Flo</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/quattroii" title="Coolpad Quattro II">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536536/support/products/quattroii.jpg"
                                             alt="Coolpad Quattro II">
                                    </div>
                                    <span class="title">Quattro II</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/quattro" title="Coolpad Quattro">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536513/support/products/quattro.jpg"
                                             alt="Coolpad Quattro">
                                    </div>
                                    <span class="title">Quattro</span>
                                </div>
                            </a>
                        </div>
                        <div class="item">
                            <a href="http://support.coolpad.us/diamante" title="Coolpad Diamante">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="image">
                                        <img class="img-responsive"
                                             src="http://res.cloudinary.com/coolpad/image/upload/v1468536613/support/products/Diamate.jpg"
                                             alt="Coolpad Diamante">
                                    </div>
                                    <span class="title">Diamante</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <a class="left carousel-control" href="#home-products-carousel" data-slide="prev"><i
                            class="fa fa-angle-left"></i></a>
                    <a class="right carousel-control" href="#home-products-carousel" data-slide="next"><i
                            class="fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /#Product Carousel -->


    <!-- #primary -->
    <div id="primary" class="sidebar-<?php echo $st_hp_sidebar_position; ?> clearfix">
        <div id="home-content" class="ht-container">
            <section id="content" role="main">
                <?php
                if (is_active_sidebar('st_sidebar_homepage_widgets')) {
                    echo '<div id="homepage-widgets" class="row stacked">';
                    dynamic_sidebar('st_sidebar_homepage_widgets');
                    echo '</div>';
                }
                ?>
            </section>
        </div>
    </div>
    <!-- /#primary -->

<?php get_footer(); ?>