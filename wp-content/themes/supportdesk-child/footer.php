<?php
switch_to_blog('1');
$post = get_page_by_path('footer');
echo apply_filters('the_content', $post->post_content);
switch_to_blog('2');
?>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript"
        src="<?php echo get_site_url() . '/wp-content/themes/supportdesk-child/js/global.js'; ?>"></script>
<?php wp_footer(); ?>
</body>
</html>