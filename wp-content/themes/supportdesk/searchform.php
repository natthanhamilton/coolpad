<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
        <input type="text" value="Search..." name="s" id="s" onblur="if (this.value == '')  {this.value = 'Search...';}" onfocus="if (this.value == 'Search...')  
{this.value = '';}" />
<?php if( is_home() || 'post' == get_post_type() || is_category() || is_tag() ) { ?><input type="hidden" name="post_type" value="post" /><?php } ?>
</form>
