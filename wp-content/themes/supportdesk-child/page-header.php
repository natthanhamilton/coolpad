<?php
// get the id of the posts page
$st_index_id         = get_option('page_for_posts');
$st_page_sidebar_pos = get_post_meta($st_index_id, '_st_page_sidebar', TRUE);
$background_image    = 'http://res.cloudinary.com/coolpad/image/upload/v1468479706/support/page_cover.jpg';
?>

<?php if (is_category()) { ?>

	<!-- #page-header -->
	<div class="default-cover">
		<div class="wrapper"
		     style="background-image: url('<?= $background_image ?>')">
			<div class="content">
				<div class="title"><?php echo get_the_title($st_index_id); ?>
					> <?php echo single_cat_title('', FALSE); ?></div>
			</div>
		</div>
	</div>
	<!-- /#page-header -->

<?php } elseif (is_tag()) { ?>

	<!-- #page-header -->
	<div class="default-cover">
		<div class="wrapper"
		     style="background-image: url('<?= $background_image ?>')">
			<div class="content">
				<div class="title"><?php echo get_the_title($st_index_id); ?>
					> <?php __('Archives', 'framework') ?></div>
				<p><?php
					if (is_day()) :
						printf(__('Daily Archives for %s', 'framework'), '<span>' . get_the_date() . '</span>');
					elseif (is_month()) :
						printf(__('Monthly Archives for %s', 'framework'),
						       '<span>' . get_the_date(_x('F Y', 'monthly archives date format',
						                                  'framework')) . '</span>');
					elseif (is_year()) :
						printf(__('Yearly Archives for %s', 'framework'),
						       '<span>' . get_the_date(_x('Y', 'yearly archives date format',
						                                  'framework')) . '</span>');
					else :
						_e('Archives', 'framework');
					endif;
					?></p>
			</div>
		</div>
	</div>
	<!-- /#page-header -->

<?php } elseif (is_archive()) { ?>

	<!-- #page-header -->
	<div class="default-cover">
		<div class="wrapper"
		     style="background-image: url('<?= $background_image ?>')">
			<div class="content">
				<div class="title"><?php echo get_the_title($st_index_id); ?>
					> <?php echo single_tag_title('', FALSE); ?></div>
			</div>
		</div>
	</div>
	<!-- /#page-header -->

<?php } else { ?>

	<!-- #page-header -->
	<div class="default-cover">
		<div class="wrapper"
		     style="background-image: url('<?= $background_image ?>')">
			<div class="content">
				<div class="title"><?php if (is_search()) {
						_e("Search: ", "framework");
					} ?><?php echo get_the_title($st_index_id); ?></div>
			</div>
		</div>
	</div>
	<!-- /#page-header -->

<?php } ?>