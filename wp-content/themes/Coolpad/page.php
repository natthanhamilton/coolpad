<?php
$output = "";
if (have_posts()) {
    while (have_posts()) {
        if (get_post_type() == 'blog') echo '<!-- Checking for blog type -->';
        $id = the_ID();
        $content = the_content();
        $post = the_post();
        $classes = post_class('page');
        $output .= "{$post}<div id='post-{$id}' {$classes}>{$content}</div>";
    }
}

// Output

get_header();
echo "<div id='content'>{$output}</div>";
get_footer();
?>
