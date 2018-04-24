<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title><?php if (is_category()) {
            echo 'Category Archive for &quot;';
            single_cat_title();
            echo '&quot; | ';
            bloginfo('name');
        } elseif (is_tag()) {
            echo 'Tag Archive for &quot;';
            single_tag_title();
            echo '&quot; | ';
            bloginfo('name');
        } elseif (is_archive()) {
            wp_title('');
            echo ' Archive | ';
            bloginfo('name');
        } elseif (is_search()) {
            echo 'Search for &quot;' . wp_specialchars($s) . '&quot; | ';
            bloginfo('name');
        } elseif (is_home()) {
            bloginfo('name');
            echo ' | ';
            bloginfo('description');
        } elseif (is_404()) {
            echo 'Error 404 Not Found | ';
            bloginfo('name');
        } elseif (is_single()) {
            wp_title('');
        } else {
            echo wp_title('');
            echo ' | ';
            bloginfo('name');
        } ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?php require_once(ABSPATH . 'wp-content/themes/assets/header.php'); ?>
    <link rel="icon" href="http://res.cloudinary.com/coolpad/image/upload/v1464995065/favicon.jpg" type="image/x-icon"/>
    <?php wp_head(); ?>
    <link rel="stylesheet" type="text/css" media="all"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="all"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/2.7.7/jquery.fullPage.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/2.23.2/mediaelementplayer.min.css"/>
    <link rel="stylesheet" type="text/css" media="all"
          href="<?php echo get_template_directory_uri() . '/assets/css/style.css'; ?>"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body <?php body_class(); ?>>
<div id="body-wrapper" style="overflow-x: hidden">
    <?php require_once(ABSPATH . 'wp-content/themes/assets/navigation.php'); ?>