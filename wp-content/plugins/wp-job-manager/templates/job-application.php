<?php if ($apply = get_the_job_application_method()) :
	wp_enqueue_script('wp-job-manager-job-application');
	?>
	<div class="job_application application">
		<?php do_action('job_application_start', $apply); ?>

		<input type="button" class="application_button button" value="<?php _e('Apply for job', 'wp-job-manager'); ?>"/>

		<?php do_action('job_application_end', $apply); ?>
	</div>
<?php endif; ?>
