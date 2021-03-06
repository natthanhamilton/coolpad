<?php
/**
 * The template for displaying the footer.
 *
 * @package flatsome
 */
global $flatsome_opt;
?>


</div><!-- #main-content -->
<?php
switch_to_blog('1');
$post = get_page_by_path('footer');
echo apply_filters('the_content', $post->post_content);
switch_to_blog('3');
?>
</div><!-- #wrapper -->

<!-- back to top -->
<a href="#top" id="top-link" class="animated fadeInUp"><span class="icon-angle-up"></span></a>

<?php if (isset($flatsome_opt['html_scripts_footer'])) {
    // Insert footer scripts
    echo $flatsome_opt['html_scripts_footer'];
} ?>

<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript"
        src="<?php echo get_site_url() . '/wp-content/themes/flatsome-child/js/global.js'; ?>"></script>
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 830603400;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/830603400/?guid=ON&amp;script=0"/>
    </div>
</noscript>
<?php wp_footer(); ?>
</body>
</html>