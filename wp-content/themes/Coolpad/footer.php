<?php
$post = get_page_by_path('footer');
echo apply_filters('the_content', $post->post_content);
?>
<?php wp_footer(); ?></div>
</body>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.19.0/TweenMax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/ScrollMagic.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.5/plugins/animation.gsap.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/assets/js/threesixty.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/assets/js/global.js'; ?>"></script>
</html>