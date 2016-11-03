<?php get_header(); ?>
	<div id="content">
		<?php
		if (have_posts()) {
			while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
					<post-cover
						style="background-image: linear-gradient(rgba(0, 0, 0, 0) 39%, #000 100%), url('<?= get_the_post_thumbnail_url() ?>')">
						<?php
						echo "<div class='wrapper'>";
						echo "<description>" . get_the_time('F j, Y') . "</description>" . do_shortcode('[addtoany]');
						echo "<title>" . get_the_title() . "</title>";
						echo "</div>";
						?>
					</post-cover>
					<post>
						<?= "<article>" . apply_filters('the_content', $post->post_content) . "</article>"; ?>
					</post>
				</div>
			<?php endwhile;
		} /* end loop */ ?>
	</div>
<?php get_footer(); ?>