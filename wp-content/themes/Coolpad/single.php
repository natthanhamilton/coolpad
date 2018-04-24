<?php
get_header();
echo "<div id='content'>";
if (have_posts()) {
    while (have_posts()) {
        the_post();

        if (get_post_type() == 'blog') {
            ?>
            <script>
                // Fill in your MailChimp popup settings below.
                // These can be found in the original popup script from MailChimp.
                var mailchimpConfig = {
                    baseUrl: 'mc.us5.list-manage.com',
                    uuid: '00514edbe8cf586789cd0ea24',
                    lid: '3e8b1c7de6'
                };

                // No edits below this line are required
                var chimpPopupLoader = document.createElement("script");
                chimpPopupLoader.src = '//s3.amazonaws.com/downloads.mailchimp.com/js/signup-forms/popup/embed.js';
                chimpPopupLoader.setAttribute('data-dojo-config', 'usePlainJson: true, isDebug: false');

                var chimpPopup = document.createElement("script");
                chimpPopup.appendChild(document.createTextNode('require(["mojo/signup-forms/Loader"], function (L) { L.start({"baseUrl": "' +  mailchimpConfig.baseUrl + '", "uuid": "' + mailchimpConfig.uuid + '", "lid": "' + mailchimpConfig.lid + '"})});'));

                jQuery(function ($) {
                    document.body.appendChild(chimpPopupLoader);

                    $(window).load(function () {
                        document.body.appendChild(chimpPopup);
                    });

                });
            </script>
            <?
        }

        $title = get_the_title();
        $content = apply_filters('the_content', $post->post_content);
        $background = get_the_post_thumbnail_url();
        $time = get_the_time('F j, Y');
        $addtoany = do_shortcode('[addtoany]');

        ?><div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>><?

        echo "<post-cover style='background-image: linear-gradient(rgba(0, 0, 0, 0) 39%, #000 100%), url({$background})'>
                            <div class='wrapper'>
                                <description>{$time}</description>
                                {$addtoany}
                                <title>{$title}</title>
                            </div>
                        </post-cover>
                        <post>
                            <article>{$content}</article>
                        </post>
                    </div>";
    }
}
echo "</div>";
get_footer();
?>

