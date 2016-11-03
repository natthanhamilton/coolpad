<!-- #page-header -->
<div id="page-header"
     style="background: linear-gradient(to bottom, rgba(238, 238, 238, 0), rgba(0, 0, 0, 0.51)), url('http://res.cloudinary.com/coolpad/image/upload/v1468479706/support/page_cover.jpg')">
    <div class="ht-container">
        <div class="title"><?php if (bbp_is_search()) {
                _e("Search: ", "framework");
            } ?><?php echo of_get_option('st_forum_title'); ?>
        </div>
        <div id="live-search">
            <form role="search" method="get" id="searchform" class="clearfix" action="<?php bbp_search_url(); ?>"
                  autocomplete="off">
                <input type="text"
                       onfocus="if (this.value == '<?php _e("Search the forum...", "framework") ?>') {this.value = '';}"
                       onblur="if (this.value == '')  {this.value = '<?php _e("Search the forum...", "framework") ?>';}"
                       value="<?php _e("Search the forum...", "framework") ?>" name="bbp_search" id="s"/>
            </form>
        </div>
    </div>
</div>

<!-- #page-subnav -->
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
<!-- /#page-subnav -->