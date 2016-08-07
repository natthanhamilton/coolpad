<?php get_header(); ?>
<div id="content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="post-single">
            <div class="post-content">
                <?php the_content(__('Read more')); ?>
            </div>
        </div><!--.post-single-->
    <?php endwhile;
    else: ?>
        <div class="no-results">
            <p><strong><?php _e('There has been an error.'); ?></strong></p>
            <p><?php _e('We apologize for any inconvenience, please hit back on your browser or use the search form below.'); ?></p>
            <?php get_search_form(); /* outputs the default Wordpress search form */ ?>
        </div><!--noResults-->
    <?php endif; ?>
</div><!--#content-->
<?php get_footer(); ?>
