<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="description" content="<?php wp_title('');
	echo ' | ';
	bloginfo('description'); ?>"/>
	<meta charset="<?php bloginfo('charset'); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="icon" href="http://res.cloudinary.com/coolpad/image/upload/v1464995065/favicon.jpg" type="image/x-icon"/>
	<?php wp_head(); ?>
	<link rel="stylesheet" type="text/css" media="all"
	      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" media="all"
	      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" media="all"
	      href="<?php echo get_site_url() . '/wp-content/themes/flatsome-child/css/style.css'; ?>"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body <?php body_class(); ?>>
<div id="body-wrapper" style="overflow-x: hidden">
    <?php require_once(ABSPATH . 'wp-content/themes/assets/navigation.php'); ?>