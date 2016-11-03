<!-- #page-header -->
<div id="page-header"
     style="background: linear-gradient(to bottom, rgba(238, 238, 238, 0), rgba(0, 0, 0, 0.51)), url('http://res.cloudinary.com/coolpad/image/upload/v1468479706/support/page_cover.jpg')">
	<div class="ht-container">
		<div class="title"><?php if (is_search()) {
				_e("Search: ", "framework");
			} ?><?php _e("Knowledge Base", "framework") ?></div>
		<div id="live-search">
			<form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url('/'); ?>"
			      autocomplete="off">
				<input type="text"
				       onfocus="if (this.value == '<?php _e("Search the knowledge base...",
				                                            "framework") ?>') {this.value = '';}"
				       onblur="if (this.value == '')  {this.value = '<?php _e("Search the knowledge base...",
				                                                              "framework") ?>';}"
				       value="<?php _e("Search the knowledge base...", "framework") ?>" name="s" id="s"/>
				<input type="hidden" name="post_type" value="st_kb"/>
			</form>
		</div>
	</div>
</div>
<!-- #page-header -->
<!-- #breadcrumbs -->
<div id="page-subnav" class="clearfix">
	<div class="ht-container">
		<?php st_breadcrumb(); ?>
	</div>
</div>
<!-- /#breadcrumbs -->