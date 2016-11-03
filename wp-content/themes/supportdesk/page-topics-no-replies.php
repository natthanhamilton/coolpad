<?php
/**
 * Template Name: bbPress - Topics (No Replies)
 *
 * @package    bbPress
 * @subpackage Theme
 */
get_header(); ?>

<?php $st_page_sidebar_pos = get_post_meta($post->ID, '_st_page_sidebar', TRUE); ?>

	<!-- #page-header -->
	<div id="page-header" class="clearfix">
		<div class="ht-container">
			<h1><?php the_title(); ?></h1>
			<?php if (get_post_meta($post->ID, '_st_page_tagline', TRUE)) { ?>
				<p><?php echo get_post_meta($post->ID, '_st_page_tagline', TRUE); ?></p>
			<?php } ?>
		</div>
	</div>
	<!-- /#page-header -->

<?php if (!get_post_meta($post->ID, '_st_page_breadcrumbs', TRUE)) { ?>
	<!-- #breadcrumbs -->
	<div id="page-subnav" class="clearfix">
		<div class="ht-container">
			<?php
			$st_bbpress_breadcrumbs_args = [
				// Modify default BBPress Breadcrumbs
				'before' => '<nav class="bbp-breadcrumb">',
				'after'  => '</nav>',
				'sep'    => __('&frasl;', 'bbpress'),
			];
			bbp_breadcrumb($st_bbpress_breadcrumbs_args); ?>
		</div>
	</div>
	<!-- /#breadcrumbs -->
<?php } ?>


	<!-- #primary -->
	<div id="primary" class="sidebar-<?php echo $st_page_sidebar_pos; ?> clearfix">
		<div class="ht-container">
			<!-- #content -->
			<section id="content" role="main">

				<?php do_action('bbp_before_main_content'); ?>

				<?php do_action('bbp_template_notices'); ?>

				<?php while (have_posts()) : the_post(); ?>

					<div id="topics-front" class="bbp-topics-front">
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<div class="entry-content">

							<?php the_content(); ?>

							<div id="bbpress-forums">

								<?php bbp_breadcrumb(); ?>

								<?php bbp_set_query_name('bbp_no_replies'); ?>

								<?php if (bbp_has_topics(['meta_key' => '_bbp_reply_count', 'meta_value' => '1', 'meta_compare' => '<', 'orderby' => 'date', 'show_stickies' => FALSE])) : ?>

									<?php bbp_get_template_part('pagination', 'topics'); ?>

									<?php bbp_get_template_part('loop', 'topics'); ?>

									<?php bbp_get_template_part('pagination', 'topics'); ?>

								<?php else : ?>

									<?php bbp_get_template_part('feedback', 'no-topics'); ?>

								<?php endif; ?>

								<?php bbp_reset_query_name(); ?>

							</div>
						</div>
					</div><!-- #topics-front -->

				<?php endwhile; ?>

				<?php do_action('bbp_after_main_content'); ?>

			</section>
			<!-- #content -->

			<?php if ($st_page_sidebar_pos != 'off') {
				get_sidebar();
			} ?>
		</div>
	</div>
	<!-- #primary -->
<?php get_footer(); ?>