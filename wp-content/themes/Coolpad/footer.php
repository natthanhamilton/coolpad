<?php
$post = get_page_by_path('footer');
echo apply_filters('the_content', $post->post_content);
?>
<?php wp_footer(); ?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/2.23.2/mediaelement-and-player.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/assets/js/global.js'; ?>"></script>
</body>
</html>