<?php
switch_to_blog('1');
$post = get_page_by_path('footer');
echo apply_filters('the_content', $post->post_content);
switch_to_blog('3');
?>
<?php
global $flatsome_opt;
if (isset($flatsome_opt['html_scripts_footer'])) {
	// Insert footer scripts
	echo $flatsome_opt['html_scripts_footer'];
}
wp_footer();
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript"
        src="<?php echo get_site_url() . '/wp-content/themes/flatsome-child/js/global.js'; ?>"></script>
</body>
</html>