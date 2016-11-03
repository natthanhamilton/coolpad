<?php
global $post;
$rand_ID = mt_rand();
?>
<div id="listing" xmlns="http://www.w3.org/1999/html">
	<div class="panel">
		<div class="panel-heading" id="heading">
			<div class="row">
				<div class="col-xs-2" id="icon">
					<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $rand_ID; ?>"><span
							class="icon"><i class="fa fa-plus"></i></span></a>
				</div>
				<div class="col-xs-10">
					<div class="line">
						<span class="title"><?php the_title(); ?></span><span class="ref">1234</span>
					</div>
					<span class="location"><?php the_job_location(FALSE); ?></span>
				</div>
			</div>
		</div>
		<div id="<?php echo $rand_ID; ?>" class="panel-collapse collapse">
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-2" id="icon"></div>
					<div class="col-xs-10">
						<span class="content"><?php echo get_the_content(); ?></span>
						<span class="apply"><button type="button" data-toggle="modal"
						                            data-target="#job-<?php echo $rand_ID; ?>">Apply Now
							</button</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="application/javascript">
	$('#job-<?php echo $rand_ID; ?>').on('shown.bs.modal', function () {
		$('#myInput').focus()
	})
</script>
<div class="modal fade" id="job-<?php echo $rand_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?php the_title(); ?>, <?php the_job_location(FALSE); ?></h4>
			</div>
			<div class="modal-body">
				<?php echo do_shortcode('[contact-form-7 id="336" title="Dynamic Job Application Form"]'); ?>
			</div>
		</div>
	</div>
</div>